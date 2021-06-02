<?php
	session_start();
	include "session_check.php";
	include "db_connect.php";
	
	$email = $_SESSION['email'];
	
	// Define variables and initialize with empty values
	$chi_in = $status_type = "";
	$chi_in_err = $status_type_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		if( (empty($_POST["chi_in"])) || ($_POST["chi_in"] == "NONE") ){
			$chi_in_err = "Please choose a channel invite.";     
		} else{
			$chi_in = $_POST["chi_in"];
		}
		
		if(empty($_POST["status_type"])){
			$status_type_err = "Please choose an option.";     
		} else{
			$status_type = $_POST["status_type"];
		}
		
		// Check input errors before inserting in database
		if( (empty($chi_in_err)) && (empty($status_type_err)) ){
			try{
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				list($chiID, $chiName, $chiSender, $chiStatus, $chiDate) = explode(" @@@@ ", $chi_in);
				$handled = false;
				
				$check = $mysqli->query("SELECT * FROM channel_members 
										WHERE (email='$email') AND (workspace_id = '$chiID') AND (channel_name = '$chiName')");
				if(!$check){
					$mysqli->rollback();
					header('Refresh: 2; URL = received_channel_invites.php');
					die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT HANDLE CHECK</h2>
					<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to received channel invite page</h3>");
				}
				
				//already joined condition
				if($check->num_rows == 1){
					$b = $mysqli->query("UPDATE channel_invites SET chi_status = 3 
										WHERE (workspace_id = '$chiID') AND (chi_sender = '$chiSender')
											AND (chi_receiver = '$email') AND (chi_status = 0)
											AND (chi_invite_timedate = '$chiDate') AND (channel_name = '$chiName')
										");
					if(!$b){
						$mysqli->rollback();
						header('Refresh: 2; URL = received_channel_invites.php');
						die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT HANDLE STATUS 3</h2>
						<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to received channel invite page</h3>");
					}
					$handled = true;
				}
				
				//declined
				elseif($status_type == 2){
					$b = $mysqli->query("UPDATE channel_invites SET chi_status = 2 
										WHERE (workspace_id = '$chiID') AND (chi_sender = '$chiSender')
											AND (chi_receiver = '$email') AND (chi_status = 0)
											AND (chi_invite_timedate = '$chiDate') AND (hannel_name = '$chiName')
										");
					if(!$b){
						$mysqli->rollback();
						header('Refresh: 2; URL = received_channel_invites.php');
						die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT DECLINE INVITE</h2>
						<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to received channel invite page</h3>");
					}
					$handled = true;
				}
				//accepted
				elseif($status_type == 1){
					//update invite
					$b = $mysqli->query("UPDATE channel_invites SET chi_status = 1 
										WHERE (workspace_id = '$chiID') AND (chi_sender = '$chiSender')
											AND (chi_receiver = '$email') AND (chi_status = 0)
											AND (chi_invite_timedate = '$chiDate') AND (channel_name = '$chiName')
										");
					if(!$b){
						$mysqli->rollback();
						header('Refresh: 2; URL = received_channel_invites.php');
						die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT ACCEPT INVITE</h2>
						<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to received channel invite page</h3>");
					}
					
					//add to workspace members
					$c = $mysqli->query("INSERT INTO channel_members (workspace_id, channel_name, email) VALUE('$chiID', '$chiName', '$email')");
					if(!$c){
						$mysqli->rollback();
						header('Refresh: 2; URL = received_channel_invites.php');
						die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT ADD WORKSPACE MEMBER</h2>
						<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to received channel invite page</h3>");
					}
					
					$check2 = $mysqli->query("SELECT * FROM workspace_members WHERE (email='$email') AND (workspace_id = '$wsiID') ");
					if(!$check){
						$mysqli->rollback();
						header('Refresh: 2; URL = received_workspace_invites.php');
						die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT HANDLE CHECK</h2>
						<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to received workspace invite page</h3>");
					}
					
					//already joined workspace condition
					if($check2->num_rows == 0){
						$b = $mysqli->query("UPDATE workspace_invites SET wsi_status = 3 
											WHERE (workspace_id = '$chiID') AND (wsi_sender = '$chiSender')
												AND (wsi_receiver = '$email') AND (wsi_status = 0)
											");
						if(!$b){
							$mysqli->rollback();
							header('Refresh: 2; URL = received_channel_invites.php');
							die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT HANDLE W STATUS 3</h2>
							<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to received workspace invite page</h3>");
						}
						
						$d = $mysqli->query("INSERT INTO workspace_members (workspace_id, email) VALUE('$chiID', '$email')");
						if(!$d){
							$mysqli->rollback();
							header('Refresh: 2; URL = received_channel_invites.php');
							die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT ADD WORKSPACE MEMBER</h2>
							<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to received channel invite page</h3>");
						}
					}
					
					$handled = true;
				}
				
				$mysqli->commit();
			} catch(Exception $e){
				$mysqli->rollback();
				header('Refresh: 2; URL = received_channel_invites.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT HANDLE INVITE</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to received channel invite page</h3>");
			}
			$mysqli->close();
			
			if($handled){
				header('Refresh: 2; URL = received_channel_invites.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:green;\">Successfully handled invite</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to received channel invite page</h3>");
			}
				
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Snickr Manage Invites</title>
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
	
	.container-fluid{ border-radius: 10px; margin-top: 10px; width: 75%;
		
		background-color: #F0F0F0F3;
	}
	
	.navbar{
		width: 100%;
	}
	
	html { 
        background: url('https://d2v9y0dukr6mq2.cloudfront.net/video/thumbnail/cW5lDBG/e-mail-icons-move-in-perspective-view-on-dark-grid-background-internet-message-concept_s7esa7lke_thumbnail-full01.png') no-repeat center center fixed; 
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
                    <a class="nav-link dropdown-toggle active" data-toggle="dropdown">Manage Invitations</a>
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
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<br>
			<h3 class="text-center">Received Channel Invites</h3>
			
			<?php 
				$sql1 = "SELECT chi.workspace_id, chi.channel_name, ws.workspace_name, chi.chi_sender,
							u.u_first_name, u.u_last_name, u.u_nickname, chi.chi_status,
							chi.chi_invite_timedate, c.channel_type
						FROM workspace ws JOIN channel_invites chi JOIN users u JOIN channels c
						WHERE (c.workspace_id = chi.workspace_id) AND (chi.chi_sender = u.email)
							AND (c.channel_name = chi.channel_name) AND (c.workspace_id = ws.workspace_id)
							AND (chi.chi_receiver = '$email') AND (chi.chi_status = 0)
						";
				$channelinvites = $mysqli->query($sql1);
			?>
			<div class="form-group <?php echo (!empty($chi_in_err)) ? 'has-error' : ''; ?>">
			  <label class="col-md control-label" for="chi_in"><h6><strong>Select a Channel</strong></h6></label>
			  <div class="col-md">
					<select  class="selectpicker form-control" data-live-search="true" name="chi_in">
					  <option></option>
					  <?php
					  while($rowCH = $channelinvites->fetch_assoc()){
						$time=date('M-d-Y h:i a', strtotime($rowCH["chi_invite_timedate"]));
						$value = $rowCH['workspace_id'] . " @@@@ " . $rowCH['channel_name'] . " @@@@ " . $rowCH['chi_sender'] 
								. " @@@@ " . $rowCH['chi_status'] . " @@@@ " . $rowCH['chi_invite_timedate'];
						echo "<option value='" . $value .  "'
						data-subtext='" . "SENT BY: " . $rowCH['chi_sender'] . " (". $rowCH['u_first_name'] 
							. " " . $rowCH['u_last_name'] . " - " . $rowCH['u_nickname'] . ") " 
							. "[" . $time . "]" ."'>"
						. "[" . $rowCH['workspace_id'] . "] " . $rowCH['workspace_name'] . ": " . $rowCH['channel_name'] . " [" . $rowCH['channel_type'] . "] ". "</option>";
					  }
					  ?>
					</select>
					<span class="help-block text-danger"><?php echo $chi_in_err . "<br>"; ?></span>
					<span class="help-block">[workspaceID] workspace: channel [type] - SENT BY: email (sender - nickname) [dateSent]
					
			  </div>
			</div>
			<script>
			$(document).ready(function() {
				$('select').selectpicker();
			});
			</script>
			
			<br>
			
			<!-- Multiple Radios -->
			<div class="form-group <?php echo (!empty($status_type_err)) ? 'has-error' : ''; ?>">
			  <label style="font-weight:bold;" class="col-md control-label" for="status_type"><h6><strong>Accept or Decline</strong></label>
			  <div class="col-md">
			  <div class="radio">
				<label for="ch_type-0">
				  <input type="radio" name="status_type" id="status_type-0" value="1" <?php if(isset($_POST['status_type']) && $_POST["status_type"] == "1"){echo 'checked="checked"';} ?>>
				  Accept
				</label>
				</div>
			  <div class="radio">
				<label for="ch_type-1">
				  <input type="radio" name="status_type" id="status_type-1" value="2" <?php if(isset($_POST['status_type']) && $_POST["status_type"] == "2"){echo 'checked="checked"';} ?>>
				  Decline
				</label>
				</div>
			</div>
			<span class="help-block text-danger"><?php echo $status_type_err . "<br>"; ?></span>
			</div>
			
			 
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
		<br>
	</div>
</div>
</body>
</html>