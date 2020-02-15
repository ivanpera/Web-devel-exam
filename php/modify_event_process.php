<?php
    require_once("../bootstrap.php");
    if (!isset($_POST) || !isset($_SESSION["sessUser"])) {
        safeHeader("Location: ../index.php");
    }
    define("ONE_MiB", 1048576);

    $imageName = "";

    if(!empty($_FILES["imageName"]["name"])) {
        $imageArray = uploadImage();
        $imageName = $imageArray["imageName"];
        if(!empty($_POST["currentImageName"]) && !$imageArray["uploadError"]) {
            unlink("../img/".$_SESSION["sessUser"]["email"]."/".$_POST["currentImageName"]);
        }
    }
    $codEvento = $_POST["codEvento"];
    $eventName = $_POST["nomeEvento"];
    $dataEOra = (new DateTime($_POST["data"]." ".$_POST["ora"]))->format("Y-m-d H:i:s");
    $codLuogo = intval($_POST["luogo"]);
    $descrizione = $_POST["description"];
    $NSFC = isset($_POST["NSFC"]) ? 1 : 0;
    $categories = empty($_POST["categories"]) ? array() : $_POST["categories"];
    $emailModeratori = empty($_POST["mod_mail"]) ? array() : $_POST["mod_mail"];

    $biglietti["type"] = $_POST["ticket_type"];
    $biglietti["cost"] = $_POST["ticket_cost"];
    foreach ($biglietti["cost"] as &$costo) {$costo = intval($costo) * 100;}
    $biglietti["num"] = $_POST["num_tickets"];

    //Mette assieme le categorie con i costi uguali
    for ($i=0; $i < count($biglietti["type"]); $i++) { 
        $prevOccurrence = array_search($biglietti["type"][$i], $biglietti["type"]);
        if ($prevOccurrence !== false && $prevOccurrence < $i && $biglietti["cost"][$i] == $biglietti["cost"][$prevOccurrence]) {
            $biglietti["num"][$prevOccurrence] += $biglietti["num"][$i];
            unset($biglietti["type"][$i], $biglietti["cost"][$i], $biglietti["num"][$i]);
            $i--;
        }
    }

    $dbh->updateEvent($codEvento, $eventName, $dataEOra, $NSFC, $descrizione, $imageName, $codLuogo, $categories, $biglietti, $emailModeratori);
    safeHeader("Location: ../event_details.php?codEvento=".$codEvento.($imageArray["uploadError"] ? "&uploadError=".$imageArray["uploadError"] : ""));
    
?>