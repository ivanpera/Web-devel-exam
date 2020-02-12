<?php
  require_once('../../bootstrap.php');
  if (isset($_SESSION["sessUser"]) && $_SESSION["sessUser"]["email"] == $_REQUEST["emailUtente"]) {
    echo $dbh->getObserveState(intval($_REQUEST["codEvento"]), $_REQUEST["emailUtente"]);
  } else {
    safeHeader("Location: ../../index.php");
  }
?>