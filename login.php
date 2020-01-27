<?php
    require_once('bootstrap.php');
    $templateParams["name"] = "login.php";
    $templateParams["title"] = "Login";
    $loginFailed = false;
    require(TEMPLATE_DIR."base.php");
?>