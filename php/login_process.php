<?php
    
    require_once('../bootstrap.php');
    $result = $dbh->checkLogin($_POST["email"], $_POST["password"]);
    if (!empty($result)) {
        $_SESSION["sessUser"]["email"] = $_POST["email"];
        session_write_close();
        $redirectPage = "index.php";
        if (isset($_SESSION["previousPage"])) {
            $redirectPage = $_SESSION["previousPage"];
        }
        header("Location: ../".$redirectPage);
        die();
    } else {
        if(isset($_SESSION["sessUser"]["email"])) {
            unset($_SESSION["sessUser"]);
        }
        session_write_close();
        header("Location: ../login.php?loginFailed=1");
        die();
    }
?>