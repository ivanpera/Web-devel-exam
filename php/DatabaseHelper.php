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

    public function getPopularEvents( $NSFC = 0, $limit = -1) {
        $query = "SELECT *
                  FROM (SELECT E.codEvento, E.nomeEvento, E.dataEOra, E.NSFC, E.descrizione, E.nomeImmagine, E.emailOrganizzatore,
                               L.codLuogo, L.nome AS nomeLuogo, L.indirizzo, L.urlMaps, L.capienzaMassima,
                               COUNT(P.codPrenotazione) as postiOccupati, (COUNT(P.codPrenotazione)/L.capienzaMassima * 100) as percPostiOccupati
                        FROM evento E, luogo L, posto P
                        WHERE E.codLuogo = L.codLuogo
                        AND p.codEvento = E.codEvento
                        AND E.NSFC <= ?
                        AND E.dataEOra > ?
                        GROUP BY E.codEvento, E.nomeEvento, E.dataEOra, E.NSFC, E.descrizione, E.nomeImmagine, E.emailOrganizzatore,
                                 L.codLuogo, L.nome, L.indirizzo, L.urlMaps, L.capienzaMassima
                        HAVING percPostiOccupati < 100
                       ) AS tabEventi,
              
                       (SELECT EHC.codEvento, GROUP_CONCAT(ce.nomeCategoria SEPARATOR ', ') AS categorie
                        FROM evento_ha_categoria EHC, categoria_evento CE
                        WHERE EHC.codCategoria = ce.codCategoria
                        GROUP BY EHC.codEvento) AS tabCategorie
                  WHERE tabEventi.codEvento = tabCategorie.codEvento
                  ";
        if ($limit != -1) {
            $query = $query." LIMIT ?";
        }
        $currentDate = date("Y-m-d H:i:s");
        $stmt = $this->db->prepare($query);
        if ($limit != -1) {
            $stmt->bind_param("isi", $NSFC, $currentDate, $limit);
        } else {
            $stmt->bind_param("is", $NSFC, $currentDate);
        }
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
                       ) AS tabEventi,
              
                       (SELECT EHC.codEvento, GROUP_CONCAT(ce.nomeCategoria SEPARATOR ', ') AS categorie
                        FROM evento_ha_categoria EHC, categoria_evento CE
                        WHERE EHC.codCategoria = ce.codCategoria
                          AND EHC.codEvento = ?
                        GROUP BY EHC.codEvento) AS tabCategorie
                  WHERE tabEventi.codEvento = tabCategorie.codEvento
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
                               COUNT(P.codPrenotazione) as postiOccupati, (COUNT(P.codPrenotazione)/L.capienzaMassima * 100) as percPostiOccupati
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
                          FROM (".$queryEvento.") AS tabEventi,
                               (".$queryCategorie.") AS tabCategorie
                          WHERE tabEventi.codEvento = tabCategorie.codEvento";

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
        if ($oldEventName != $nomeEvento) {
            $descrizioneNotifica .= "L'evento '".$oldEventName."' è stato rinominato in '".$nomeEvento."' e ha subito variazioni. Controlla i dettagli dell'evento!";
        } else {
            $descrizioneNotifica .= "L'evento '".$oldEventName."' ha subito variazioni. Controlla i dettagli dell'evento!";
        }
        $interestedUsers = $this->getInterestedUsers($codEvento);
        $codNotifica = $this->getLastNotificationId($codEvento) + 1;
        $queryAddNot = "INSERT INTO notifica(codEvento, codNotificaEvento, descrizione, letta, dataEOraInvio, differenzaGiorni, emailUtente) VALUES (".$codEvento.", ?, '".$descrizioneNotifica."', 0, '".date("Y-m-d H:i:s")."', NULL, ?)";
        $stmtNotifiche = $this->db->prepare($queryAddNot);
        foreach ($interestedUsers as $user) {
            $stmtNotifiche->bind_param("is", $codNotifica, $user["emailUtente"]);
            $stmtNotifiche->execute();
            $codNotifica++;
        }

    }

    public function getSeatNumByTypeAndCost($codEvento) {
        $query = "SELECT posto.codTipologia, nomeTipologia, costo, COUNT(codPosto) AS numTotPosti, SUM(IF(codPrenotazione IS NOT NULL, 1, 0)) AS postiPrenotati
                  FROM posto, tipologia_posto
                  WHERE codEvento = ? 
                  AND posto.codTipologia = tipologia_posto.codTipologia
                  GROUP BY codTipologia, costo
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