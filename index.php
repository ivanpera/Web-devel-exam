<?php
    require_once('bootstrap.php');
    $templateParams["name"] = "home.php";
    $templateParams["title"] = "Home";
    $templateParams["css"] = array("base.css", "home.css");
    require(TEMPLATE_DIR."base.php");
?>
