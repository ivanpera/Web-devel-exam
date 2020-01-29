<?php

    require_once('DatabaseHelper.php');
    $result = $dbh->checkLogin($_POST["email"], $_POST["password"]);
    if (!empty($result)) {
        //Add a variable to $_SESSION
    } else {
        header("Location: ../login.php?loginFailed=1");
    }
?>