<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$mail = sanitize_text_field($_POST["mail"]);
$what = sanitize_text_field($_POST["what"]);
$anrede = sanitize_text_field($_POST["anrede"]);
$name = sanitize_text_field($_POST["name"]);

$headers   = array();
$headers[] = "MIME-Version: 1.0";
$headers[] = "Content-type: text/plain; charset=utf-8";

$noreply = "\n\n--Diese Nachricht wurde automatisch generiert. Antworten an diese Adresse werden nicht entgegen genommen.--";

if($what == 'take')
{
	$text = $anrede." ".$name." hat sich soeben Ihrem Problem angenommen.\n";
	$text .= "Bei Veränderungen des Problems informieren Sie ihn bitte.\n\n";
	$text .= "Mit freundlichen Grüßen\nIT-Koordination";
	$text .= $noreply;
	mail($mail, 'Ihr Ticket wird bearbeitet', $text, implode("\r\n",$headers));
}
if($what == 'done')
{
	$text = "Ihr Problem wurde soeben behoben ";
	$text .= "und ist nun bei uns im System als erledigt markiert.\n";
	$text .= "Sollte dies nicht korrekt sein, melden Sie sich bitte bei ";
	$text .= $anrede;
	if($anrede == 'Herr'){$text .= "n";}
	$text .= " ".$name;
	$text .= ".\n\nMit freundlichen Grüßen\nIT-Koordination";
	$text .= $noreply;
	mail($mail, 'Ihr Problem ist behoben', $text, implode("\r\n",$headers));
}
if($what == 'change')
{
	$text = "Ihr Problem wurde an eine/n andere/n Mitarbeiter/in übergeben, ";
	$text .= "bei Veränderungen des Problems informieren Sie nun bitte ";
	$text .= $anrede;
	if($anrede == 'Herr'){$text .= "n";}
	$text .= " ".$name;
	$text .= ".\n\nMit freundlichen Grüßen\nIT-Koordination";
	$text .= $noreply;
	mail($mail, 'Neuer Bearbeiter Ihres Problems', $text, implode("\r\n",$headers));
}	
?>