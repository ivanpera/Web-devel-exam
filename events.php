<?php
    require_once('bootstrap.php');
    $templateParams["name"] = "events.php";
    $templateParams["title"] = "Visualizza eventi";
    $templateParams["css"] = array("base.css", "events.css");
    $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/events.js", "js/base.js");
    $templateParams["events"] = $dbh->getPopularEvents();

    require(TEMPLATE_DIR."base.php");
?>