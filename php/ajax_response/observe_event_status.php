<?php
require_once('../../bootstrap.php');
echo $dbh->getObserveState(intval($_REQUEST["codEvento"]), $_REQUEST["emailUtente"]);
?>