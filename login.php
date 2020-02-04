<?php
    require_once('bootstrap.php');
    $templateParams["name"] = "login.php";
    $templateParams["title"] = "Login";
    $templateParams["css"] = array("base.css", "login.css");
    $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js");
    $loginFailed = false;
    require(TEMPLATE_DIR."base.php");
?>