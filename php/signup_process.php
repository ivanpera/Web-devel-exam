<?php
    require_once('bootstrap.php');
    if($dbh->registerNewUser($_POST["email"], $_POST["password"], $_POST["name"], $_POST["surname"], $_POST["birthdate"], $_POST["gender"], 0)) {
        safeHeader("Location: ../signup.php?registrationFailed=1");
    } else {
        //Volendo si può già settare la sessione con le variabili dell'utente e loggarlo automaticamente, poi ridirezionarlo alla home
        safeHeader("Location: ../login.php");
    }

?>