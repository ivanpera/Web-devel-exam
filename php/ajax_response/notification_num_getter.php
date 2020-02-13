<?php
  require_once("../../bootstrap.php");
  if(isset($_SESSION["sessUser"])) {
    echo $dbh->getUnreadNotificationNum($_SESSION["sessUser"]["email"]);
  }

?>