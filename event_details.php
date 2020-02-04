<?php
    require_once('bootstrap.php');
    $templateParams["name"] = "event_details.php";
    $templateParams["title"] = "Dettagli evento";
    $templateParams["css"] = array("base.css");
    $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js");
    require(TEMPLATE_DIR."base.php");
?>