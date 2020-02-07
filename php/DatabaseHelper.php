<?php

class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname){
        $this->db = new mysqli($servername, $username, $password, $dbname);
        if ($this->db->connect_error) {
            die("Connection failed: " . $db->connect_error);
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

        $stmt = $this->db->prepare($query);
        if ($limit != -1) {
            $stmt->bind_param("ii", $NSFC, $limit);
        } else {
            $stmt->bind_param("i", $NSFC);
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

    /*
    public function getPosts($n=-1){
        $query = "SELECT idarticolo, titoloarticolo, imgarticolo, anteprimaarticolo, dataarticolo, nome FROM articolo, autore WHERE autore=idautore ORDER BY dataarticolo DESC";
        if($n > 0){
            $query .= " LIMIT ?";
        }
        $stmt = $this->db->prepare($query);
        if($n > 0){
            $stmt->bind_param('i',$n);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertArticle($titoloarticolo, $testoarticolo, $anteprimaarticolo, $dataarticolo, $imgarticolo, $autore){
        $query = "INSERT INTO articolo (titoloarticolo, testoarticolo, anteprimaarticolo, dataarticolo, imgarticolo, autore) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssssi',$titoloarticolo, $testoarticolo, $anteprimaarticolo, $dataarticolo, $imgarticolo, $autore);
        $stmt->execute();
        
        return $stmt->insert_id;
    }

    public function updateArticleOfAuthor($idarticolo, $titoloarticolo, $testoarticolo, $anteprimaarticolo, $imgarticolo, $autore){
        $query = "UPDATE articolo SET titoloarticolo = ?, testoarticolo = ?, anteprimaarticolo = ?, imgarticolo = ? WHERE idarticolo = ? AND autore = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssii',$titoloarticolo, $testoarticolo, $anteprimaarticolo, $imgarticolo, $idarticolo, $autore);
        
        return $stmt->execute();
    }

    public function deleteArticleOfAuthor($idarticolo, $autore){
        $query = "DELETE FROM articolo WHERE idarticolo = ? AND autore = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii',$idarticolo, $autore);
        $stmt->execute();
        var_dump($stmt->error);
        return true;
    }
    */
    
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

}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projTecWeb";

$dbh = new DatabaseHelper($servername, $username, $password, $dbname);

?>