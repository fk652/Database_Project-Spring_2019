<?php
	session_start();
	include "session_check.php";
	include "db_connect.php";
	
	$email = $_SESSION['email'];
	
	// Define variables and initialize with empty values
	$first_name_in = $last_name_in = $nickname_in = $contact_info_in = $des_in = "";
	$first_name_in_err = $last_name_in_err = $nickname_in_err = $contact_info_in_err = $des_in_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		$sql1 = "SELECT *
				FROM users u
				WHERE u.email = '$email'
				";
		$userinfo = $mysqli->query($sql1);
		$row = $userinfo->fetch_assoc();
		$curr_first_name = $row['u_first_name'];
		$curr_last_name = $row['u_last_name'];
		$curr_nickname = $row['u_nickname'];
		$curr_contact_info = $row['contact_info'];
		$curr_description = $row['profile_description'];
		
		if(empty(trim($_POST["first_name_in"]))){
			$first_name_in = $curr_first_name;
		} else{
			$first_name_in = trim($_POST["first_name_in"]);
		}
		
		if(empty(trim($_POST["last_name_in"]))){
			$last_name_in = $curr_last_name;
		} else{
			$last_name_in = trim($_POST["last_name_in"]);
		}
		
		if(empty(trim($_POST["nickname_in"]))){
			$nickname_in = $curr_nickname; 
		} else{
			$nickname_in = trim($_POST["nickname_in"]);
		}
		
		if(empty(trim($_POST["contact_info_in"]))){
			$contact_info_in = $curr_contact_info;
		} else{
			$contact_info_in = trim($_POST["contact_info_in"]);
		}
		
		if(empty(trim($_POST["des_in"]))){
			$des_in = $curr_description;
		} else{
			$des_in = trim($_POST["des_in"]);
		}
		
		// Check input errors before inserting in database
		if(empty($first_name_in_err) && empty($last_name_in_err) && empty($nickname_in_err)){
			$updated = false;
			try{
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				
				// Prepare an insert statement
				$sql = "UPDATE users 
						SET u_first_name = ?, u_last_name = ?, 
							u_nickname = ?, profile_description = ?, contact_info = ?
						WHERE email = '$email'
						";
						
				if($stmt = $mysqli->prepare($sql)){
					// Bind variables to the prepared statement as parameters
					$stmt->bind_param('sssss', $first_name_in, $last_name_in, $nickname_in, $des_in, $contact_info_in);
				
					// Attempt to execute the prepared statement
					if( !($stmt->execute()) )
						throw new Exception();
					$stmt->close();
				}
				
				$mysqli->commit();
				$updated = true;
			} catch(Exception $e){
				$mysqli->rollback();
				header('Refresh: 2; URL = profile.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">ERROR WITH UPDATING</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to profile page</h3>");
			}
			$mysqli->close();
			
			if($updated){
				header('Refresh: 2; URL = profile.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:green;\">Successfully updated</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to profile page</h3>");
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
	
	.container-fluid{ border-radius: 10px; width: 75%; max-width: 700px; max-width: 700px;
		margin-top: 10px;
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
			<h3 class="text-center">Profile Information</h3>
			
			<?php 
				$sql1 = "SELECT *
						FROM users u
						WHERE u.email = '$email'
						";
				$userinfo = $mysqli->query($sql1);
				$row = $userinfo->fetch_assoc();
				$curr_first_name = $row['u_first_name'];
				$curr_last_name = $row['u_last_name'];
				$curr_nickname = $row['u_nickname'];
				$curr_contact_info = $row['contact_info'];
				$curr_description = $row['profile_description'];
			?>
			
			<div class="form-group <?php echo (!empty($first_name_in_err)) ? 'has-error' : ''; ?>">
                <label class="font-weight-bold">First Name</label>
                <input type="text" name="first_name_in" class="form-control" placeholder="<?php echo $curr_first_name; ?>" value="<?php echo $first_name_in; ?>">
                <span class="help-block text-danger"><?php echo $first_name_in_err; ?></span>
            </div>
			
			<div class="form-group <?php echo (!empty($last_name_in_err)) ? 'has-error' : ''; ?>">
                <label class="font-weight-bold">Last Name</label>
                <input type="text" name="last_name_in" class="form-control" placeholder="<?php echo $curr_last_name; ?>" value="<?php echo $last_name_in; ?>">
                <span class="help-block text-danger"><?php echo $last_name_in_err; ?></span>
            </div>
			
			<div class="form-group <?php echo (!empty($nickname_in_err)) ? 'has-error' : ''; ?>">
                <label class="font-weight-bold">Nickname</label>
                <input type="text" name="nickname_in" class="form-control" placeholder="<?php echo $curr_nickname; ?>" value="<?php echo $nickname_in; ?>">
                <span class="help-block text-danger"><?php echo $nickname_in_err; ?></span>
            </div>
			
			<div class="form-group <?php echo (!empty($contact_info_in_err)) ? 'has-error' : ''; ?>">
                <label class="font-weight-bold">Contact Info (optional)</label>
                <input type="text" maxlength="15" name="contact_info_in" class="form-control" placeholder="<?php echo $curr_contact_info; ?>" value="<?php echo $contact_info_in; ?>">
                <span class="help-block text-danger"><?php echo $contact_info_in_err; ?></span>
            </div>
			
			<div class="form-group <?php echo (!empty($des_in_err)) ? 'has-error' : ''; ?>">
                <label class="font-weight-bold">Profile Description (optional)</label>
                <textarea name="des_in" class="form-control" rows="5" placeholder="<?php echo $curr_description; ?>" value="<?php echo $des_in; ?>"></textarea>
                <span class="help-block text-danger"><?php echo $des_in_err; ?></span>
            </div>
			
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Update">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
		<br>
	</div>
</div>
</body>
</html>