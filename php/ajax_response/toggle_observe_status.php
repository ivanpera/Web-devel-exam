<?php
  require_once('../../bootstrap.php');
  $dbh->toggleObserveState($_REQUEST["codEvento"], $_REQUEST["emailUtente"]);
  $dbh->getObserveState($_REQUEST["codEvento"], $_REQUEST["emailUtente"]);
?>