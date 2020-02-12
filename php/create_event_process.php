<?php
    require_once("../bootstrap.php");
    if (!isset($_POST) || !isset($_SESSION["sessUser"])) {
        safeHeader("Location: ../index.php");
    }
    define("ONE_MiB", 1048576);

    $imageName = uploadImage();
    $newEventName = $_POST["nomeEvento"];
    $dataEOra = (new DateTime($_POST["data"]." ".$_POST["ora"]))->format("Y-m-d H:i:s");
    $codLuogo = intval($_POST["luogo"]);
    $descrizione = $_POST["description"];
    $NSFC = isset($_POST["NSFC"]) ? 1 : 0;
    $categories = $_POST["categories"];
    $biglietti["type"] = $_POST["ticket_type"];
    $biglietti["cost"] = $_POST["ticket_cost"];
    foreach ($biglietti["cost"] as &$costo) {$costo = intval($costo) * 100;}
    $biglietti["num"] = $_POST["num_tickets"];
    $emailOrganizzatore = $_SESSION["sessUser"]["email"];
    $emailModeratori = $_POST["mod_mail"];
    $newCodEvento = $dbh->insertEvent($newEventName, $dataEOra, $NSFC, $descrizione, $imageName, $codLuogo, $emailOrganizzatore, $categories, $biglietti, $emailModeratori);

    safeHeader("Location: ../event_details.php?codEvento=".$newCodEvento);
    
?>