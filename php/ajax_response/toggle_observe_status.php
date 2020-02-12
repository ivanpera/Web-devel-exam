<?php
  require_once('../../bootstrap.php');
  if (isset($_SESSION["sessUser"]) && $_SESSION["sessUser"]["email"] == $_REQUEST["emailUtente"]) {
    $dbh->toggleObserveState($_REQUEST["codEvento"], $_REQUEST["emailUtente"]);
  } else {
    safeHeader("Location: ../../index.php");
  }
?>