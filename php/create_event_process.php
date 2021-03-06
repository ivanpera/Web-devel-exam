<?php
    require_once("../bootstrap.php");
    if (!isset($_POST) || !isset($_SESSION["sessUser"])) {
        safeHeader("Location: ../index.php");
    }
    define("ONE_MiB", 1048576);

    $imageArray = uploadImage();
    $imageName = $imageArray["imageName"];
    $newEventName = $_POST["nomeEvento"];
    $dataEOra = (new DateTime($_POST["data"]." ".$_POST["ora"]))->format("Y-m-d H:i:s");
    $codLuogo = intval($_POST["luogo"]);
    $descrizione = $_POST["description"];
    $NSFC = isset($_POST["NSFC"]) ? 1 : 0;
    $categories = empty($_POST["categories"]) ? array() : $_POST["categories"];
    $biglietti["type"] = $_POST["ticket_type"];
    $biglietti["cost"] = $_POST["ticket_cost"];
    foreach ($biglietti["cost"] as &$costo) {$costo = intval($costo) * 100;}
    $biglietti["num"] = $_POST["num_tickets"];
    $emailOrganizzatore = $_SESSION["sessUser"]["email"];
    $emailModeratori = empty($_POST["mod_mail"]) ? array() : $_POST["mod_mail"];
    if(empty(trim($newEventName)) || empty(trim($dataEOra)) || empty(trim($codLuogo))) {
        safeHeader("Location: ../create_event.php?error=1");
    }
    if ((empty($biglietti["type"]) || empty($biglietti["cost"]) || empty($biglietti["num"])) ||
         (count($biglietti["type"]) != count($biglietti["cost"]) || count($biglietti["num"]) != count($biglietti["cost"]))) {
            safeHeader("Location: ../create_event.php?error=1");
    }
    for ($i = 0; $i < count($biglietti["type"]); $i++) {
        if((empty(trim($biglietti["type"][$i])) || empty(trim($biglietti["cost"][$i])) || empty(trim($biglietti["num"][$i])))) {
            safeHeader("Location: ../create_event.php?error=1");
        }
    }
    $newCodEvento = $dbh->insertEvent($newEventName, $dataEOra, $NSFC, $descrizione, $imageName, $codLuogo, $emailOrganizzatore, $categories, $biglietti, $emailModeratori);
    safeHeader("Location: ../event_details.php?codEvento=".$newCodEvento.($imageArray["uploadError"] ? "&uploadError=".$imageArray["uploadError"] : ""));
    
?>