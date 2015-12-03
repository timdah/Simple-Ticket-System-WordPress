<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;

$id = sanitize_text_field($_POST["id"]);
$text = stripslashes(implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['text'] ) ) ) );
if($text == '') {
	return;
} else {
	if(isset($_COOKIE["ts_username"])) {
		$user = 'Support';
	} else {
		$user = NULL;
	}

	$check = $wpdb->get_var($wpdb->prepare("SELECT MAX(index_antwort) FROM wp_sts_answers WHERE ticket_id = %d",$id));
		if($check != NULL) {
			$wpdb->query($wpdb->prepare("INSERT INTO wp_sts_answers (ticket_id, index_antwort, user, antwort) VALUES (%d, %d, %s, %s)",
						$id, $check+1, $user, $text));
		} else {
			$wpdb->query($wpdb->prepare("INSERT INTO wp_sts_answers (ticket_id, index_antwort, user, antwort) VALUES (%d, %d, %s, %s)",
						$id, 1, $user, $text));
		}
		
	$status = NULL; $mail = NULL; $title = NULL;
	$query_stat = $wpdb->get_results($wpdb->prepare("SELECT status, mail, title FROM wp_sts_tickets WHERE id=%d",$id));
	foreach($query_stat as $row)
	{
		if($row->status === '1' && $user != NULL)
		{
			$mail = $row->mail;
			$title = $row->title;
			$text = $wpdb->get_var("SELECT ts_value FROM wp_sts_options WHERE ts_option = 'mail_answer'");
			$url = TS_DIR_URL.'includes/status.php';
			$data = array('mail' => $mail, 'what' => 'answer', 'title' => $title, 'text' => $text);
			//open connection
			$ch = curl_init();
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
			$result = curl_exec($ch);
			//close connection
			curl_close($ch);
		}
	}
}
?>