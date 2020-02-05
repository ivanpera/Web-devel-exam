<?php
    
    require_once('../bootstrap.php');
    $result = $dbh->checkLogin($_POST["email"], $_POST["password"]);
    if (!empty($result)) {
        $_SESSION["sessUser"]["email"] = $_POST["email"];
        $_SESSION["sessUser"]["NSFC"] = date_diff(new DateTime($result[0]["dataNascita"]), new DateTime(date("Y-m-d")), true)->y > 18 ? "1" : "0";
        $redirectPage = "index.php";
        if (isset($_SESSION["previousPage"])) {
            $redirectPage = $_SESSION["previousPage"];
            unset($_SESSION["previousPage"]);
        }
        session_write_close();
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