<?php
  require_once('../bootstrap.php');
  $disponibili = true;
  $totale = 0;
  foreach ($_SESSION["sessUser"]["cart"] as $codEvento => $biglietto) {
    foreach ($biglietto as $tipoCosto => $numero) {
      $arrTipoCosto = explode("/",$tipoCosto);
      $disponibili = $disponibili && (intval($dbh->getRemainingSeats($codEvento, $arrTipoCosto[0], $arrTipoCosto[1])) >= $numero);
      $totale += intval($arrTipoCosto[1]) * $numero;
    }
  }
  if($disponibili) {
    $dataEOra = (new DateTime())->format("Y-m-d H:i:s");
    $differenzaGiorni = 7;
    $emailUtente = $_SESSION["sessUser"]["email"];
    $newBookingId = $dbh->insertBooking($dataEOra, $totale, $differenzaGiorni, $emailUtente);
    foreach ($_SESSION["sessUser"]["cart"] as $codEvento => $biglietto) {
      foreach ($biglietto as $tipoCosto => $numero) {
        $arrTipoCosto = explode("/",$tipoCosto);
        $dbh->bookSeats($newBookingId, $codEvento, $arrTipoCosto[0], $arrTipoCosto[1], $numero);
      }
    }
    unset($_SESSION["sessUser"]["cart"]);
    safeHeader("Location: ../communication.php?page=payment_ok.php");
  } else {
    safeHeader("Location: ../communication.php?page=payment_ko.php");
  }
?>