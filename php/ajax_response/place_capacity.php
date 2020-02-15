<?php
  require_once('../../bootstrap.php');
  if (isset($_POST["codLuogo"])) {
    echo $dbh->getPlaceCapacity($_POST["codLuogo"]);
  } else {
    safeHeader("Location: ../../index.php");
  }
?>