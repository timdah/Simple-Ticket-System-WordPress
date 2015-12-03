<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$id = strval($lastid);
$param = encrypt($id, "Tu7nBL2XDc4j5XB62XyiHGDP");
$url = $_POST["url"];

// Returns a string if the URL has parameters or NULL if not
$query = parse_url($url, PHP_URL_QUERY);
if ($query) {
    $url .= '&tid=';
	$url .= $param;
} else {
    $url .= '?tid=';
	$url .= $param;
}

$linkURL = "<a href='" . $url . "'>" . $url . "</a>";
echo '<p>' . _e('Pleae store this link, you can consult your ticket there: ', 'simple-support-ticket-system') . $linkURL .  '</p>';

// functions
function encrypt($string, $key) {
  $result = '';
  for($i=0; $i<strlen ($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)+ord($keychar));
    $result.=$char;
  }

  return base64_encode($result);
}
?>