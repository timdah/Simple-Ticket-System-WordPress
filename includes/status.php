<?php
// Zugriff einschränken
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	exit;
}

$what = $_POST["what"];
if(isset($_POST["mail"])){$mail = $_POST["mail"];}
if(isset($_POST["title"])){$title = $_POST["title"];}
if(isset($_POST["text"])){$text = nl2br($_POST["text"]);}

// für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
$header  = 'MIME-Version: 1.0' . "\r\n";
$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$nachricht = '
<html>
<head>
  <title>'. $title .'</title>
</head>
<body>
  <p>'. $text .'</p>
</body>
</html>
';


if($what == 'take')
{	
	mail($mail, $title, $nachricht, $header);
}
if($what == 'done')
{
	mail($mail, $title, $nachricht, $header);
}
if($what == 'answer')
{
	mail($mail, $title, $nachricht, $header);
}
if($what == 'test') {
	mail($mail, 'Test', 'It works!');
}
?>