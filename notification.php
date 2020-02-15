<?php
  require_once("bootstrap.php");
  if(!isset($_SESSION["sessUser"])) {
    $_SESSION["previousPage"] = "notification.php";
    safeHeader("Location: login.php");
  }
  $userEmail = $_SESSION["sessUser"]["email"];

  $templateParams["name"] = "notification.php";
  $templateParams["title"] = "Notifiche";
  $templateParams["css"] = array("base.css", "notification.css");
  $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js", "js/notification.js");

  $templateParams["notifiche"] = $dbh->getNotificationFor($userEmail);

  require(TEMPLATE_DIR."base.php");
?>