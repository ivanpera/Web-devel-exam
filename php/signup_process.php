<?php
    require_once('../bootstrap.php');
    if($dbh->registerNewUser($_POST["email"], $_POST["password"], $_POST["name"], $_POST["surname"], $_POST["birthdate"], $_POST["gender"], 0)) {
        safeHeader("Location: ../signup.php?registrationFailed=1");
    } else {
        $_SESSION["sessUser"]["email"] = $_POST["email"];
        $_SESSION["sessUser"]["NSFC"] = date_diff(new DateTime($_POST["birthdate"]), new DateTime(date("Y-m-d")), true)->y > 18 ? "1" : "0";
        safeHeader("Location: ../index.php");
    }

?>