<?php 
  require_once("../../bootstrap.php");
  if (isset($_SESSION["sessUser"]["email"])) {
    $dbh->readAllNots($_SESSION["sessUser"]["email"]);
  }
?>