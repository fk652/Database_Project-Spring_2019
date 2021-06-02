<?php
	session_start();
	include "session_check.php";
	include "db_connect.php";
	
	$email = $_SESSION['email'];
	
	// Define variables and initialize with empty values
	$ch_in = $ch_name = $ch_type = "";
	$ch_in_err = $ch_name_err = $ch_type_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		if( (empty($_POST["ch_in"])) || ($_POST["ch_in"] == "NONE") ){
			$ch_in_err = "Please choose a workspace member.";     
		} else{
			$ch_in = $_POST["ch_in"];
		}
		
		if(empty(trim($_POST["ch_name"]))){
			$ch_name_err = "Please enter a channel name.";     
		} else{
			$ch_name = $_POST["ch_name"];
		}
		
		if(empty($_POST["ch_type"])){
			$ch_type_err = "Please choose a channel type.";     
		} else{
			$ch_type = $_POST["ch_type"];
		}
		
		// Check input errors before inserting in database
		if( (empty($ch_in_err)) && (empty($ch_name_err)) && (empty($ch_type_err)) ){
			$added1 = false;
			$name_check = false;
			try{
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				$sql = "SELECT * FROM channels WHERE workspace_id = '$ch_in' AND channel_name = ?";
			
				if($stmt = $mysqli->prepare($sql)){
					// Bind variables to the prepared statement as parameters
					$stmt->bind_param("s", $param_ch_name);
					
					// Set parameters
					$param_ch_name = trim($_POST["ch_name"]);
					
					// Attempt to execute the prepared statement
					if($stmt->execute()){
						/* store result */
						$stmt->store_result();
						
						if($stmt->num_rows() == 1){
							$mysqli->rollback();
							header('Refresh: 2; URL = add_workspace_channels.php');
							die("<h2 style=\"font-weight:bold;text-align:center;color:red\">CHANNEL NAME IS TAKEN</h2>
							<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to create workspace channels page</h3>");
						} else{
							$ch_name = trim($_POST["ch_name"]);
							$name_check = true;
						}
					} else{
						echo "Oops! Something went wrong. Please try again later.";
					}
				}
				
				if($name_check){
					$sql = "INSERT INTO channels (channel_name, workspace_id, channel_owner, channel_type)
						VALUE (?, '$ch_in', '$email', '$ch_type')";
			
					if($stmt = $mysqli->prepare($sql)){
						// Bind variables to the prepared statement as parameters
						$stmt->bind_param("s", $param_ch_name);
						
						// Set parameters
						$param_ch_name = trim($_POST["ch_name"]);
						
						// Attempt to execute the prepared statement
						if(!($stmt->execute())){
							$mysqli->rollback();
							header('Refresh: 2; URL = add_workspace_channels.php');
							die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT CREATE CHANNEL</h2>
							<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to create workspace channels page</h3>");
						}
					}
					
					$sql = "INSERT INTO channel_members (email, channel_name, workspace_id)
						VALUE ('$email', ?, '$ch_in')";
			
					if($stmt = $mysqli->prepare($sql)){
						// Bind variables to the prepared statement as parameters
						$stmt->bind_param("s", $param_ch_name);
						
						// Set parameters
						$param_ch_name = trim($_POST["ch_name"]);
						
						// Attempt to execute the prepared statement
						if($stmt->execute()){
							$added = true;
						} else{
							$mysqli->rollback();
							header('Refresh: 2; URL = add_workspace_channels.php');
							die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT ADD CHANNEL MEMBER</h2>
							<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to create workspace channels page</h3>");
						}
					}
				}
				$mysqli->commit();
			} catch(Exception $e){
				$mysqli->rollback();
				header('Refresh: 2; URL = add_workspace_channels.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT CREATE WORKSPACE CHANNEL</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to create workspace channels page</h3>");
			}
			$mysqli->close();
			
			if($added){
				header('Refresh: 2; URL = add_workspace_channels.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:green;\">Successfully created channel</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to create workspace channel page</h3>");
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
			<h3 class="text-center">Adding Channels to a Workspace<h6 class="text-center">(Requires Workspace Admin Privilege)</h6></h3>
			<?php 
				$workspaces = $mysqli->query("SELECT ws.workspace_id, ws.workspace_name, ws.description
											FROM workspace_admins wsa JOIN workspace ws
											WHERE (wsa.workspace_id = ws.workspace_id) AND (wsa.email = '$email')
											");
			?>
			<div class="form-group <?php echo (!empty($ch_in_err)) ? 'has-error' : ''; ?>">
			  <label class="col-md control-label" for="ch_in"><h6><strong>Select a Work</strong></h6></label>
			  <div class="col-md">
					<select  class="selectpicker form-control" data-live-search="true" name="ch_in">
					  <option></option>
					  <?php
					  while($rowWS = $workspaces->fetch_assoc()){
						echo "<option value='" . $rowWS['workspace_id'] .  "'
							data-subtext='" . $rowWS['description'] ."'>"
						. "[" . $rowWS['workspace_id'] . "] " . $rowWS['workspace_name'] . "</option>";
					  }
					  ?>
					</select>
					<span class="help-block text-danger"><?php echo $ch_in_err . "<br>"; ?></span>
					<span class="help-block">[workspaceID] workspaceName - description</span>
			  </div>
			</div>
			<script>
			$(document).ready(function() {
				$('select').selectpicker();
			});
			</script>
			
			<br>
			
			<!-- Multiple Radios -->
			<div class="form-group <?php echo (!empty($ch_type_err)) ? 'has-error' : ''; ?>">
			  <label style="font-weight:bold;" class="col-md control-label" for="ch_type"><h6><strong>Channel Type</strong></label>
			  <div class="col-md">
			  <div class="radio">
				<label for="ch_type-0">
				  <input type="radio" name="ch_type" id="ch_type-0" value="public" <?php if(isset($_POST['ch_type']) && $_POST["ch_type"] == "public"){echo 'checked="checked"';} ?>>
				  Public
				</label>
				</div>
			  <div class="radio">
				<label for="ch_type-1">
				  <input type="radio" name="ch_type" id="ch_type-1" value="private" <?php if(isset($_POST['ch_type']) && $_POST["ch_type"] == "private"){echo 'checked="checked"';} ?>>
				  Private
				</label>
				</div>
			  <div class="radio">
				<label for="ch_type-2">
				  <input type="radio" name="ch_type" id="ch_type-2" value="direct" <?php if(isset($_POST['ch_type']) && $_POST["ch_type"] == "direct"){echo 'checked="checked"';} ?>>
				  Direct
				</label>
				</div>
			  </div>
			<span class="help-block text-danger"><?php echo $ch_type_err . "<br>"; ?></span>
			</div>
			
			<div class="form-group <?php echo (!empty($ch_name_err)) ? 'has-error' : ''; ?>">
                <label><h6><strong>Channel Name</strong></label>
                <input type="text" name="ch_name" class="form-control" value="<?php echo $ch_name; ?>">
				<span class="help-block text-danger"><?php echo $ch_name_err . "<br>"; ?></span>
                <span class="help-block">Name must be unique to the workspace</span>
            </div>
			 
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Create">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
		<br>
	</div>
</div>
</body>
</html>