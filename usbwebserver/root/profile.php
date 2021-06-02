<?php
session_start();
include "session_check.php";
include "db_connect.php";
$email = $_SESSION['email'];

// Define variables and initialize with empty values
$first_name_in = $last_name_in = $nickname_in = $contact_info_in = $des_in = "";
$first_name_in_err = $last_name_in_err = $nickname_in_err = $contact_info_in_err = $des_in_err = "";
$update_status = "";

// Get this information early for placeholder values
$sql1 = "SELECT * FROM users u WHERE u.email = '$email'";
$userinfo = $mysqli->query($sql1);
$row = $userinfo->fetch_assoc();
$curr_first_name = $row['u_first_name'];
$curr_last_name = $row['u_last_name'];
$curr_nickname = $row['u_nickname'];
$curr_contact_info = $row['contact_info'];
$curr_description = $row['profile_description'];

// Always clean user input for security purposes
function clean_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Clean the input
    $first_name_in = clean_input($_POST["first_name_in"]);
    $last_name_in = clean_input($_POST["last_name_in"]);
    $nickname_in = clean_input($_POST["nickname_in"]);
    $contact_info_in = clean_input($_POST["contact_info_in"]);
    $des_in = clean_input($_POST["des_in"]);

    //setting input variables to current values, if it wasn't given
    if(empty($first_name_in)){
        $first_name_in = $curr_first_name;
    }
    if(empty($last_name_in)){
        $last_name_in = $curr_last_name;
    }
    if(empty($nickname_in)){
        $nickname_in = $curr_nickname; 
    }
    if(empty($contact_info_in)){
        $contact_info_in = $curr_contact_info;
    }
    if(empty($des_in)){
        $des_in = $curr_description;
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
            $update_status = "<span style='color:red'>ERROR WITH UPDATING</span>";
        }
        $mysqli->close();

        // Inform the user of the update status
        if($updated){
            $update_status = "<span style='color:green'>Successfully updated</span>";
            // Update new current value
            $curr_first_name = $first_name_in;
            $curr_last_name = $last_name_in;
            $curr_nickname = $nickname_in;
            $curr_contact_info = $contact_info_in;
            $curr_description = $des_in;
        } else{
            $update_status = "<span style='color:red'>Could not update</span>";
        }
        // Reset input values
        $first_name_in = $last_name_in = $nickname_in = $contact_info_in = $des_in = ""; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    background: url('https://images.pexels.com/photos/1158682/pexels-photo-1158682.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500')
     no-repeat center center fixed; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
</style>

<!-- javascript form validation -->
<script>
function validateForm(){
    var firstName = document.forms["profileForm"]["first_name_in"].value;
    var lastName = document.forms["profileForm"]["last_name_in"].value;
    var nickName = document.forms["profileForm"]["nickname_in"].value;
    var contactInfo = document.forms["profileForm"]["contact_info_in"].value;
    var description = document.forms["profileForm"]["des_in"].value;
    var validated = true;
    //check if form is empty
    if((firstName == "") && (lastName == "") && (nickName == "") && (contactInfo == "") && (description == "")){
        alert("Must input something in order to update");
        validated = false;
    }
    //validate contact info
    document.getElementById("contact_err").innerHTML = "";
    document.getElementById("contact").style.border = "1px solid light-gray";
    var phoneDigits = contactInfo.replace(/[^0-9]/g, ''); //get only the digits
    if( (contactInfo != "") && ((phoneDigits.length < 7) || (phoneDigits.length > 15)) ){
        document.getElementById("contact_err").innerHTML = "not a valid phone number<br>";
        document.getElementById("contact").style.border = "2px solid red";
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
    <form name="profileForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
    method="post" onsubmit="return validateForm()">
        <!-- title and update status -->
        <br>
        <h4 class="text-center"><?php echo $update_status; ?></h4>
        <h3 class="text-center">Profile Information</h3>
        <!-- first name -->
        <div class="form-group">
            <label class="font-weight-bold">First Name</label>
            <input type="text" name="first_name_in" class="form-control" maxlength="255"
            placeholder="<?php echo $curr_first_name; ?>" value="<?php echo $first_name_in; ?>">
            <span id="fname" class="help-block text-danger"><?php echo $first_name_in_err; ?></span>
        </div>
        <!-- last name -->
        <div class="form-group">
            <label class="font-weight-bold">Last Name</label>
            <input type="text" name="last_name_in" class="form-control" maxlength="255"
            placeholder="<?php echo $curr_last_name; ?>" value="<?php echo $last_name_in; ?>">
            <span id="lname" class="help-block text-danger"><?php echo $last_name_in_err; ?></span>
        </div>
        <!-- nickname -->
        <div class="form-group">
            <label class="font-weight-bold">Nickname (the username shown on your messages)</label>
            <input type="text" name="nickname_in" class="form-control" maxlength="255"
            placeholder="<?php echo $curr_nickname; ?>" value="<?php echo $nickname_in; ?>">
            <span id="nickname" class="help-block text-danger"><?php echo $nickname_in_err; ?></span>
        </div>
        <!-- contact info -->
        <div class="form-group">
            <label class="font-weight-bold">Phone Number (optional)</label>
            <input id="contact" type="text" maxlength="15" name="contact_info_in" class="form-control" 
            maxlength="50" placeholder="<?php echo $curr_contact_info; ?>" value="<?php echo $contact_info_in; ?>">
            <span id="contact_err" class="help-block text-danger"><?php echo $contact_info_in_err; ?></span>
        </div>
        <!-- profile description -->
        <div class="form-group">
            <label class="font-weight-bold">Profile Description (optional)</label>
            <textarea name="des_in" class="form-control" rows="5"
            placeholder="<?php echo $curr_description; ?>" value="<?php echo $des_in; ?>"></textarea>
            <span class="help-block text-danger"><?php echo $des_in_err; ?></span>
        </div>
        <!-- submit -->
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Update">
        </div>
    </form>
    <br>
</div>

</body>
</html>