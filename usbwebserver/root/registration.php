<?php
session_start();
include "db_connect.php";

// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$first_name = $last_name = $nickname = "";
$email_err = $password_err = $confirm_password_err = "";
$first_name_err = $last_name_err = $nickname_err = "";

// Always clean user input for security purposes
function clean_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Clean input
    $email = clean_input($_POST["email"]);
    $password = clean_input($_POST["password"]);
    $confirm_password = clean_input($_POST["confirm_password"]);
    $first_name = clean_input($_POST["first_name"]);
    $last_name = clean_input($_POST["last_name"]);
    $nickname = clean_input($_POST["nickname"]);

    // Validate email
    if(empty($email)){
        $email_err = "Please enter a email.";
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $email_err = "Not a valid email.";
    } else{	// Check if email is taken
        // Prepare a select statement
        $sql = "SELECT email FROM users WHERE email = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            // Set parameters
            $param_email = $email;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                /* store result */
                $stmt->store_result();
                if($stmt->num_rows() == 1){
                    $email_err = "This email is already taken.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
        $stmt->close();
    }
    // Validate password
    if(empty($password)){
        $password_err = "Please enter a password.";     
    } elseif(strlen($password) < 6){
        $password_err = "Password must have at least 6 characters.";
    }
    // Validate confirm password
    if(empty($confirm_password)){
        $confirm_password_err = "Please confirm password.";     
    } elseif($password != $confirm_password){
        $confirm_password_err = "Password did not match.";
    }
    // Check if first name is empty
    if(empty($first_name)){
        $first_name_err = "Please enter your first name.";     
    }
    // Check if last name is empty
    if(empty($last_name)){
        $last_name_err = "Please enter your last name.";     
    }
    // Check if nickname is empty
    if(empty($nickname)){
        $nickname_err = "Please enter a nickname.";     
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)
    && empty($first_name_err) && empty($last_name_err) && empty($nickname_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, u_password, u_first_name, u_last_name, u_nickname) VALUES (?, ?, ?, ?, ?)";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss", $param_email, $param_password, $param_first_name, $param_last_name, $param_nickname);
        
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_nickname = $nickname;
        
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header('Refresh: 2; URL = index.php');
                die("<h2 align=\"center\">Account successfully created</h2><h3 align=\"center\">Redirecting to login page</h3>");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        // Close statement and commit changes
        $stmt->close();
    }
    // Close connection
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Snickr Registration</title>

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
    background: url('https://images.pexels.com/photos/19670/pexels-photo.jpg?auto=compress&cs=tinysrgb&dpr=2&w=500') 
    no-repeat center center fixed; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
</style>
</head>
<body>

<div class="registration-form">
    <br>
    <h1 style="text-align:center">Sign Up</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- email -->
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" 
            value="<?php echo $email; ?>" required>
            <span class="help-block text-danger"><?php echo $email_err; ?></span>
        </div>
        <!-- password  -->
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" 
            value="<?php echo $password; ?>" required>
            <span class="help-block text-danger"><?php echo $password_err; ?></span>
        </div>
        <!-- confirm password -->
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" 
            value="<?php echo $confirm_password; ?>" required>
            <span class="help-block text-danger"><?php echo $confirm_password_err; ?></span>
        </div>
        <!-- first name -->
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" 
            value="<?php echo $first_name; ?>" required>
            <span class="help-block text-danger"><?php echo $first_name_err; ?></span>
        </div>
        <!-- last name -->
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" 
            value="<?php echo $last_name; ?>" required>
            <span class="help-block text-danger"><?php echo $last_name_err; ?></span>
        </div>
        <!-- nickname -->
        <div class="form-group">
            <label>Nickname <span><?php echo "(name that others see)"; ?></span> </label>
            <input type="text" name="nickname" class="form-control" 
            value="<?php echo $nickname; ?>" required>
            <span class="help-block text-danger"><?php echo $nickname_err; ?></span>
        </div>
        <!-- submit -->
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
        <!-- login link -->
        <p>Already have an account? <a href="index.php">Login here</a>.</p>
    </form>
</div>  
  
</body>
</html>