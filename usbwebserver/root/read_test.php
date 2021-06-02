<?php
session_start();
include "session_check.php";
include "db_connect.php";

$email = $_SESSION['email'];
$selected_workspace = $_SESSION['selected_workspace'];
$selected_channel = $_SESSION['selected_channel'];

date_default_timezone_set(date_default_timezone_get());
$date = date('Y-m-d h:i:s a', time());

$query="SELECT * FROM messages ORDER BY posted_timedate ASC";
//execute query
try{
	$a = $mysqli->query("SELECT u.u_nickname, m.message, m.posted_timedate
						FROM messages m JOIN users u
						WHERE (m.workspace_id = '$selected_workspace') AND (m.channel_name = '$selected_channel')
							AND (m.email = u.email)
						ORDER BY m.posted_timedate ASC
						");
	if(!$a){
		throw new Exception();
	}
	
	$container = "container2";
	
	while($row = $a->fetch_assoc()){
		$username=$row["u_nickname"];
		$text=$row["message"];
		$time=date('M-d-Y h:i a', strtotime($row["posted_timedate"]));
		$text2 = nl2br($text);
		$text3 = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $text2);
		if($container == container2){
			$container = "container2 darker";
		} else{
			$container = "container2";
		}
		
		echo "
				<div class=\"$container\">
				<li class=\"left clearfix\"><span class=\"chat-img pull-left\">
					<div class=\"header\">
							<font size=\"+1\"><strong class=\"primary-font\">$username</strong></font>
						</div>
				</span>
				<div class=\"chat-body clearfix\">
					<div class=\"header\">
						<strong class=\"primary-font\"></strong> <small class=\"pull-right text-muted\">
							<span class=\"glyphicon glyphicon-time\"></span>$time</small>
					</div>
						<p>$text3</p>
				</div>
			</li>
			</div>
			 ";
	}
} catch(Exception $e){
	/* header('Refresh: 2; URL = choose_channel.php');
	die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">ERROR WRITING MESSAGE</h2>
	<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to choose channel page</h3>"); */
	echo "An error occured";
}

$mysqli->close();
?>