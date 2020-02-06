<?php
    require_once('bootstrap.php');
    if($dbh->registerNewUser($_POST["email"], $_POST["password"], $_POST["name"], $_POST["surname"], $_POST["birthdate"], $_POST["gender"], 0)) {
        header("Location: ../signup.php?registrationFailed=1");
    } else {
        echo "Registrazione corretta";
    }

?>