<?php
    require_once('bootstrap.php');
    if (!isset($_GET["codEvento"])){
        safeHeader("Location: index.php");
    }

    $templateParams["evento"] = $dbh->getEvent($_GET["codEvento"])[0];
    if(!empty($templateParams["evento"])) {
        if ($templateParams["evento"]["NSFC"] == "1") {
            if(!isset($_SESSION["sessUser"])) {
                $_SESSION["previousPage"] = "event_details.php?codEvento=".$_GET["codEvento"];
                safeHeader("Location: login.php");
            } else if ($_SESSION["sessUser"]["NSFC"] == 0) {
                //Volendo si manda in una pagina esplicativa
                safeHeader("Location: index.php");
            }
        }
    } else {
        safeHeader("Location: index.php");
    }
    $templateParams["moderatori"] = $dbh->getEventModerators($_GET["codEvento"]);
    $templateParams["name"] = "event_details.php";
    $templateParams["title"] = "Dettagli evento";
    $templateParams["css"] = array("base.css", "event_details.css");
    $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js", "js/event_details.js", "js/cart_handler.js");

    $templateParams["tickets"] = $dbh->getSeatNumByTypeAndCost($_GET["codEvento"]);
    
    $templateParams["recensioni"] = $dbh->getEventReviews($_GET["codEvento"], 3);
    $templateParams["votoMedioRecensioni"] = $dbh->getAverageReviewVote($_GET["codEvento"]);
    $templateParams["utenteHaRecensito"] = (isset($_SESSION["sessUser"]) ? $dbh->hasUserWrittenReview($_GET["codEvento"], $_SESSION["sessUser"]["email"]) : 1);
    $templateParams["userCanReview"] = (isset($_SESSION["sessUser"]) ? !$dbh->canUserReviewEvent($_GET["codEvento"], $_SESSION["sessUser"]["email"]) : 0) && (isset($_SESSION["sessUser"]) ? $_SESSION["sessUser"]["email"] != $templateParams["evento"]["emailOrganizzatore"] : 0);
    require(TEMPLATE_DIR."base.php");
?>