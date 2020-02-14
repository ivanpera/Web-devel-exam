<?php
  require_once("bootstrap.php");
  if(!isset($_SESSION["sessUser"])) {
    $_SESSION["previousPage"] = "notification_details.php?codEvento=".$_GET["codEvento"]."&codNotifica=".$_GET["codNotifica"];
    safeHeader("Location: login.php");
  }

  $templateParams["notifica"] = $dbh->getNotification($_GET["codEvento"], $_GET["codNotifica"]);

  if ($_SESSION["sessUser"]["email"] != $templateParams["notifica"]["emailUtente"]) {
    safeHeader("Location: index.php");
  }

  $templateParams["name"] = "notification_details.php";
  $templateParams["title"] = "Notifica";
  $templateParams["css"] = array("base.css");
  $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js");

  $dbh->readNotification($_GET["codEvento"], $_GET["codNotifica"]);

  require(TEMPLATE_DIR."base.php");
?>