<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

setcookie("ts_username", "0", time()-999999, "/");
setcookie("ts_admin", "0", time()-999999, "/");
?>