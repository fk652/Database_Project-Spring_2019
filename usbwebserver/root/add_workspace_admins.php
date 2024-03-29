<?php
	session_start();
	include "session_check.php";
	include "db_connect.php";
	
	$email = $_SESSION['email'];
	
	// Define variables and initialize with empty values
	$wsa_in = "";
	$wsa_in_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		if( (empty($_POST["wsa_in"])) || ($_POST["wsa_in"] == "NONE") ){
			$wsa_in_err = "Please choose a workspace member.";     
		} else{
			$wsa_in = $_POST["wsa_in"];
		}
		
		// Check input errors before inserting in database
		if(empty($wsa_in_err)){
			$added = false;
			try{
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				list($email_in, $ws_in) = explode("@@@@", $wsa_in);
				
				$c = $mysqli->query("INSERT INTO workspace_admins (workspace_id, email) VALUE('$ws_in','$email_in')");
				if(!$c){
					throw new Exception();
				}
				
				$added = true;
				$mysqli->commit();
				
			} catch(Exception $e){
				$mysqli->rollback();
				header('Refresh: 2; URL = add_workspace_admins.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT PROMOTE THIS MEMBER</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to promot workspace admin page</h3>");
			}
			$mysqli->close();
			
			if($added){
				header('Refresh: 2; URL = add_workspace_admins.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:green;\">Successfully promoted member</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to promote workspace admin page</h3>");
			}
				
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Snickr Manage Workspaces</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<link rel="stylesheet" type="text/css" href="snickr_style.css">
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
                    <a class="nav-link dropdown-toggle active" data-toggle="dropdown">Manage Workspaces</a>
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
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<br>
			<h3 class="text-center">Promote a Workspace Member to Admin<h6 class="text-center">(Requires Workspace Owner Privilege)</h6></h3>
			<?php 
				$workspace_members = $mysqli->query("SELECT wsm.workspace_id, ws.workspace_name, ws.description, wsm.email, u.u_nickname,
														u.u_first_name, u.u_last_name
													FROM users u JOIN workspace_members wsm JOIN workspace ws
													WHERE (u.email = wsm.email) AND (wsm.workspace_id = ws.workspace_id) AND (wsm.email != '$email')
													AND (ws.workspace_id IN (SELECT workspace_id FROM workspace WHERE workspace_owner = '$email'))
													AND ((wsm.workspace_id, wsm.email) NOT IN (SELECT workspace_id, email FROM workspace_admins))
													");
			?>
			<div class="form-group <?php echo (!empty($wsa_in_err)) ? 'has-error' : ''; ?>">
			  <label class="col-md control-label" for="wsa_in"><h6><strong>Select a Workspace - User</strong></h6></label>
			  <div class="col-md">
					<select  class="selectpicker form-control" data-live-search="true" name="wsa_in">
					  <option></option>
					  <?php
					  while($rowWSM = $workspace_members->fetch_assoc()){
						$value = $rowWSM['email'] . "@@@@" . $rowWSM['workspace_id'];
						echo "<option value='" . $value .  "'
							data-subtext='" . $rowWSM['u_first_name'] . " " . $rowWSM['u_last_name'] . " (" . 
								$rowWSM['u_nickname'] . ") " ."'>"
							. "[" . $rowWSM['workspace_id'] . "] " . $rowWSM['workspace_name']. ": " .
							$rowWSM['email'] . "</option>";
					  }
					  ?>
					</select>
					<span class="help-block text-danger"><?php echo $wsa_in_err . "<br>"; ?></span>
					<span class="help-block">[ID] workspace: email - member (nickname)</span>
			  </div>
			</div>
			<script>
			$(document).ready(function() {
				$('select').selectpicker();
			});
			</script>
			
			
			 
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Promote">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
		<br>
	</div>
</div>
</body>
</html>