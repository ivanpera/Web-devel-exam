<?php
    require_once('bootstrap.php');
    $templateParams["name"] = "login.php";
    $templateParams["title"] = "Login";
    $templateParams["css"] = array("base.css", "login.css");
    $loginFailed = false;
    require(TEMPLATE_DIR."base.php");
?>