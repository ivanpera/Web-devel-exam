<?php
    require_once('../bootstrap.php');
    if(isset($_SESSION["sessUser"]["email"])) {
        unset($_SESSION["sessUser"]);
    }
    safeHeader("Location: ../index.php");
?>