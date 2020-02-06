<?php
    require_once("../bootstrap.php");
    if (!isset($_POST) || !isset($_SESSION["sessUser"])) {
        safeHeader("Location: ../index.php");
    }
    define("ONE_MiB", 1048576);

    /*  uploadError = 0 -> all correct
        uploadError = 1 -> fake image
        uploadError = 2 -> file already exists
        uploadError = 3 -> file too large
        uploadError = 4 -> file extension not supported
        uploadError = 5 -> file move failed
    */
    function checkUploadError($uploadError) {
        if ($uploadError != 0) {
            safeHeader("Location: ../create_event.php?uploadError=".$uploadError);
        }
    }

    $imageName = "";
    //Se la stringa è vuota, allora non è stato caricato nessun file
    if ($_FILES["imageName"]["name"] != "") {
        $target_dir = "../img/".$_SESSION["sessUser"]["email"]."/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir);
        }
        $imageName = basename($_FILES["imageName"]["name"]);
        $target_file = $target_dir.$imageName;
        $check = getimagesize($_FILES["imageName"]["tmp_name"]);
        $imageFileType =  strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $uploadError = 0;

        $check !== false ? $uploadError = 0 : $uploadError = 1;
        checkUploadError($uploadError);

        file_exists($target_file) ? $uploadError = 2 : $uploadError = 0;
        checkUploadError($uploadError);

        $_FILES["imageName"]["size"] > ONE_MiB ? $uploadError = 3 : $uploadError = 0;
        checkUploadError($uploadError);

        ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") ? $uploadError = 4 : $uploadError = 0;
        checkUploadError($uploadError);

        move_uploaded_file($_FILES["imageName"]["tmp_name"], $target_file) ? $uploadError = 0 : $uploadError = 5;
        checkUploadError($uploadError);
    }

    $newEventName = $_POST["nomeEvento"];
    $dataEOra = (new DateTime($_POST["data"]." ".$_POST["ora"]))->format("Y-m-d H:i:s");
    $codLuogo = intval($_POST["luogo"]);
    $descrizione = $_POST["description"];
    $NSFC = isset($_POST["NSFC"]) ? 1 : 0;
    $categories = $_POST["categories"];
    $biglietti["type"] = $_POST["ticket_type"];
    $biglietti["cost"] = $_POST["ticket_cost"];
    $biglietti["num"] = $_POST["num_tickets"];
    $emailOrganizzatore = $_SESSION["sessUser"]["email"];
    $emailModeratori = $_POST["mod_mail"];
    $newCodEvento = $dbh->insertEvent($newEventName, $dataEOra, $NSFC, $descrizione, $imageName, $codLuogo, $emailOrganizzatore, $categories, $biglietti, $emailModeratori);

    safeHeader("Location: ../event_details.php?codEvento=".$newCodEvento);
    
?>