<?php
session_start();
include "session_check.php";
include "db_connect.php";
$email = $_SESSION['email'];

// Define variables and initialize with empty values
$curr_password = $new_password = $confirm_password = "";
$curr_password_err = $new_password_err = $confirm_password_err = $update_status = "";

// Always clean user input for security purposes
function clean_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Get current password
    $sql1 = "SELECT u_password FROM users u WHERE u.email = '$email'";
    $userinfo = $mysqli->query($sql1);
    $row = $userinfo->fetch_assoc();
    $hashed_password = $row['u_password'];
    
    // Clean input
    $curr_password = clean_input($_POST["curr_password"]);
    $new_password = clean_input($_POST["new_password"]);
    $confirm_password = clean_input($_POST["confirm_password"]);

    // Validate current password
    if(empty($curr_password)){
        $curr_password_err = "Please enter a password.";     
    } elseif(strlen($curr_password) < 6){
        $curr_password_err = "Password must have at least 6 characters.";
    } elseif(!(password_verify($curr_password, $hashed_password))){
        $curr_password_err = "Password does not match current password.";
    }
    // Validate new password
    if(empty($new_password)){
        $new_password_err = "Please enter a password.";     
    } elseif(strlen($new_password) < 6){
        $new_password_err = "Password must have at least 6 characters.";
    }
    // Validate confirm password
    if(empty($confirm_password)){
        $confirm_password_err = "Please confirm password.";     
    } elseif($new_password != $confirm_password){
        $confirm_password_err = "Passwords did not match.";
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
            $update_status = "<span style='color:red'>ERROR WITH UPDATING PASSWORD</span>";
        }
        $mysqli->close();
        
        // Inform the user of update status
        if($updated){
            $update_status = "<span style='color:green'>Successfully updated password</span>";
        }
        else{
            $update_status = "<span style='color:red'>Could not update password</span>";
        }
        // Reset input values
        $curr_password = $new_password = $confirm_password = "";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Snickr Manage Profile</title>

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
	background: url('https://images.pexels.com/photos/1158682/pexels-photo-1158682.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500') no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}
</style>

<!-- javascript form validation -->
<script>
function validateForm(){
    var currentPass = document.forms["passwordForm"]["curr_password"].value;
    var newPass = document.forms["passwordForm"]["new_password"].value;
    var confirmPass = document.forms["passwordForm"]["confirm_password"].value;
    var validated = true;
    //current password validation
    document.getElementById("current_pass_err").innerHTML = "";
    document.getElementById("current_pass").style.border = "1px solid light-gray";
    if(currentPass.length < 6){
        document.getElementById("current_pass_err").innerHTML = "Password must have at least 6 char<br>";
        document.getElementById("current_pass").style.border = "2px solid red";
        validated = false;
    }
    //new password validation
    document.getElementById("new_pass_err").innerHTML = "";
    document.getElementById("new_pass").style.border = "1px solid light-gray";
    if(newPass.length < 6){
        document.getElementById("new_pass_err").innerHTML = "Password must have at least 6 char<br>";
        document.getElementById("new_pass").style.border = "2px solid red";
        validated = false;
    }
    //confirm password validation
    document.getElementById("confirm_pass_err").innerHTML = "";
    document.getElementById("confirm_pass").style.border = "1px solid light-gray";
    if(confirmPass.length < 6){
        document.getElementById("confirm_pass_err").innerHTML = "Password must have at least 6 char<br>";
        document.getElementById("confirm_pass").style.border = "2px solid red";
        validated = false;
    }
    if(newPass != confirmPass){
        document.getElementById("confirm_pass_err").innerHTML += "Password does not match new password<br>";
        document.getElementById("confirm_pass").style.border = "2px solid red";
        validated = false;
    }
    return validated;
}
</script>
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
<!-- form -->
<div class="container-fluid form-style">
    <form name="passwordForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
    method="post" onsubmit="return validateForm()">
        <!-- title and update status -->
        <br>
        <h4 class="text-center"><?php echo $update_status; ?></h4>
        <h3 class="text-center">Change Password</h3>
        <!-- current password -->
        <div class="form-group">
            <label>Current Password</label>
            <input id="current_pass" type="password" name="curr_password" class="form-control"
            value="<?php echo $curr_password; ?>" required>
            <span id="current_pass_err" class="help-block text-danger"><?php echo $curr_password_err; ?></span>
        </div>
        <!-- new password -->
        <div class="form-group">
            <label>New Password</label>
            <input id="new_pass" type="password" name="new_password" class="form-control" 
            value="<?php echo $new_password; ?>" required>
            <span id="new_pass_err" class="help-block text-danger"><?php echo $new_password_err; ?></span>
        </div>
        <!-- confirm new password -->
        <div class="form-group">
            <label>Confirm Password</label>
            <input id="confirm_pass" type="password" name="confirm_password" class="form-control" 
            value="<?php echo $confirm_password; ?>" required>
            <span id="confirm_pass_err" class="help-block text-danger"><?php echo $confirm_password_err; ?></span>
        </div>
        <!-- submit -->
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Change Password">
        </div>
    </form>
    <br>
</div>

</body>
</html>