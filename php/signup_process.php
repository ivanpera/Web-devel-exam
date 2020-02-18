<?php
    require_once('../bootstrap.php');
    if($_POST["password"] != $_POST["password_confirmation"]) {
        safeHeader("Location: ../signup.php?registrationFailed=3");
    }
    if(empty(trim($_POST["email"])) || 
       empty(trim($_POST["password"])) || 
       empty(trim($_POST["name"])) ||
       empty(trim($_POST["surname"])) || 
       empty(trim($_POST["birthdate"])) || 
       empty(trim($_POST["gender"]))) {
        safeHeader("Location: ../signup.php?registrationFailed=1");
    }
    if($errorCode =$dbh->registerNewUser($_POST["email"], $_POST["password"], $_POST["name"], $_POST["surname"], $_POST["birthdate"], $_POST["gender"])) {
        safeHeader("Location: ../signup.php?registrationFailed=".$errorCode);
    } else {
        $_SESSION["sessUser"]["email"] = $_POST["email"];
        $_SESSION["sessUser"]["NSFC"] = date_diff(new DateTime($_POST["birthdate"]), new DateTime(date("Y-m-d")), true)->y > 18 ? "1" : "0";
        safeHeader("Location: ../index.php");
    }

?>