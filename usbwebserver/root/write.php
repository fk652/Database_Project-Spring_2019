<?php
session_start();
include "session_check.php";
include "db_connect.php";

$email = $_SESSION['email'];
$selected_workspace = $_SESSION['selected_workspace'];
$selected_channel = $_SESSION['selected_channel'];

//get user-input from url
$text=$_GET["text"];
//escaping is extremely important to avoid injections!
$textEscaped = htmlentities(mysqli_real_escape_string($db, $text)); //escape text and limit it to 128 chars

try{
	//create query
	$query="INSERT INTO messages (workspace_id, channel_name, email, message) 
					VALUE('$selected_workspace', '$selected_channel', '$email', ?)";
	$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
	
	//execute query
	if($stmt = $mysqli->prepare($query)){
		// Bind variables to the prepared statement as parameters
		$stmt->bind_param('s', $text);

		// Attempt to execute the prepared statement
		if( !($stmt->execute()) ){
			throw new Exception();
			
		}
		$stmt->close();
	}
	$mysqli->commit();
} catch(Exception $e){
	$mysqli->rollback();
	header('Refresh: 2; URL = choose_channel.php');
	die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">ERROR WRITING MESSAGE</h2>
	<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to choose channel page</h3>");
}

$mysqli->close();
?>