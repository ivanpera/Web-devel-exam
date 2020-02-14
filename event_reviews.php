<?php
  require_once("bootstrap.php");

  if(!isset($_GET["codEvento"])) {
    safeHeader("Location: index.php");
  }

  $templateParams["nomeEvento"] = $dbh->getEventName($_GET["codEvento"]);
  $templateParams["name"] = "event_reviews.php";
  $templateParams["title"] = "Tutte le recensioni di ".$templateParams["nomeEvento"];
  $templateParams["css"] = array("base.css");
  $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js");
  $templateParams["recensioni"] = $dbh->getEventReviews($_GET["codEvento"]);

  if(empty($templateParams["recensioni"])) {
    safeHeader("Location: event_details.php?codEvento=".$_GET["codEvento"]);
  }

  require(TEMPLATE_DIR."base.php");

?>
