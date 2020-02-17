<?php
    session_start();
    define("TEMPLATE_DIR", "php/template/");
    define("CSS_DIR", "css/");
    define("IMG_DIR", "img/");
    require_once("php/DatabaseHelper.php");

    function safeHeader($headerString) {
        session_write_close();
        header($headerString);
        die();
    }

    /*  uploadError = 0 -> all correct
        uploadError = 1 -> fake image
        uploadError = 2 -> file already exists
        uploadError = 3 -> file too large
        uploadError = 4 -> file extension not supported
        uploadError = 5 -> file move failed
    */
    function uploadImage() {
        $imageName = "";
        $uploadError = 0;
        //Se la stringa è vuota, allora non è stato caricato nessun file
        if ($_FILES["imageName"]["name"] != "") {
            $target_dir = "../img/".$_SESSION["sessUser"]["email"]."/";
            if(!file_exists($target_dir)) {
                mkdir($target_dir);
            }
            $imageName = basename($_FILES["imageName"]["name"]);
            $imageName = str_replace(" ", "_", $imageName);
            $target_file = $target_dir.$imageName;
            $check = getimagesize($_FILES["imageName"]["tmp_name"]);
            $imageFileType =  strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            $check !== false ? $uploadError = 0 : $uploadError = 1;

            if(!$uploadError) {
                file_exists($target_file) ? $uploadError = 2 : $uploadError = 0;
            }
            if(!$uploadError) {
                $_FILES["imageName"]["size"] > ONE_MiB ? $uploadError = 3 : $uploadError = 0;
            }

            if(!$uploadError) {
                ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") ? $uploadError = 4 : $uploadError = 0;
            }

            if(!$uploadError) {
                move_uploaded_file($_FILES["imageName"]["tmp_name"], $target_file) ? $uploadError = 0 : $uploadError = 5;
            }
            if($uploadError) {
                $imageName = "";
            }
        }
        return array("imageName" => $imageName, "uploadError" => $uploadError);
    }

    if(isset($_SESSION["sessUser"])) {
        $notificationNumber = $dbh->getUnreadNotificationNum($_SESSION["sessUser"]["email"]);
    }
    if (isset($notificationNumber) && $notificationNumber == 0) {
        unset($notificationNumber);
    }
?>