<?php
    require_once('bootstrap.php');
    $searchTerms["NSFC"] = isset($_SESSION["sessUser"]["NSFC"]) ? $_SESSION["sessUser"]["NSFC"] : 0;
    if(!empty($_GET["event_name"])) {
        $searchTerms["nomeEvento"] = $_GET["event_name"];
    }
    if(!empty($_GET["luogo"])) {
        $searchTerms["codLuogo"] = $_GET["luogo"];
    }
    if(!empty($_GET["fromDate"])) {
        $searchTerms["fromData"] = $_GET["fromDate"];
    }
    if(!empty($_GET["toDate"])) {
        $searchTerms["toData"] = $_GET["toDate"];
    }
    if(!empty($_GET["categories"])) {
        $searchTerms["categories"] = $_GET["categories"];
    }
    $templateParams["name"] = "search_results.php";
    $templateParams["title"] = "Risultati della ricerca";
    $templateParams["css"] = array("base.css", "events.css");
    $templateParams["js"] = array("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js", "js/base.js");

    $templateParams["events"] = $dbh->searchEvent($searchTerms);
    require(TEMPLATE_DIR."base.php");
?>