<?php
    require_once("../bootstrap.php");
    if (!isset($_POST) || !isset($_SESSION["sessUser"])) {
        header("Location: ../index.php");
        die();
    }
    //var_dump($_POST);
?>