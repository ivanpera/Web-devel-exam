<?php
    session_start();
    define("TEMPLATE_DIR", "php/template/");
    define("CSS_DIR", "css/");
    define("IMG_DIR", "img/");
    require_once("php/DatabaseHelper.php");

    function safeHeader($headerString) {
        session_write_close();
        header($headerString);
        die();
    }
?>