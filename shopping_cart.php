<?php
  require_once('bootstrap.php');
  if(!isset($_SESSION["sessUser"])) {
    safeHeader("Location: login.php");
  }

  $templateParams["name"] = "shopping_cart.php";
  $templateParams["title"] = "Carrello";
  $templateParams["css"] = array("base.css", "shopping_cart.css");
  $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js", "js/cart_handler.js");

  $total = 0;
  if (isset($_SESSION["sessUser"]["cart"])) {
    foreach ($_SESSION["sessUser"]["cart"] as $codEvento => $bigliettiEvento) {
      $templateParams["eventi"][$codEvento] = $dbh->getEvent($codEvento)[0];
      foreach($_SESSION["sessUser"]["cart"][$codEvento] as $tipoCosto => $numero) {
        $total += intval(explode("/", $tipoCosto)[1]) * $numero;
      }
    }
  }
  require(TEMPLATE_DIR."base.php");

?>