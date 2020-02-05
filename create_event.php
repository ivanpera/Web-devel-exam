<?php
    require_once('bootstrap.php');
    //This check is present to prevent accessing the page without being logged in and by changing the browser address
    if(!isset($_SESSION["sessUser"]["email"])) {
        $_SESSION["previousPage"] = "create_event.php";
        session_write_close();
        header("Location: login.php");
        die();
    }
    $templateParams["name"] = "create_event.php";
    $templateParams["title"] = "Crea evento";
    $templateParams["css"] = array("base.css", "create_event.css");
    $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js", "js/create_event.js");
    $templateParams["categories"] = $dbh->getCategories();
    $templateParams["tipoPosti"] = $dbh->getSeatTypes();
    $templateParams["luoghi"] = $dbh->getPlaces();
    require(TEMPLATE_DIR."base.php");
?>