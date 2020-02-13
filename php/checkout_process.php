<?
  require_once('../bootstrap.php');
  $disponibili = true;
  $totale = 0;
  foreach ($_SESSION["sessUser"]["cart"] as $codEvento => $biglietto) {
    foreach ($biglietto as $tipoCosto => $numero) {
      $arrTipoCosto = explode("/",$tipoCosto);
      $disponibili = $disponibili and ($dbh->getRemainingSeats($codEvento, $arrTipoCosto[0], $arrTipoCosto[1]) >= $numero);
      $totale += intval($arrTipoCosto[1]);
    }
  }
  if($disponibili) {
    //Crea prenotazione
    $dataEOra = (new DateTime())->format("Y-m-d H:i:s");
    $differenzaGiorni = 7;
    $emailUtente = $_SESSION["sessUser"]["email"];
    $newBookingId = $dbh->insertBooking($dataEOra, $totale, $differenzaGiorni, $emailUtente);
    //Per ogni biglietto aggiorna il cod prenotazione
    foreach ($_SESSION["sessUser"]["cart"] as $codEvento => $biglietto) {
      foreach ($biglietto as $tipoCosto => $numero) {
        $arrTipoCosto = explode("/",$tipoCosto);
        $dbh->bookSeats($newBookingId, $codEvento, $arrTipoCosto[0], $arrTipoCosto[1], $numero);
      }
    }
    //SafeHeader per qualche pagina di successo
  } else {
    //Safe header per qualche pagina di errore
  }
?>