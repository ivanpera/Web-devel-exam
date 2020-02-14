<?php

class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname){
        $this->db = new mysqli($servername, $username, $password, $dbname);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }        
    }

    public function checkLogin($email, $password){
        $storedPassword = $this->getHashedPassword($email, $password);
        $query = "SELECT email, userPassword, dataNascita FROM utente WHERE email = ? AND userPassword =  ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss', $email, $storedPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function registerNewUser($email, $password, $name, $surname, $birthdate, $gender, $organizer) {
        if( $this->checkUsername($email) != 0) {
            return 1;
        }
        $storedPassword = $this->getHashedPassword($email, $password);
        $registrationDate = date("Y-m-d");
        $administrator = 0;

        $query = "INSERT INTO utente (email, userPassword, nome, cognome, dataNascita, genere, dataIscrizione, organizzatore, amministratore) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssssssii', $email, $storedPassword, $name, $surname, $birthdate, $gender, $registrationDate, $organizer, $administrator);
        $stmt->execute();
        return $stmt->insert_id != 0;
    }

    public function getPopularEvents( $NSFC = 0) {
        $query = "SELECT *
                  FROM (SELECT E.codEvento, E.nomeEvento, E.dataEOra, E.NSFC, E.descrizione, E.nomeImmagine, E.emailOrganizzatore,
                               L.codLuogo, L.nome AS nomeLuogo, L.indirizzo, L.urlMaps, L.capienzaMassima,
                               COUNT(P.codPrenotazione) as postiOccupati, (COUNT(P.codPrenotazione)/L.capienzaMassima * 100) as percPostiOccupati, COUNT(P.codPosto) AS maxPostiDisponibili
                        FROM evento E, luogo L, posto P
                        WHERE E.codLuogo = L.codLuogo
                        AND p.codEvento = E.codEvento
                        AND E.NSFC <= ?
                        AND DATEDIFF(E.dataEOra, ?) >= 0
                        GROUP BY E.codEvento, E.nomeEvento, E.dataEOra, E.NSFC, E.descrizione, E.nomeImmagine, E.emailOrganizzatore,
                                 L.codLuogo, L.nome, L.indirizzo, L.urlMaps, L.capienzaMassima
                        HAVING percPostiOccupati < 100
                       ) AS tabEventi LEFT JOIN
                       (SELECT EHC.codEvento, GROUP_CONCAT(ce.nomeCategoria SEPARATOR ', ') AS categorie
                        FROM evento_ha_categoria EHC, categoria_evento CE
                        WHERE EHC.codCategoria = ce.codCategoria
                        GROUP BY EHC.codEvento) AS tabCategorie
                  USING (codEvento)
                  ";
        $currentDate = date("Y-m-d H:i:s");
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("is", $NSFC, $currentDate);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategories() {
        $stmt = $this->db->prepare("SELECT codCategoria, nomeCategoria FROM categoria_evento");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSeatTypes() {
        $stmt = $this->db->prepare("SELECT codTipologia, nomeTipologia FROM tipologia_posto");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getEvent($codEvent) {
        $query = "SELECT *
                  FROM (SELECT E.codEvento, E.nomeEvento, E.dataEOra, E.NSFC, E.descrizione, E.nomeImmagine, E.emailOrganizzatore,
                               L.codLuogo, L.nome AS nomeLuogo, L.indirizzo, L.urlMaps, L.capienzaMassima,
                               COUNT(P.codPrenotazione) as postiOccupati, (COUNT(P.codPrenotazione)/L.capienzaMassima * 100) as percPostiOccupati, COUNT(P.codPosto) AS maxPostiDisponibili
                        FROM evento E, luogo L, posto P
                        WHERE E.codLuogo = L.codLuogo
                        AND p.codEvento = E.codEvento
                        AND E.codEvento = ?
                        GROUP BY E.codEvento, E.nomeEvento, E.dataEOra, E.NSFC, E.descrizione, E.nomeImmagine, E.emailOrganizzatore,
                                 L.codLuogo, L.nome, L.indirizzo, L.urlMaps, L.capienzaMassima
                       ) AS tabEventi LEFT JOIN
                       (SELECT EHC.codEvento, GROUP_CONCAT(ce.nomeCategoria SEPARATOR ', ') AS categorie
                        FROM evento_ha_categoria EHC, categoria_evento CE
                        WHERE EHC.codCategoria = ce.codCategoria
                          AND EHC.codEvento = ?
                        GROUP BY EHC.codEvento) AS tabCategorie
                  USING(codEvento)
                  ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $codEvent, $codEvent);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPlaces() {
        $stmt = $this->db->prepare("SELECT * FROM luogo");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertEvent($nomeEvento, $dataEOra, $NSFC, $descrizione, $nomeImmagine, $codLuogo, $emailOrganizzatore, $categorie, $tickets, $emailModeratori) {
        //Inserimento evento
        $queryEvento = "INSERT INTO evento(codEvento, nomeEvento, dataEOra, NSFC, descrizione, nomeImmagine, codLuogo, emailOrganizzatore) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($queryEvento);
        $codEvento = $this->getLastEventId() + 1;
        $stmt->bind_param("ississis", $codEvento, $nomeEvento, $dataEOra, $NSFC, $descrizione, $nomeImmagine, $codLuogo, $emailOrganizzatore);
        $stmt->execute();

        //inserimento evento_ha_categoria
        $queryCategorie = "INSERT INTO evento_ha_categoria(codCategoria, codEvento) VALUES (?, ".$codEvento.")";
        $stmt = $this->db->prepare($queryCategorie);
        foreach ($categorie as $codCategoria) {
            $stmt->bind_param("i", $codCategoria);
            $stmt->execute();
        }

        //inserimento posti
        for($i = 0; $i < count($tickets["type"]); $i++) {
            $lastSeatId = $this->getLastSeatId($codEvento);
            for($j = 1; $j <= intval($tickets["num"][$i]); $j++){
                $this->insertSeat($codEvento, $lastSeatId + $j, intval($tickets["cost"][$i]), intval($tickets["type"][$i]));
            }
        }

        //inserimento moderazione
        $queryModeratori = "INSERT INTO moderazione(emailModeratore, codEvento) VALUES (?, ".$codEvento.")";
        $stmt = $this->db->prepare($queryModeratori);
        foreach ($emailModeratori as $emailMod) {
            $stmt->bind_param("s", $emailMod);
            $stmt->execute();
        }

        return $codEvento;
    }

    public function getEventModerators($codEvento) {
        $stmt = $this->db->prepare("SELECT emailModeratore FROM moderazione WHERE codEvento = ".$codEvento);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* $searchParameters deve essere un'array associativo
       parametri controllati:
        0. NSFC ("NSFC") - obbligatorio
        1. nome evento ("nomeEvento")
        2. luogo ("codLuogo")
        3. data "minima" ("fromData")
        4. data "massima" ("toData")
        5. categorie ("categories")
       */
    public function searchEvent(array $searchParameters) {
        $queryEvento = "SELECT E.codEvento, E.nomeEvento, E.dataEOra, E.NSFC, E.descrizione, E.nomeImmagine, E.emailOrganizzatore,
                               L.codLuogo, L.nome AS nomeLuogo, L.indirizzo, L.urlMaps, L.capienzaMassima,
                               COUNT(P.codPrenotazione) as postiOccupati, (COUNT(P.codPrenotazione)/L.capienzaMassima * 100) as percPostiOccupati, COUNT(P.codPosto) AS maxPostiDisponibili
                        FROM evento E, luogo L, posto P
                        WHERE E.codLuogo = L.codLuogo
                          AND p.codEvento = E.codEvento
                          AND E.NSFC <= ".$searchParameters["NSFC"];
        if(isset($searchParameters["nomeEvento"])) {
            $queryEvento .= " AND E.nomeEvento LIKE '%".$searchParameters["nomeEvento"]."%'";
        }
        if(isset($searchParameters["codLuogo"])) {
            $queryEvento .= " AND E.codLuogo = ".$searchParameters["codLuogo"];
        }
        if(isset($searchParameters["fromData"])) {
            $queryEvento .= " AND DATEDIFF(E.dataEOra, '" .$searchParameters["fromData"]."') >= 0";
        }
        if(isset($searchParameters["toData"])) {
            $queryEvento .= " AND DATEDIFF(E.dataEOra, '".$searchParameters["toData"]."') <= 0";
        }
        $queryEvento .= " GROUP BY E.codEvento, E.nomeEvento, E.dataEOra, E.NSFC, E.descrizione, E.nomeImmagine, E.emailOrganizzatore,
                                 L.codLuogo, L.nome, L.indirizzo, L.urlMaps, L.capienzaMassima
                        HAVING percPostiOccupati < 100";
        
        $queryCategorie = "SELECT EHC.codEvento, GROUP_CONCAT(ce.nomeCategoria SEPARATOR ', ') AS categorie
                           FROM evento_ha_categoria EHC, categoria_evento CE
                           WHERE EHC.codCategoria = ce.codCategoria";

        if(isset($searchParameters["categories"]) && count($searchParameters["categories"]) > 0) {
            $queryCategorie .= " AND ( 0 ";
            foreach ($searchParameters["categories"] as $cat) {
                $queryCategorie .= " OR " . " CE.codCategoria = ".$cat;
            }
            $queryCategorie .= " )";
        }

        $queryCategorie .= " GROUP BY EHC.codEvento";

        $queryCompleta = "SELECT *
                          FROM (".$queryEvento.") AS tabEventi LEFT JOIN
                               (".$queryCategorie.") AS tabCategorie
                          USING (codEvento)";

        $stmt = $this->db->prepare($queryCompleta);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateEvent($codEvento, $nomeEvento, $dataEOra, $NSFC, $descrizione, $nomeImmagine, $codLuogo, $categorie, $tickets, $emailModeratori) {

        //Query vecchio nome (per notifiche)
        $stmtOldName =$this->db->prepare("SELECT nomeEvento FROM evento WHERE codEvento = ".$codEvento);
        $stmtOldName->execute();
        $oldEventName = $stmtOldName->get_result()->fetch_all(MYSQLI_ASSOC)[0]["nomeEvento"];

        //Update evento
        $queryEvento = "UPDATE evento
                        SET nomeEvento = '".$nomeEvento."', dataEOra = '".$dataEOra."', NSFC = ".$NSFC.", descrizione = '".$descrizione."', codLuogo = ".$codLuogo;
        if(!empty($nomeImmagine)){
            $queryEvento.=", nomeImmagine = ".$nomeImmagine;
        }
        $queryEvento.=" WHERE codEvento = ".$codEvento;
        $stmtEvento = $this->db->prepare($queryEvento);
        $stmtEvento->execute();

        //Update posti
        $oldSeats = $this->getSeatNumByTypeAndCost($codEvento);
        for ($i=0; $i < count($tickets["type"]); $i++) { 
            $seatIndex = $this->indexOfSeatByTypeAndCostFromDB($oldSeats, $tickets["type"][$i], $tickets["cost"][$i]);
            //Ticket type found, insert or delete difference between numPosti
            if ($seatIndex >= 0) {
                $seatNumDiff = $tickets["num"][$i] - $oldSeats[$seatIndex]["numTotPosti"];
                //Devo aggiungere posti
                if ( $seatNumDiff > 0) {
                    $queryPosti = "INSERT INTO posto(codEvento, codPosto, costo, codTipologia, codPrenotazione) VALUES (".$codEvento.", ?, ".$tickets["cost"][$i].", ".$tickets["type"][$i].", NULL)";
                    $stmtAddPosti = $this->db->prepare($queryPosti);
                    for ($j=0; $j < $seatNumDiff; $j++) { 
                        $newCodPosto = $this->getLastSeatId($codEvento) + 1;
                        $stmtAddPosti->bind_param("i", $newCodPosto);
                        $stmtAddPosti->execute();
                    }
                } elseif ($seatNumDiff < 0) {
                    //Tolgo posti
                    $queryPosti = "DELETE FROM posto WHERE codTipologia = ".$tickets["type"][$i]." AND costo = ".$tickets["cost"][$i]." AND codPrenotazione IS NULL LIMIT ".$seatNumDiff;
                    $stmtRmPosti = $this->db->prepare($queryPosti);
                    $stmtRmPosti->execute();
                }
            } else {
                //Ticket type not found, insert the number of tickets
                $queryPosti = "INSERT INTO posto(codEvento, codPosto, costo, codTipologia, codPrenotazione) VALUES (".$codEvento.", ?, ".$tickets["cost"][$i].", ".$tickets["type"][$i].", NULL)";
                $stmtAddPosti = $this->db->prepare($queryPosti);
                for ($j=0; $j < $tickets["num"][$i]; $j++) { 
                    $newCodPosto = $this->getLastSeatId($codEvento) + 1;
                    $stmtAddPosti->bind_param("i", $newCodPosto);
                    $stmtAddPosti->execute();
                }
            }
        }

        //Controllo se sono stati eliminati dei posti (dovrebbero essere sicuramente non prenotati)
        for ($i=0; $i < count($oldSeats); $i++) {
            $oldSeatIndex = $this->indexOfSeatByTypeAndCostFromList($tickets, $oldSeats[$i]["codTipologia"], $oldSeats[$i]["costo"]);
            //devo eliminare i posti corrisondenti
            if ($oldSeatIndex == -1) {
                $queryPosti = "DELETE FROM posto WHERE codTipologia = ".$oldSeats[$i]["codTipologia"]." AND costo = ".$oldSeats[$i]["costo"];
                $stmtRmPosti = $this->db->prepare($queryPosti);
                $stmtRmPosti->execute();
            }
            //Se è stato trovato è già stato gestito nel ciclo precedente
        }

        //Update categorie
        $stmtCategorie = $this->db->prepare("SELECT codCategoria FROM evento_ha_categoria WHERE codEvento = ".$codEvento);
        $stmtCategorie->execute();
        $resultCategorie = array_column($stmtCategorie->get_result()->fetch_all(MYSQLI_ASSOC), "codCategoria");
        $newCat = array_diff($categorie, $resultCategorie);
        $removedCat = array_diff($resultCategorie, $categorie);
        $stmtAddCat = $this->db->prepare("INSERT INTO evento_ha_categoria(codCategoria, codEvento) VALUES ( ?, ".$codEvento.")");
        foreach($newCat as $new) {
            $stmtAddCat->bind_param("i", $new);
            $stmtAddCat->execute();
        }
        $stmtRmCat = $this->db->prepare("DELETE FROM evento_ha_categoria WHERE codEvento = ".$codEvento." AND codCategoria = ?");
        foreach($removedCat as $rmCat) {
            $stmtRmCat->bind_param("i", $rmCat);
            $stmtRmCat->execute();
        }

        //Update moderatori
        $stmtModeratori = $this->db->prepare("SELECT emailModeratore FROM moderazione WHERE codEvento = ".$codEvento);
        $stmtModeratori->execute();
        $resultModeratori = array_column($stmtModeratori->get_result()->fetch_all(MYSQLI_ASSOC), "emailModeratore");
        $newModerators = array_diff($emailModeratori, $resultModeratori);
        $removedModerators = array_diff($resultModeratori, $emailModeratori);
        $stmtAddMod = $this->db->prepare("INSERT INTO moderazione (emailModeratore, codEvento) VALUES (?, ".$codEvento.")");
        foreach ($newModerators as $newMod) {
            $stmtAddMod->bind_param("s", $newMod);
            $stmtAddMod->execute();
        }
        $stmtRmMod = $this->db->prepare("DELETE FROM moderazione WHERE emailModeratore = ? AND codEvento = ".$codEvento);
        foreach ($removedModerators as $rmMod) {
            $stmtRmMod->bind_param("s", $rmMod);
            $stmtRmMod->execute();
        }

        //Aggiungi notifiche a utenti che osservano e ad utenti che hanno una prenotazione per l'evento
        $descrizioneNotifica = "";
        $titoloNotifica = "Un evento che ti interessa è stato modificato!";
        if ($oldEventName != $nomeEvento) {
            $descrizioneNotifica .= "L'evento '".$oldEventName."' è stato rinominato in '".$nomeEvento."' e ha subito variazioni. Controlla i dettagli dell'evento!";
        } else {
            $descrizioneNotifica .= "L'evento '".$oldEventName."' ha subito variazioni. Controlla i dettagli dell'evento!";
        }
        $interestedUsers = $this->getInterestedUsers($codEvento);
        $codNotifica = $this->getLastNotificationId($codEvento) + 1;
        $dataEOraInvio = date("Y-m-d H:i:s");
        $queryAddNot = "INSERT INTO notifica(codEvento, codNotificaEvento, titolo, descrizione, letta, dataEOraInvio, differenzaGiorni, emailUtente) VALUES (?, ?, ?, ?, 0, ?, NULL, ?)";
        $stmtNotifiche = $this->db->prepare($queryAddNot);
        foreach ($interestedUsers as $user) {
            $stmtNotifiche->bind_param("iissss", $codEvento, $codNotifica, $titoloNotifica, $descrizione, $dataEOraInvio, $user["emailUtente"]);
            $stmtNotifiche->execute();
            $codNotifica++;
        }

    }

    public function getSeatNumByTypeAndCost($codEvento) {
        $query = "SELECT posto.codTipologia, nomeTipologia, costo, COUNT(codPosto) AS numTotPosti, SUM(IF(codPrenotazione IS NOT NULL, 1, 0)) AS postiPrenotati
                  FROM posto, tipologia_posto
                  WHERE codEvento = ? 
                  AND posto.codTipologia = tipologia_posto.codTipologia
                  GROUP BY codTipologia, nomeTipologia, costo
                  ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $codEvento);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getObserveState($codEvento, $emailUtente) {
        $stmt = $this->db->prepare("SELECT COUNT(codEvento) FROM osserva WHERE codEvento = ? AND emailUtente = ?");
        $stmt->bind_param("is", $codEvento, $emailUtente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all()[0][0];
    }

    public function toggleObserveState($codEvento, $emailUtente) {
        if($this->getObserveState($codEvento, $emailUtente) == 0){
            $stmtAdd = $this->db->prepare("INSERT INTO osserva(codEvento, emailUtente) VALUES (".$codEvento.", '".$emailUtente."')");
            $stmtAdd->execute();
        } else {
            $stmtRm = $this->db->prepare("DELETE FROM osserva WHERE codEvento = ? AND emailUtente = ?");
            $stmtRm->bind_param("is", $codEvento, $emailUtente);
            $stmtRm->execute();
        }
    }

    public function getObservedEvents($emailUtente){
        $query = "SELECT E.codEvento, E.nomeEvento, E.dataEOra, E.descrizione,
                         L.codLuogo, L.nome AS nomeLuogo, L.indirizzo, L.urlMaps, L.capienzaMassima,
                         COUNT(P.codPrenotazione) as postiOccupati, (COUNT(P.codPrenotazione)/L.capienzaMassima * 100) as percPostiOccupati, COUNT(P.codPosto) AS maxPostiDisponibili
                  FROM evento E, luogo L, posto P, osserva O
                  WHERE E.codLuogo = L.codLuogo
                  AND p.codEvento = E.codEvento
                  AND E.codEvento = O.codEvento
                  AND O.emailUtente = ?
                  GROUP BY E.codEvento, E.nomeEvento, E.dataEOra, E.descrizione, 
                           L.codLuogo, L.nome, L.indirizzo, L.urlMaps, L.capienzaMassima";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $emailUtente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrganizedEvents($emailOrganizzatore) {
        $query = "SELECT E.codEvento, E.nomeEvento, E.dataEOra, E.descrizione,
                         L.codLuogo, L.nome AS nomeLuogo, L.indirizzo, L.urlMaps, L.capienzaMassima,
                         COUNT(P.codPrenotazione) as postiOccupati, (COUNT(P.codPrenotazione)/L.capienzaMassima * 100) as percPostiOccupati, COUNT(P.codPosto) AS maxPostiDisponibili
                  FROM evento E, luogo L, posto P
                  WHERE E.codLuogo = L.codLuogo
                  AND p.codEvento = E.codEvento
                  AND E.emailOrganizzatore = ?
                  GROUP BY E.codEvento, E.nomeEvento, E.dataEOra, E.descrizione, 
                           L.codLuogo, L.nome, L.indirizzo, L.urlMaps, L.capienzaMassima";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $emailOrganizzatore);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getModeratedEvents($emailModeratore) {
        $query = "SELECT E.codEvento, E.nomeEvento, E.dataEOra, E.descrizione,
                         L.codLuogo, L.nome AS nomeLuogo, L.indirizzo, L.urlMaps, L.capienzaMassima,
                         COUNT(P.codPrenotazione) as postiOccupati, (COUNT(P.codPrenotazione)/L.capienzaMassima * 100) as percPostiOccupati, COUNT(P.codPosto) AS maxPostiDisponibili
                  FROM evento E, luogo L, posto P, moderazione M
                  WHERE E.codLuogo = L.codLuogo
                  AND p.codEvento = E.codEvento
                  AND E.codEvento = M.codEvento
                  AND M.emailModeratore = ?
                  GROUP BY E.codEvento, E.nomeEvento, E.dataEOra, E.descrizione, 
                           L.codLuogo, L.nome, L.indirizzo, L.urlMaps, L.capienzaMassima";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $emailModeratore);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getBookedEvents($emailUtente) {
        $query = "SELECT DISTINCT E.codEvento, E.nomeEvento, E.dataEOra, E.descrizione,
                         L.codLuogo, L.nome AS nomeLuogo, L.indirizzo, L.urlMaps, L.capienzaMassima,
                         COUNT(P.codPrenotazione) as postiOccupati
                  FROM evento E, luogo L, posto P, prenotazione PR
                  WHERE E.codLuogo = L.codLuogo
                    AND P.codEvento = E.codEvento
                    AND P.codPrenotazione = PR.codPrenotazione
                    AND PR.emailUtente = ?
                  GROUP BY E.codEvento, E.nomeEvento, E.dataEOra, E.descrizione,
                           L.codLuogo, L.nome, L.indirizzo, L.urlMaps, L.capienzaMassima";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $emailUtente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserData($emailUtente) {
        $query = "SELECT nome, cognome, genere, dataNascita, dataIscrizione
                  FROM utente
                  WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $emailUtente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
    }

    public function getRemainingSeats($codEvento, $codTipologia, $costo) {
        $stmt = $this->db->prepare("SELECT COUNT(codPosto) 
         FROM posto
         WHERE codEvento = ?
         AND codTipologia = ?
         AND costo = ?
         AND codPrenotazione IS NULL");
        $stmt->bind_param("iii", $codEvento, $codTipologia, $costo);
        $stmt->execute();
        return $stmt->get_result()->fetch_all()[0][0];
    }

    public function getSeatType($codTipologia) {
        $stmt = $this->db->prepare("SELECT * FROM tipologia_posto WHERE codTipologia = ?");
        $stmt->bind_param("i", $codTipologia);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
    }

    public function insertBooking($dataEOra, $totale, $differenzaGiorni, $emailUtente) {
        $stmt = $this->db->prepare("INSERT INTO prenotazione(codPrenotazione, dataEOra, costoTotale, differenzaGiorni, emailUtente) VALUES (?, ?, ?, ?, ?)");
        $newBookingId = $this->getLastBookId() + 1;
        $stmt->bind_param("isiis", $newBookingId, $dataEOra, $totale, $differenzaGiorni, $emailUtente);
        $stmt->execute();
        return $newBookingId;
    }

    public function bookSeats($codPrenotazione, $codEvento, $codTipologia, $costo, $num) {
        $query = "UPDATE posto
                  SET codPrenotazione = ?
                  WHERE codTipologia = ?
                  AND costo = ?
                  AND codEvento = ?
                  AND codPrenotazione IS NULL
                  LIMIT ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiiii", $codPrenotazione, $codTipologia, $costo, $codEvento, $num);
        $stmt->execute();

        //Notifiche
        $stmtSoldOut = $this->db->prepare("SELECT COUNT(codPosto) FROM posto WHERE codEvento = ? AND codPrenotazione IS NULL");
        $stmtSoldOut->bind_param("i", $codEvento);
        $stmtSoldOut->execute();
        if ($stmtSoldOut->get_result()->fetch_all()[0][0] <= 0) {
            $stmtOrganizzatore = $this->db->prepare("SELECT nomeEvento, emailOrganizzatore FROM evento WHERE codEvento = ?");
            $stmtOrganizzatore->bind_param("i", $codEvento);
            $stmtOrganizzatore->execute();
            $evento = $stmtOrganizzatore->get_result()->fetch_all(MYSQLI_ASSOC)[0];
            $titoloNotifica = "Un tuo evento ha esaurito i biglietti!";
            $descrizione = "L'evento ".$evento["nomeEvento"]." ha esaurito i posti disponibili. Complimenti!";
            $dataEOra = date("Y-m-d H:i:s");
            $newCodNotifica = $this->getLastNotificationId($codEvento) + 1;
            $stmtNotifica = $this->db->prepare("INSERT INTO notifica(codEvento, codNotificaEvento, titolo, descrizione, letta, dataEOraInvio, differenzaGiorni, emailUtente) VALUES(?, ?, ?, ?, 0, ?, NULL, ?)");
            $stmtNotifica->bind_param("iissss", $codEvento, $newCodNotifica, $titoloNotifica, $descrizione, $dataEOra, $evento["emailOrganizzatore"]);
            $stmtNotifica->execute();
        }
        $stmtBiglietti = $this->db->prepare("SELECT COUNT(codPosto) AS totPosti, SUM(IF(codPrenotazione IS NULL, 1, 0)) AS postiLiberi FROM POSTO WHERE codEvento = ?");
        $stmtBiglietti->bind_param("i", $codEvento);
        $stmtBiglietti->execute();
        $resultBiglietti = $stmtBiglietti->get_result()->fetch_all(MYSQLI_ASSOC)[0];
        if($resultBiglietti["postiLiberi"]/$resultBiglietti["totPosti"] <= 0.25) {
            //Manda notifica a chi osserva l'evento
            $stmtOsservatori = $this->db->prepare("SELECT emailUtente FROM osserva WHERE codEvento = ?");
            $stmtOsservatori->bind_param("i", $codEvento);
            $stmtOsservatori->execute();
            $emailsOsservatori = $stmtOsservatori->get_result()->fetch_all(MYSQLI_ASSOC);
            if(count($emailsOsservatori) > 0) {
                $titoloNotifica = "Rimangono pochi biglietti per un evento che ti interessa!";
                $descrizione = "I biglietti per l'evento ".$evento["nomeEvento"]." iniziano a scarseggiare. Affrettati!";
                $dataEOra = date("Y-m-d H:i:s");
                $newCodNotifica = $this->getLastNotificationId($codEvento) + 1;
                foreach ($emailsOsservatori as $email) {
                    $stmtNotifica = $this->db->prepare("INSERT INTO notifica(codEvento, codNotificaEvento, titolo, descrizione, letta, dataEOraInvio, differenzaGiorni, emailUtente) VALUES(?, ?, ?, ?, 0, ?, NULL, ?)");
                    $stmtNotifica->bind_param("iissss", $codEvento, $newCodNotifica, $titoloNotifica, $descrizione, $dataEOra, $email);
                    $stmtNotifica->execute();
                    $newCodNotifica++;
                }
            }
        }
    }

    public function getNotificationFor($emailUtente) {
        $stmt = $this->db->prepare("SELECT codEvento, codNotificaEvento AS codNotifica, titolo, letta, dataEOraInvio FROM notifica WHERE emailUtente = ?");
        $stmt->bind_param("s", $emailUtente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getNotification($codEvento, $codNotifica) {
        $stmt = $this->db->prepare("SELECT * FROM notifica WHERE codEvento = ? AND codNotificaEvento = ?");
        $stmt->bind_param("ii", $codEvento, $codNotifica);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
    }

    public function readNotification($codEvento, $codNotifica) {
        $stmt = $this->db->prepare("UPDATE notifica SET letta = 1 WHERE codEvento = ? AND codNotificaEvento = ?");
        $stmt->bind_param("ii", $codEvento, $codNotifica);
        $stmt->execute();
    }

    public function readAllNots($emailUtente) {
        $stmt = $this->db->prepare("UPDATE notifica SET letta = 1 WHERE emailUtente = ?");
        $stmt->bind_param("s", $emailUtente);
        $stmt->execute();
    }

    public function getUnreadNotificationNum($emailUtente) {
        $stmt = $this->db->prepare("SELECT COUNT(codNotificaEvento) FROM notifica WHERE emailUtente = ? AND LETTA = 0");
        $stmt->bind_param("s", $emailUtente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all()[0][0];
    }

    public function getBookedSeats($codEvento, $emailUtente) {
        $query = "SELECT E.codEvento, E.nomeEvento, P.codPosto, T.nomeTipologia, PR.codPrenotazione
                  FROM evento E, posto P, prenotazione PR, tipologia_posto T
                  WHERE E.codEvento = ?
                  AND E.codEvento = P.codEvento
                  AND P.codTipologia = T.codTipologia
                  AND P.codPrenotazione = PR.codPrenotazione
                  AND PR.emailUtente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("is", $codEvento, $emailUtente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertEventReview($codEvento, $emailUtente, $voto, $testo, $anonima, $dataScrittura) {
        $query = "INSERT INTO recensione(codEvento, emailUtente, voto, testo, anonima, DataScrittura) VALUES (?,?,?,?,?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isisis", $codEvento, $emailUtente, $voto, $testo, $anonima, $dataScrittura);
        $stmt->execute();
    }

    public function hasUserWrittenReview($codEvento, $emailUtente) {
        $query = "SELECT COUNT(*) FROM recensione WHERE codEvento = ? AND emailUtente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("is", $codEvento, $emailUtente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all()[0][0];
    }

    public function getEventReviews($codEvento, $limit = -1) {
        $query = "SELECT codEvento, emailUtente, voto, testo, anonima, dataScrittura FROM recensione WHERE codEvento = ? ORDER BY dataScrittura";
        if ($limit != -1) {
            $query .= " LIMIT ?";
        }
        $stmt = $this->db->prepare($query);
        if ($limit != -1) {
            $stmt->bind_param("ii", $codEvento, $limit);
        } else {
            $stmt->bind_param("i", $codEvento);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAverageReviewVote($codEvento) {
        $stmt = $this->db->prepare("SELECT IFNULL(AVG(voto), 0) FROM recensione WHERE codEvento = ?");
        $stmt->bind_param("i", $codEvento);
        $stmt->execute();
        return $stmt->get_result()->fetch_all()[0][0];
    }

    public function getEventName($codEvento) {
        $stmt = $this->db->prepare("SELECT nomeEvento FROM evento WHERE codEvento = ?");
        $stmt->bind_param("i", $codEvento);
        $stmt->execute();
        return $stmt->get_result()->fetch_all()[0][0];
    }

    public function canUserReviewEvent($codEvento, $emailUtente) {
        $query = "SELECT COUNT(R.codEvento) FROM recensione R, prenotazione PR, posto P WHERE R.codEvento = ? AND R.emailUtente = ? AND R.codEvento = P.codEvento AND P.codPrenotazione = PR.codPrenotazione AND R.emailUtente = PR.emailUtente";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("is", $codEvento, $emailUtente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all()[0][0];
    }

    public function getUserReviews($emailUtente) {
        $query = "SELECT codEvento, voto, testo, anonima, dataScrittura FROM recensione WHERE emailUtente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $emailUtente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function getHashedPassword($email, $password) {
        $salt = hash('sha512', $email);
        $hashedPass = hash('sha512', $password.$salt);
        return $hashedPass;
    }

    private function checkUsername($email) {
        $query = "SELECT COUNT(email) FROM UTENTE WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all()[0][0];
    }

    private function getLastEventId(){
        $stmt = $this->db->prepare("SELECT IFNULL(MAX(codEvento), 0) FROM evento");
        $stmt->execute();
        return $stmt->get_result()->fetch_all()[0][0];
    }

    private function insertSeat($codEvento, $codPosto, $costo, $codTipologia) {
        $query = "INSERT INTO posto(codEvento, codPosto, costo, codTipologia, codPrenotazione) VALUES (".$codEvento.", ".$codPosto.", ".$costo.", ".$codTipologia.", NULL)";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    }

    private function getLastSeatId($codEvento) {
        $stmt = $this->db->prepare("SELECT IFNULL(MAX(codPosto), 0) FROM posto WHERE codEvento =".$codEvento);
        $stmt->execute();
        return $stmt->get_result()->fetch_all()[0][0];
    }

    private function indexOfSeatByTypeAndCostFromList(array $ticketList, $codTipo, $costo) {
        for ($i=0; $i < count($ticketList["type"]); $i++) { 
            if($ticketList["type"][$i] == $codTipo && $ticketList["cost"][$i] == $costo) {
                return $i;
            }
        }
        //If the pair (type, cost) is not found, the function returns -1
        return -1;
    }

    private function indexOfSeatByTypeAndCostFromDB(array $ticketList, $codTipo, $costo) {
        for ($i=0; $i < count($ticketList); $i++) { 
            if($ticketList[$i]["codTipologia"] == $codTipo && $ticketList[$i]["costo"] == $costo) {
                return $i;
            }
        }
        //If the pair (type, cost) is not found, the function returns -1
        return -1;
    }

    private function getInterestedUsers($codEvento) {
        $queryPrenotazione = "SELECT DISTINCT PR.emailUtente
                              FROM prenotazione PR, posto P
                              WHERE P.codPrenotazione = PR.codPrenotazione
                              AND P.codEvento = ".$codEvento;
        $queryOsserva = "SELECT emailUtente
                         FROM osserva 
                         WHERE codEvento = ".$codEvento;

        $queryCompleta = $queryPrenotazione." UNION DISTINCT ".$queryOsserva;
        $stmt = $this->db->prepare($queryCompleta);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function getLastNotificationId($codEvento) {
        $query = "SELECT IFNULL(MAX(codNotificaEvento), 0) FROM notifica WHERE codEvento = ".$codEvento;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all()[0][0];
    }

    private function getLastBookId(){
        $stmt = $this->db->prepare("SELECT IFNULL(MAX(codPrenotazione), 0) FROM prenotazione");
        $stmt->execute();
        return $stmt->get_result()->fetch_all()[0][0];
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projTecWeb";

$dbh = new DatabaseHelper($servername, $username, $password, $dbname);

?>