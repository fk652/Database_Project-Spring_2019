<?php
	session_start();
	include "session_check.php";
	include "db_connect.php";
	
	$email = $_SESSION['email'];
	
	// Define variables and initialize with empty values
	$ws_name = $ws_des = "";
	$ws_name_err = $las_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		if(empty(trim($_POST["ws_name"]))){
			$ws_name_err = "Please enter a name for your workspace.";     
		} else{
			$ws_name = trim($_POST["ws_name"]);
		}
		
		if(empty(trim($_POST["ws_des"]))){
			$ws_des_err = "Please enter a description for your workspace.";     
		} else{
			$ws_des = trim($_POST["ws_des"]);
		}
		
		// Check input errors before inserting in database
		if(empty($ws_name_err) && empty($ws_des_err)){
			$created = false;
			try{
				$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
				$a = $mysqli->query("SELECT AUTO_INCREMENT FROM information_schema.TABLES 
						WHERE TABLE_SCHEMA = 'snickr_database' AND TABLE_NAME = 'workspace' ");
				if(!$a)
					throw new Exception();
				
				$row = $a->fetch_assoc();
				$ws_id = $row["AUTO_INCREMENT"];
				
				if( !($stmt = $mysqli->prepare("INSERT INTO Workspace (workspace_id, workspace_name, workspace_owner, description) 
				VALUES('$ws_id', ?, '$email', ?)")) )
					throw new Exception();
				$stmt->bind_param("ss",$param_ws_name, $param_ws_des);
				
				$param_ws_name = $ws_name;
				$param_ws_des = $ws_des;
				
				if( !($stmt->execute()) )
					throw new Exception();
				$stmt->close();
				
				$a = $mysqli->query("INSERT INTO workspace_admins (email, workspace_id) VALUES('$email', '$ws_id')");
				if(!$a)
					throw new Exception();
				
				$a = $mysqli->query("INSERT INTO workspace_members (email, workspace_id) VALUES('$email', '$ws_id')");
				if(!$a)
					throw new Exception();
				
				$mysqli->commit();
				$created = true;
			} catch(Exception $e){
				$mysqli->rollback();
				header('Refresh: 2; URL = create_workspace.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">UNEXPECTED ERROR WITH CREATING WORKSPACE</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to create workspace page</h3>");
			}
			$mysqli->close();
			
			if($created){
				header('Refresh: 2; URL = create_workspace.php');
				die("<h2 style=\"font-weight:bold;text-align:center;color:green;\">Successfully created workspace</h2>
				<h3 style=\"font-weight:bold;text-align:center;\">Redirecting to create workspace page</h3>");
			}
				
		}
		
		// Close connection
		// $mysqli->close();
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
			<h3 class="text-center">Creating a New Workspace</h3>
			<div class="form-group <?php echo (!empty($ws_name_err)) ? 'has-error' : ''; ?>">
                <label class="font-weight-bold">Workspace Name</label>
                <input type="text" name="ws_name" class="form-control" placeholder="name your workspace" value="<?php echo $ws_name; ?>">
                <span class="help-block text-danger"><?php echo $ws_name_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($ws_des_err)) ? 'has-error' : ''; ?>">
                <label class="font-weight-bold">Workspace Description</label>
                <textarea name="ws_des" class="form-control" rows="5" placeholder="describe the use of your workspace" value="<?php echo $ws_des; ?>"></textarea>
                <span class="help-block text-danger"><?php echo $ws_des_err; ?></span>
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