<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

setcookie("username", "0", time()-999999, "/", $_SERVER['SERVER_NAME']);
setcookie("admin", "0", time()-999999, "/", $_SERVER['SERVER_NAME']);
?>