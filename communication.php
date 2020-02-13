<?php
  require_once('bootstrap.php');
  if(!isset($_GET["page"])) {
    safeHeader("Location: index.php");
  }
  $templateParams["name"] = "com_".$_GET["page"];
  $templateParams["title"] = "Comunicazione";
  $templateParams["css"] = array("base.css");
  $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js");
  require(TEMPLATE_DIR."base.php");
?>