<?php
	session_start();
	include "session_check.php";
	include "db_connect.php";
	
	$email = $_SESSION['email'];
	
	// Define variables and initialize with empty values
	$curr_password = $new_password = $confirm_password = "";
	$curr_password_err = $new_password_err = $confirm_password_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		$sql1 = "SELECT u_password
				FROM users u
				WHERE u.email = '$email'
				";
		$userinfo = $mysqli->query($sql1);
		$row = $userinfo->fetch_assoc();
		$hashed_password = $row['u_password'];
		
		// Validate current password
		if(empty(trim($_POST["curr_password"]))){
			$curr_password_err = "Please enter a password.";     
		} elseif(strlen(trim($_POST["curr_password"])) < 6){
			$curr_password_err = "Password must have atleast 6 characters.";
		} elseif(!(password_verify($_POST["curr_password"], $hashed_password))){
			$curr_password_err = "Password does not match current password.";
		} else{
			$curr_password = trim($_POST["curr_password"]);
		}
		
		// Validate new password
		if(empty(trim($_POST["new_password"]))){
			$new_password_err = "Please enter a password.";     
		} elseif(strlen(trim($_POST["new_password"])) < 6){
			$new_password_err = "Password must have atleast 6 characters.";
		} else{
			$new_password = trim($_POST["new_password"]);
		}
		
		// Validate confirm password
		if(empty(trim($_POST["confirm_password"]))){
			$confirm_password_err = "Please confirm password.";     
		} else{
			$confirm_password = trim($_POST["confirm_password"]);
			if(empty($new_password_err) && ($new_password != $confirm_password)){
				$confirm_password_err = "Password did not match.";
			}
		}
		
		// Check input errors before inserting in database
		if(empty($curr_password_err) && empty($new_password_err) && empty($confirm_password_err)){
			$updated = false;
			try{
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				// Prepare an insert statement
				$sql = "UPDATE users SET u_password = ? WHERE email = '$email'";
				
				if($stmt = $mysqli->prepare($sql)){
					// Bind variables to the prepared statement as parameters
					$stmt->bind_param("s", $param_password);
				
					// Set parameters
					$param_password = password_hash($new_password, PASSWORD_DEFAULT); // Creates a password hash
				
					// Attempt to execute the prepared statement
					if( !($stmt->execute()) )
						throw new Exception();
					$stmt->close();
				}
				
				$mysqli->commit();
				$updated = true;
			} catch(Exception $e){
				$mysqli->rollback();
				header('Refresh: 2; URL = change_password.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">ERROR WITH UPDATING PASSWORD</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to change password page</h3>");
			}
			$mysqli->close();
			
			if($updated){
				header('Refresh: 2; URL = logout.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:green;\">Successfully updated password</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to login page</h3>");
			}
				
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Snickr Manage Profile</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
        background: url('https://images.pexels.com/photos/1158682/pexels-photo-1158682.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500') no-repeat center center fixed; 
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
                    <a class="nav-link dropdown-toggle active" data-toggle="dropdown">Manage Profile</a>
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
	
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<br>
			<h3 class="text-center">Change Password</h3>
			
			<div class="form-group <?php echo (!empty($curr_password_err)) ? 'has-error' : ''; ?>">
                <label>Current Password</label>
                <input type="password" name="curr_password" class="form-control" value="<?php echo $curr_password; ?>">
                <span class="help-block text-danger"><?php echo $curr_password_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block text-danger"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block text-danger"><?php echo $confirm_password_err; ?></span>
            </div>
			
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Change Password">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
		<br>
	</div>
</div>
</body>
</html>