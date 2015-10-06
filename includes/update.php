<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;
$user = $_COOKIE["ts_username"];
?>

<?php
$id = sanitize_text_field($_POST["id"]);
$status = NULL;
$query_stat = $wpdb->get_results($wpdb->prepare("SELECT status, mail FROM wp_sts_tickets WHERE id=%d",$id));
foreach($query_stat as $row)
{
	if($row->status === '1')
	{
		$status = '1';
		$GLOBALS["mail"] = $row->mail;
		$query_stat2 = $wpdb->get_results($wpdb->prepare("SELECT anrede,name FROM wp_sts_login WHERE username=%s",$user));
		foreach($query_stat2 as $row2)
		{
			$GLOBALS["anrede"] = $row2->anrede;
			$GLOBALS["name"] = $row2->name;
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
		$stamp = time();
		$zeit = date(get_option('date_format') . ', ' . get_option('time_format'));
		
		$query = $wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET geloest = CASE WHEN bearbeiter = %s THEN '1' ELSE '0' END, ende = CASE WHEN bearbeiter = %s THEN %s ELSE '' END, ende_timestamp = %s WHERE id=%d",$user,$user,$zeit,$stamp,$id));
	}
	// Das Ticket wird übernommen, wenn noch kein Bearbeiter eingetragen ist
	else if($what == 'take')
	{
		$query = $wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET bearbeiter = CASE WHEN bearbeiter = 'unbekannt' THEN %s ELSE bearbeiter END WHERE id=%d",$user,$id));
	} 
	// Ticket wird übernommen, wenn ein Benutzer eingetragen ist
	else if($what == 'change')
	{
		$query = $wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET bearbeiter = %s WHERE id=%d",$user,$id));
		if($status == '1')
		{
			//set Post variables
			$url = TS_DIR_URL.'includes/status.php';
			$data = array('mail' => $mail, 'what' => 'change', 'anrede' => $anrede, 'name' => $name);
			//open connection
			$ch = curl_init();
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
			$result = curl_exec($ch);
			//close connection
			curl_close($ch);
		}
	}
	// Das Ticket zurück holen
	else if($what == 'undo')
	{
		$query = $wpdb->query("UPDATE wp_sts_tickets SET geloest = '0' WHERE id='$id'");
	} 	
	// Abfrage des Texts für Bearbeitugnsfeld
	else if($what == 'load')
	{
		$option = sanitize_text_field($_POST["option"]);
		$query = $wpdb->get_results("SELECT $option FROM wp_sts_tickets WHERE id='$id'");
	}
} 
// Eintragen der Bearbeitungsfelder
else if(isset($_POST["text"]))
{
	$select = sanitize_text_field($_POST["select"]);
	$text = sanitize_text_field($_POST["text"]);
	if($select == 'termin')
	{
		$time = strtotime($text);
		$query = $wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET $select=%s, termin_timestamp=%s WHERE id='$id'", $text, $time));
	} else
	{
		$query = $wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET $select=%s WHERE id='$id'", $text));	
	}
}
// Ausführen der SQL-Befehle
//mysql_query($query);

if(isset($_POST["what"]))
{
	if($what == 'load')
	{
		foreach($query as $row)
		{
			// Aktuallisierung des Bearbeitungsfelds
			if($option != 'termin' && $option != 'bearbeiter')
			{
			?>
				<textarea maxLength="500" class="update_text" type="text" required="required"><?php echo esc_textarea($row->$option); ?></textarea>
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
				$check = $wpdb->get_results("SELECT username, name FROM wp_sts_login ORDER BY name");		
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
	
	// Überprüfen, ob das Ticket übernommen werden kann
	if($what == 'take')
	{
		$check = $wpdb->get_results("SELECT bearbeiter FROM wp_sts_tickets WHERE id='$id'");		
		foreach($check as $row)
		{
			if($row->bearbeiter == $user)
			{
				echo 'ja';
				if($status == '1')
				{
					//set Post variables
					$url = TS_DIR_URL.'includes/status.php';
					$data = array('mail' => $mail, 'what' => 'take', 'anrede' => $anrede, 'name' => $name);
					//open connection
					$ch = curl_init();
					//set the url, number of POST vars, POST data
					curl_setopt($ch,CURLOPT_URL, $url);
					curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
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
	
	// Überprüfen, ob das Ticket nicht von anderen Personen übernommen wurde in der Zwischenzeit
	if($what == 'done')
	{
		$check = $wpdb->get_results("SELECT geloest FROM wp_sts_tickets WHERE id='$id'");		
		foreach($check as $row)
		{
			if($row->geloest == '1')
			{
				echo 'ja';
				if($status == '1')
				{
					//set Post variables
					$url = TS_DIR_URL.'includes/status.php';
					$data = array('mail' => $mail, 'what' => 'done', 'anrede' => $anrede, 'name' => $name);
					//open connection
					$ch = curl_init();
					//set the url, number of POST vars, POST data
					curl_setopt($ch,CURLOPT_URL, $url);
					curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
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
}
?>
