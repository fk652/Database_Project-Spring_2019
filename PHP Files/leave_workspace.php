<?php
	session_start();
	include "session_check.php";
	include "db_connect.php";
	
	$email = $_SESSION['email'];
	
	// Define variables and initialize with empty values
	$ws_in = "";
	$ws_in_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		if( (empty($_POST["ws_in"])) || ($_POST["ws_in"] == "NONE") ){
			$ws_in_err = "Please choose a workspace.";     
		} else{
			$ws_in = $_POST["ws_in"];
		}
		
		// Check input errors before inserting in database
		if(empty($ws_in_err)){
			$removed = false;
			try{
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				$b = $mysqli->query("DELETE FROM channel_members WHERE (workspace_id = '$ws_in') AND (email = '$email')");
				if(!$b){
					throw new Exception();
				}
				
				$c = $mysqli->query("DELETE FROM workspace_members WHERE (workspace_id = '$ws_in') AND (email = '$email')");
				if(!$c){
					throw new Exception();
				}
				
				$d = $mysqli->query("DELETE FROM workspace_admins WHERE (workspace_id = '$ws_in') AND (email = '$email')");
				if(!$d){
					throw new Exception();
				}
				
				$removed = true;
				$mysqli->commit();
				
			} catch(Exception $e){
				$mysqli->rollback();
				header('Refresh: 2; URL = leave_workspace.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT LEAVE WORKSPACE</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to leave workspace page</h3>");
			}
			$mysqli->close();
			
			if($removed){
				header('Refresh: 2; URL = leave_workspace.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:green;\">Successfully left workspace</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to leave workspace page</h3>");
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

<style type="text/css">
    body{ font: 15px sans-serif;
		background-color: #FFFFFF00;	
		
	}
	
	.container-fluid{ border-radius: 10px; margin-top: 10px; width: 75%; max-width: 700px;
		
		background-color: #F0F0F0F3;
	}
	
	.navbar{
		width: 100%;
	}
	
	html { 
        background: url('https://images.pexels.com/photos/1496139/pexels-photo-1496139.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500') no-repeat center center fixed; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
	
	.form-inline {
	  float: right;
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
			<h3 class="text-center">Leave a Workspace<h6 class="text-center">(Requires Workspace Membership)</h6></h3>
			<?php 
				$workspaces = $mysqli->query("SELECT wsm.workspace_id, ws.workspace_name, ws.description
											FROM workspace_members wsm JOIN workspace ws
											WHERE (wsm.workspace_id = ws.workspace_id) AND (wsm.email = '$email')
											AND ((wsm.workspace_id, wsm.email) NOT IN 
													(SELECT workspace_id, workspace_owner FROM workspace WHERE workspace_owner = '$email'))
											");
			?>
			<div class="form-group <?php echo (!empty($ws_in_err)) ? 'has-error' : ''; ?>">
			  <label class="col-md control-label" for="ws_in"><h6><strong>Select a Workspace</strong></h6></label>
			  <div class="col-md">
					<select  class="selectpicker form-control" data-live-search="true" name="ws_in">
					  <option></option>
					  <?php
					  while($rowWS = $workspaces->fetch_assoc()){
						echo "<option value='" . $rowWS['workspace_id'] .  "'
							data-subtext='" . $rowWS['description'] ."'>"
						. "[" . $rowWS['workspace_id'] . "] " . $rowWS['workspace_name'] . "</option>";
					  }
					  ?>
					</select>
					<span class="help-block text-danger"><?php echo $ws_in_err . "<br>"; ?></span>
					<span class="help-block">[ID]: workspace - description
					<br>Note that owners of a workspace can't leave their own workspace</span>
			  </div>
			</div>
			<script>
			$(document).ready(function() {
				$('select').selectpicker();
			});
			</script>
			 
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Leave">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
		<br>
	</div>
</div>
</body>
</html>