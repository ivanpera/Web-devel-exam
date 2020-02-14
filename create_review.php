<?php
  require_once('bootstrap.php');
  if(!isset($_GET["codEvento"])) {
    safeHeader("Location: index.php");
  }
  if(!isset($_SESSION["sessUser"])) {
    $_SESSION["previousPage"] = "create_review.php?codEvento=".$_GET["codEvento"];
    safeHeader("Location: login.php");
  }
  if($dbh->hasUserWrittenReview($_GET["codEvento"], $_SESSION["sessUser"]["email"])) {
    safeHeader("Location: event_details.php?codEvento=".$_GET["codEvento"]);
  }

  $templateParams["name"] = "create_review.php";
  $templateParams["title"] = "Scrivi una recensione";
  $templateParams["css"] = array("base.css");
  $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js", "js/create_review.js");
  $templateParams["nomeEvento"] = $dbh->getEventName($_GET["codEvento"]);

  require(TEMPLATE_DIR."base.php");
?>