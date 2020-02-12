<?php
  require_once('../../bootstrap.php');
  if (isset($_SESSION["sessUser"]) && !empty($_POST)) {
    if (!isset($_SESSION["sessUser"]["cart"][$_POST["codEvento"]][$_POST["codTipologia"]."/".$_POST["costo"]])) {
      $_SESSION["sessUser"]["cart"][$_POST["codEvento"]][$_POST["codTipologia"]."/".$_POST["costo"]] = 0;
    }
    $_SESSION["sessUser"]["cart"][$_POST["codEvento"]][$_POST["codTipologia"]."/".$_POST["costo"]] += $_POST["num"];
    echo "OK";
  } else {
    safeHeader("Location: ../../index.php");
  }
?>