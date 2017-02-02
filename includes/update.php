<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;
$user = $_COOKIE["ts_username"];
?>

<?php
$id = sanitize_text_field($_POST["id"]);
$option = sanitize_text_field($_POST["option"]);
$status = NULL; $mail = NULL; $title = NULL;
$link_mail = NULL;
$query_stat = $wpdb->get_results($wpdb->prepare("SELECT status, mail, title, link FROM {$wpdb->prefix}sts_tickets WHERE id=%d",$id));
foreach($query_stat as $row)
{
	if($row->status === '1')
	{
		$status = '1';
		$mail = $row->mail;
		$title = $row->title;
		
		$link_mail = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'link_mail'");
		if($link_mail == '1') {
			$link_mail = $row->link;
		} else {
			$link_mail = NULL;
		}
	}
}
// Überprüfen ob übergeben wurde, was zu tun ist
if(isset($_POST["what"]))
{
	$what = sanitize_text_field($_POST["what"]);
	// Eintragen, dass das Ticket bearbeitet wurde
	if($what == 'done')
	{
		$check = $wpdb->get_results("SELECT geloest, bearbeiter FROM {$wpdb->prefix}sts_tickets WHERE id='$id'");		
		foreach($check as $row)
		{
			if($row->geloest != '1' && $row->bearbeiter == $user)
			{
				$stamp = time();
				$zeit = date(get_option('date_format') . ', ' . get_option('time_format'));
				$query = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_tickets SET geloest = '1', ende = %s, ende_timestamp = %s WHERE id=%d",$zeit,$stamp,$id));
				echo 'ja';
				if($status == '1')
				{
					$url = TS_DIR_URL.'includes/status.php';
					$text = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'mail_done'");
					$data = array('mail' => $mail, 'what' => 'done', 'title' => $title, 'text' => $text, 'link' => $link_mail);
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
			} else
			{
				echo 'nein';
			}
		}
	}
	// Das Ticket wird übernommen, wenn noch kein Bearbeiter eingetragen ist
	else if($what == 'take')
	{
		$check = $wpdb->get_results("SELECT bearbeiter FROM {$wpdb->prefix}sts_tickets WHERE id='$id'");		
		foreach($check as $row)
		{
			if($row->bearbeiter == 'unbekannt')
			{
				$query = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_tickets SET bearbeiter = %s WHERE id=%d",$user,$id));
				echo 'ja';
				if($status == '1')
				{
					$url = TS_DIR_URL.'includes/status.php';
					$text = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'mail_take'");
					$data = array('mail' => $mail, 'what' => 'take', 'title' => $title, 'text' => $text, 'link' => $link_mail);
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
			} else
			{
				echo 'nein';
			}
		}		
	} 
	// Ticket wird übernommen, wenn ein Benutzer eingetragen ist
	else if($what == 'change')
	{
		$query = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_tickets SET bearbeiter = %s WHERE id=%d",$user,$id));
	}
	// Das Ticket zurück holen
	else if($what == 'undo')
	{
		$query = $wpdb->query("UPDATE {$wpdb->prefix}sts_tickets SET geloest = '0' WHERE id='$id'");
	} 	
	// Abfrage des Texts für Bearbeitugnsfeld
	else if($what == 'load' && $option != 'antwort')
	{
		$query = $wpdb->get_results("SELECT $option FROM {$wpdb->prefix}sts_tickets WHERE id='$id'");
	}
} 
// Eintragen der Bearbeitungsfelder
else if(isset($_POST["text"]))
{
	$select = sanitize_text_field($_POST["select"]);
	$text = stripslashes(implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['text'] ) ) ) );
	if($select == 'termin')
	{
		$time = strtotime($text);
		$query = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_tickets SET $select=%s, termin_timestamp=%s WHERE id='$id'", $text, $time));
	} else
	{
		$query = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_tickets SET $select=%s WHERE id='$id'", $text));	
	}
}


if(isset($_POST["what"]))
{
	if($what == 'load' && $option != 'antwort')
	{
		foreach($query as $row)
		{
			// Aktuallisierung des Bearbeitungsfelds
			if($option != 'termin' && $option != 'bearbeiter' && $option != 'antwort')
			{
			?>
				<textarea maxLength="1000" class="update_text" type="text" required="required"><?php echo esc_textarea($row->$option); ?></textarea>
			<?php
			}
			// Ändern des Textfelds wenn Termin & Aktivierung des Datepickers
			else if($option == 'termin') 
			{
			?>
				<input type="text" type="text" maxLength="10" class="form-control" value="<?php echo esc_html($row->$option); ?>">
				<script>
				jQuery(document).ready(function() {
					jQuery('.form-control').datepicker({
						//format: "dd.mm.yyyy",
						//language: "de",
						todayHighlight: true,
						autoclose: true
					});
				});
				</script>
			<?php	
			} 
			// Ändern zu Auswahlfeld
			else if($option == 'bearbeiter') 
			{
			?>
				<select class="select_3">
			<?php
				$check = $wpdb->get_results("SELECT username, name FROM {$wpdb->prefix}sts_login ORDER BY name");		
				foreach($check as $row)
				{
			?>
					<option value="<?php echo esc_html($row->username); ?>"><?php echo esc_html($row->name); ?></option>
			<?php
				}
			?>
				</select>
			<?php	
			}
		}
	}
	else if($option == 'antwort') {
		?>
			<textarea maxLength="1000" class="update_text" type="text" required="required"></textarea>
		<?php		
	}
}
?>