<?php
    require_once('bootstrap.php');
    $templateParams["name"] = "events.php";
    $templateParams["title"] = "Visualizza eventi";
    $templateParams["css"] = array("base.css", "events.css");

    $templateParams["events"] = $dbh->getPopularEvents();

    require(TEMPLATE_DIR."base.php");
?>