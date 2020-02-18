<?php
    require_once('bootstrap.php');
    $templateParams["name"] = "signup.php";
    $templateParams["title"] = "Registrazione";
    $templateParams["css"] = array("base.css");
    $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js");
    $errorMessages = array("",
                           "Si è verificato un errore interno. Riprovare più tardi o contattare l'assistenza.",
                           "L'email è già in uso.",
                           "La password e la conferma non combaciano."
                          );
    require(TEMPLATE_DIR."base.php");
?>