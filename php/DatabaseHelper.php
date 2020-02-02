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
        $query = "SELECT email, userPassword FROM utente WHERE email = ? AND userPassword =  ?";
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

    /* TO BE IMPROVED */
    public function getMostPopularEvents( $NSFC = 0, $limit = -1) {
        $query = "SELECT E.codEvento, E.nomeEvento, E.dataEOra, E.NSFC, E.descrizione, E.nomeImmagine, E.codLuogo, E.emailOrganizzatore,
                         L.nome, L.indirizzo, L.urlMaps, L.capienzaMassima,
                         CE.nomeCategoria,
                         (COUNT(P.codPosto)/L.capienzaMassima * 100) as percPostiOccupati
                  FROM EVENTO E, LUOGO L, CATEGORIA_EVENTO CE, POSTO P, EVENTO_HA_CATEGORIA EHC
                  WHERE E.codEvento = EHC.codEvento AND EHC.codCategoria = CE.codCategoria -- Join tra evento e categoria
                    AND E.codLuogo = L.codLuogo -- Join tra evento e luogo
                    AND P.codEvento = E.codEvento -- Join tra evento e posto
                    AND P.codPrenotazione IS NOT NULL
                    AND E.NSFC = ?
                  -- GROUP BY E.codEvento, E.nomeEvento, E.dataEOra, E.NSFC, E.descrizione, E.nomeImmagine, E.codLuogo, E.emailOrganizzatore,
                  --       L.nome, L.indirizzo, L.urlMaps, L.capienzaMassima,
                  --       CE.nomeCategoria
                  HAVING percPostiOccupati >= 75 AND percPostiOccupati < 100
                  ORDER BY percPostiOccupati DESC
                  ";
        if ($limit != -1) {
            $query = $query." LIMIT ?";
        }

        $stmt = $this->db->prepare($query);
        if (limit != -1 ) {
            $stmt->bind_param("ii", $NSFC, $limit);
        } else {
            $stmt->bind_param("i", $NSFC);
        }
        $stmt_>execute();
        return $stmt->get_result()->fetch_all(MYSQL_ASSOC);
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

}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projTecWeb";

$dbh = new DatabaseHelper($servername, $username, $password, $dbname);

?>