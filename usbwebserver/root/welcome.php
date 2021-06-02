<?php
session_start();
include "session_check.php";
include "db_connect.php";

$email = $_SESSION['email'];

$a = $mysqli->query("SELECT u_first_name, u_last_name FROM users WHERE email = '$email'");
if(!$a){
    header('Refresh: 2; URL = logout.php');
    die("<h2 style=\"font-weight:bold;text-align:center;color:red;\">COULD NOT GET USER INFORMATION</h2>
    <h3 style=\"font-weight:bold;text-align:center;\">Redirecting to login</h3>");
}
$row = $a->fetch_assoc();
$first_name = $row['u_first_name'];
$last_name = $row['u_last_name'];

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Snickr Welcome Page</title>

<!-- Bootstrap 4 -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- custom style -->
<link rel="stylesheet" type="text/css" href="snickr_style.css">
<style>
html { 
    background: url('https://images.pexels.com/photos/370717/pexels-photo-370717.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500') no-repeat center center fixed; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
img {
  max-width: 50%;
  height: auto;
  margin: auto;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
.jumbotron{
    background-color: transparent;
    padding: 5%;
}
</style>
</head>
<body>

<!-- navigation bar -->
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
<!-- welcome message -->
<div class="container-fluid">
    <br>
    <div class="jumbotron">
        <h1 class="text-center">Welcome Back to Snickr <?php echo $first_name . ' ' . $last_name ?>!</h2>
        <p class="lead text-center">
        Snickr - an alternative to running and managing workspaces for all your communication needs</p>
        <img src="https://cdn-images-1.medium.com/max/2600/1*W9JIV6e4n0F27JC4Fnn2NQ.jpeg">
    </div>	
</div>

</body>
</html>