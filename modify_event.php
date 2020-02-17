<?php
    require_once('bootstrap.php');
    //This check is present to prevent accessing the page without being logged in and by changing the browser address
    if(!isset($_SESSION["sessUser"]["email"])) {
        $_SESSION["previousPage"] = "modify_event.php";
        safeHeader("Location: login.php");
    }
    if (!isset($_GET["codEvento"])) {
        safeHeader("Location: index.php");
    }

    $templateParams["evento"] = $dbh->getEvent($_GET["codEvento"])[0];
    if(!empty($templateParams["evento"])) {
        if($templateParams["evento"]["emailOrganizzatore"] != $_SESSION["sessUser"]["email"]) {
            safeHeader("Location: event_details.php?codEvento=".$_GET["codEvento"]);
        }
    } else {
        safeHeader("Location: index.php");
    }

    $templateParams["name"] = "modify_event.php";
    $templateParams["title"] = "Modifica evento";
    $templateParams["css"] = array("base.css", "create_event.css");
    $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js", "js/create_event.js");
    $templateParams["categories"] = $dbh->getCategories();
    $templateParams["tipoPosti"] = $dbh->getSeatTypes();
    $templateParams["luoghi"] = $dbh->getPlaces();
    $templateParams["biglietti"] = $dbh->getSeatNumByTypeAndCost($_GET["codEvento"]);
    $templateParams["moderatori"] = $dbh->getEventModerators($_GET["codEvento"]);
    require(TEMPLATE_DIR."base.php");
?>