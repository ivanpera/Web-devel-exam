<?php

    require_once('DatabaseHelper.php');
    /*
    if ($_GET["email"] == "a") {
        header("location: login.php?loginFailed=true");
    } else {
        header("location: /index.php");
    }*/

    //Controlla che esista un utente con lamail specificata
    //Crea sale e hasha password passata, controlla che corrisponda
    //Il sale per la password è hash('sha512', email);
    //La password hashata è hash('sha512', password.salt);
?>