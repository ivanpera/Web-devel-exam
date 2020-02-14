<?php
  require_once('../bootstrap.php');
  if(!isset($_SESSION["sessUser"])) {
    safeHeader("Location: ../index.php");
  }
  if(!isset($_POST)) {
    safeHeader("Location: ../index.php");
  }
  $codEvento = $_POST["codEvento"];
  $emailUtente = $_POST["emailUtente"];
  $voto = $_POST["voto"];
  $descrizione = $_POST["descrizione"];
  $anonima = (isset($_POST["anonymous"]) ? 1 : 0);
  $dataScrittura = date("Y-m-d");

  $dbh->insertEventReview($codEvento, $emailUtente, $voto, $descrizione, $anonima, $dataScrittura);
  safeHeader("Location: ../event_details.php?codEvento=".$codEvento);
?>