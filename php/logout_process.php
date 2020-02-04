<?php
    require_once('../bootstrap.php');
    if(isset($_SESSION["sessUser"])) {
        unset($_SESSION["sessUser"]);
    }
    session_write_close();
    header("Location: ../index.php");
    die();
?>