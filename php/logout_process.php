<?php
    require_once('../bootstrap.php');
    if(isset($_SESSION["sessUser"]["email"])) {
        unset($_SESSION["sessUser"]);
    }
    session_write_close();
    header("Location: ../index.php");
    die();
?>