<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
//Session Variablen aktivieren
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION["username"]);
unset($_SESSION["admin"]);
?>