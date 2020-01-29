<?php
    require_once('DatabaseHelper.php');
    if($dbh->registerNewUser($_POST["email"], $_POST["password"], $_POST["name"], $_POST["surname"], $_POST["birthdate"], $_POST["gender"], 0)) {
        echo "L'utente esiste già.";
    } else {
        echo "Registrazione corretta";
    }

?>