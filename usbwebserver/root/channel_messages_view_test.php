<?php
	session_start();
	include "session_check.php";
	include "db_connect.php";
	
	$email = $_SESSION['email'];
	$selected_workspace = $_SESSION['selected_workspace'];
	$selected_channel = $_SESSION['selected_channel'];
	
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Snickr Chat</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<style type="text/css">
	body{ font: 15px sans-serif; }
	
	.chat
	{
		list-style: none;
		margin: 0;
		padding: 0;
	}

	.chat li
	{
		margin-bottom: 5px;
	}

	.chat li.left .chat-body
	{
		margin-left: 60px;
	}

	.chat li.right .chat-body
	{
		margin-right: 60px;
	}


	.chat li .chat-body p
	{
		margin: 0;
		color: #777777;
	}

	.panel .slidedown .glyphicon, .chat .glyphicon
	{
		margin-right: 5px;
	}

	.panel-body
	{
		overflow-y: scroll;
		height: 68vh;
		background-color: #505050;
		border: 2px solid #FFFFFF;
	}

	::-webkit-scrollbar-track
	{
		-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
		background-color: #F5F5F5;
	}

	::-webkit-scrollbar
	{
		width: 12px;
		background-color: #F5F5F5;
	}

	::-webkit-scrollbar-thumb
	{
		-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
		background-color: #555;
	}
	
	.chat #chatOutput {
		width: 100%;
	}
	
	.chat #chatOutput p {
		margin: 0;
		padding: 5px;
		word-break: break-all;
	}
	
	.container-fluid{
		background: url('https://images.pexels.com/photos/1323550/pexels-photo-1323550.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500') no-repeat center center fixed; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
	}
	
	.navbar{
		width: 100%;
		margin: 0;
	}
	
	.container2 {
	  border: 2px solid #dedede;
	  background-color: #FFF7F7;
	  border-radius: 5px;
	  padding: 10px;
	  margin: 10px;
	}
	
	.panel-footer{
		background-color: #505050;
		border: 2px solid #FFFFFF;
	}
	
	.darker {
	  border-color: #ccc;
	  background-color: #F3F9FF;
	}
	
	html { 
        background: url('https://images.pexels.com/photos/1323550/pexels-photo-1323550.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500') no-repeat center center fixed; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
	
</style>
</head>
<body>
<div class="bs-example">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a href="welcome.php" class="navbar-brand">Snickr</a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
		
		<div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav">
				<div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Profile</a>
                    <div class="dropdown-menu">
                        <a href="profile.php" class="dropdown-item">Update Profile</a>
						<a href="change_password.php" class="dropdown-item">Change Password</a>
                    </div>
                </div>
				
				<div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Invitations</a>
                    <div class="dropdown-menu">
                        <a href="received_workspace_invites.php" class="dropdown-item">Received Workspace Invites</a>
						<a href="received_channel_invites.php" class="dropdown-item">Received Channel Invites</a>
                        <a href="sent_invites.php" class="dropdown-item">View Sent Invites</a>
                    </div>
                </div>
				
				<div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Workspaces</a>
                    <div class="dropdown-menu">
						<a href="create_workspace.php" class="dropdown-item">Create a new workspace</a>
						<a href="remove_workspace.php" class="dropdown-item">Delete a workspace</a>
						<a href="view_members.php" class="dropdown-item">View all workspace members and admins</a>
						<a href="view_my_memberships.php" class="dropdown-item">View my memberships</a>
						<a href="join_public_channels.php" class="dropdown-item">Join public channels in your workspaces</a>
						<a href="add_workspace_users.php" class="dropdown-item">Add users to a workspace</a>
                        <a href="remove_workspace_users.php" class="dropdown-item">Remove users from a workspace</a>
                        <a href="add_workspace_admins.php" class="dropdown-item">Promote admins in a workspace</a>
                        <a href="remove_workspace_admins.php" class="dropdown-item">Demote admins in a workspace</a>
						<a href="add_workspace_channels.php" class="dropdown-item">Add channels to a workspace</a>
                        <a href="remove_workspace_channels.php" class="dropdown-item">Remove channels from a workspace</a>
						<a href="add_channel_users.php" class="dropdown-item">Add users to a channel</a>
                        <a href="remove_channel_users.php" class="dropdown-item">Remove users from a channel</a>
						<a href="leave_workspace.php" class="dropdown-item">Leave a workspace</a>
                        <a href="leave_channel.php" class="dropdown-item">Leave a channel</a>
                    </div>
                </div>
				
                <a href="choose_channel.php" class="nav-item nav-link">Workspace Chat</a>
				
            </div>
			<form class="form-inline ml-auto">
                <a href="logout.php" class="btn btn-danger">Logout</a>
			</form>
        </div>
    </nav>
	<div class="container-fluid">
			<?php
				$a = $mysqli->query("SELECT ws.workspace_name, c.channel_name
									FROM channels c JOIN workspace ws
									WHERE (c.workspace_id = ws.workspace_id)
										AND (c.workspace_id = '$selected_workspace') 
										AND (c.channel_name = '$selected_channel')");
				if(!$a){
					header('Refresh: 2; URL = logout.php');
					die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT GET USER INFORMATION</h2>
					<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to login</h3>");
				}
				
				$row = $a->fetch_assoc();
				$workspace_name = $row['workspace_name'];
				$channel_name = $row['channel_name'];
			?>
        <h2 style="font-family:Arial;" class="text-center"><b><?php echo "[$selected_workspace] $workspace_name - $channel_name"?></b></h2>
		
		<div class="container">
			<div id = "panelbody1" class="panel-body">
				<ul class="chat">
					<div id="chatOutput" style="overflow:auto"></div>
				</ul>
			</div>
			
			<div class="panel-footer">
				<div class="input-group">
					<textarea id="chatInput" class="form-control" rows="3" cols="25" placeholder="Type your message here..."></textarea>
					<span class="input-group-btn">
						<button class="btn btn-warning btn-lg" id="chatSend">
							Send</button>
					</span>
				</div>
			</div>
		 </div>
		
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="js/rChat.js"></script>
<script>
$(document).ready(function() {
    var chatInterval = 250; //refresh interval in ms
    var $userName = $("#email"); //CHANGE TO EMAIL
    var $chatOutput = $("#chatOutput");
    var $chatInput = $("#chatInput");
    var $chatSend = $("#chatSend");
	
	var scrolled = false;
	function updateScroll(){
		if(!scrolled){
			var element = document.getElementById("panelbody1");
			element.scrollTop = element.scrollHeight;
		}
	}
	$("#panelbody1").on('scroll', function(){
		scrolled=true;
	});
	setInterval(updateScroll,chatInterval);
	
    function sendMessage() {
        var userNameString = $userName.val();
        var chatInputString = $chatInput.val();

        $.get("./write.php", {
            username: userNameString,
            text: chatInputString
        });

        $userName.val("");
        retrieveMessages();
		
		scrolled = false;
    }

    function retrieveMessages() {
        $.get("./read_test.php", function(data) {
            $chatOutput.html(data); //Paste content into chat output
        });
    }

    $chatSend.click(function() {
        sendMessage();
		document.getElementById("chatInput").value='';
    });

    setInterval(function() {
        retrieveMessages();
    }, chatInterval);
	
	// $('#chatInput').keypress(function(event) {
		// if (event.keyCode == 13 || event.which == 13) {
			// sendMessage();
			// document.getElementById("chatInput").value='';
		// }
	// });
	
	$(document).delegate('#chatInput', 'keydown', function(e) {
	  var keyCode = e.keyCode || e.which;

	  if (keyCode == 9) {
		e.preventDefault();
		var start = this.selectionStart;
		var end = this.selectionEnd;

		// set textarea value to: text before caret + tab + text after caret
		$(this).val($(this).val().substring(0, start)
					+ "\t"
					+ $(this).val().substring(end));

		// put caret at right position again
		this.selectionStart =
		this.selectionEnd = start + 1;
	  }
	});
	
});
</script>

</body>
</html>

<?php $mysqli->close(); ?>