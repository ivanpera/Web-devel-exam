<?php
    require_once('bootstrap.php');
    $templateParams["name"] = "create_event.php";
    $templateParams["title"] = "Crea evento";
    $templateParams["css"] = array("base.css", "create_event.css");
    $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js");
    require(TEMPLATE_DIR."base.php");
?>