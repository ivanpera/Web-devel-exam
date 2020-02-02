<?php
    require_once('bootstrap.php');
    $templateParams["name"] = "create_event.php";
    $templateParams["title"] = "Crea evento";
    $templateParams["css"] = array("base.css", "create_event.css");
    require(TEMPLATE_DIR."base.php");
?>