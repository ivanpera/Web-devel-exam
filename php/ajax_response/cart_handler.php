<?php
  require_once('../../bootstrap.php');
  if (isset($_SESSION["sessUser"]) && !empty($_POST)) {
    if (!isset($_SESSION["sessUser"]["cart"][$_POST["codEvento"]][$_POST["codTipologia"]."/".$_POST["costo"]])) {
      $_SESSION["sessUser"]["cart"][$_POST["codEvento"]][$_POST["codTipologia"]."/".$_POST["costo"]] = 0;
    }
    $_SESSION["sessUser"]["cart"][$_POST["codEvento"]][$_POST["codTipologia"]."/".$_POST["costo"]] += $_POST["num"];
    if($_SESSION["sessUser"]["cart"][$_POST["codEvento"]][$_POST["codTipologia"]."/".$_POST["costo"]] <= 0) {
      unset($_SESSION["sessUser"]["cart"][$_POST["codEvento"]][$_POST["codTipologia"]."/".$_POST["costo"]]);
    }
    if(empty($_SESSION["sessUser"]["cart"][$_POST["codEvento"]])) {
      unset($_SESSION["sessUser"]["cart"][$_POST["codEvento"]]);
    }
    echo "OK";
  } else {
    safeHeader("Location: ../../index.php");
  }
?>