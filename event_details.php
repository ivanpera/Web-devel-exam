<?php
    require_once('bootstrap.php');
    if (!isset($_GET["codEvento"])){
        header("Location: index.php");
        die();
    }
    $templateParams["evento"] = $dbh->getEvent($_GET["codEvento"])[0];
    if($templateParams["evento"] != false) {
        if ($templateParams["evento"]["NSFC"] == "1") {
            if(!isset($_SESSION["sessUser"])) {
                $_SESSION["previousPage"] = "event_details.php?codEvento=".$_GET["codEvento"];
                session_write_close();
                header("Location: login.php");
                die();
            } else if ($_SESSION["sessUser"]["NSFC"] == 0) {
                //Volendo si manda in una pagina esplicativa
                header("Location: index.php");
                die();
            }
        }
    } else {
        header("Location: index.php");
        die();
    }
    $templateParams["name"] = "event_details.php";
    $templateParams["title"] = "Dettagli evento";
    $templateParams["css"] = array("base.css");
    $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js");
    require(TEMPLATE_DIR."base.php");
?>