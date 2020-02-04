<?php
    
    require_once('../bootstrap.php');
    $result = $dbh->checkLogin($_POST["email"], $_POST["password"]);
    if (!empty($result)) {
        $_SESSION["sessUser"] = $_POST["email"];
        session_write_close();
        header("Location: ../index.php");
        die();
    } else {
        if(isset($_SESSION["sessUser"])) {
            unset($_SESSION["sessUser"]);
        }
        session_write_close();
        header("Location: ../login.php?loginFailed=1");
        die();
    }
?>