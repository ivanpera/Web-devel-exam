<?php
  require_once("bootstrap.php");
  if(!isset($_SESSION["sessUser"])) {
    $_SESSION["previousPage"] = "user_area.php";
    safeHeader("Location: login.php");
  }
  $userEmail = $_SESSION["sessUser"]["email"];

  $templateParams["name"] = "user_area.php";
  $templateParams["title"] = "Area utente";
  $templateParams["css"] = array("base.css", "user_area.css");
  $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/user_area.js", "js/base.js");

  $genere["M"] = "Maschio";
  $genere["F"] = "Femmina";
  $genere["A"] = "Altro";

  $templateParams["userData"] = $dbh->getUserData($userEmail);
  $templateParams["observedEvents"] = $dbh->getObservedEvents($userEmail);
  $templateParams["organizedEvents"] = $dbh->getOrganizedEvents($userEmail);
  $templateParams["moderatedEvents"] = $dbh->getModeratedEvents($userEmail);
  $templateParams["bookedEvents"] = $dbh->getBookedEvents($userEmail);
  foreach ($templateParams["bookedEvents"] as &$evento) {
    $evento["bigliettiPrenotati"] = $dbh->getBookedSeats($evento["codEvento"], $userEmail);
  }
  $templateParams["recensioni"] = $dbh->getUserReviews($_SESSION["sessUser"]["email"]);

  require(TEMPLATE_DIR."base.php");
?>