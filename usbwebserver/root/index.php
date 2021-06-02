<?php
ob_start();
session_start();
include "db_connect.php";
    
// Check if the user is already logged in, if yes then redirect him to overview page
if(isset($_SESSION["valid"]) && $_SESSION["valid"] === true){
    header("location: logout.php");		// TO BE CHANGED LATER
    exit;
}

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

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
    $email = clean_input($_POST["email"]);
    $password = clean_input($_POST["password"]); 

    // Check if email is empty or not valid
    if(empty($email)){
        $email_err = "Please enter email.";
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $email_err = "Not a valid email.";
    }
    // Check if password is empty
    if(empty($password)){
        $password_err = "Please enter your password.";
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT email, u_password FROM users WHERE email = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            // Set parameters
            $param_email = $email;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                // Check if email exists, if yes then verify password
                if($stmt->num_rows() == 1){                    
                    // Bind result variables
                    $stmt->bind_result($email, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            //Rehashing passwording if needed
                            // if(password_needs_rehash($row["u_password"], PASSWORD_DEFAULT)){
                                // $newPass = password_hash($password, PASSWORD_DEFAULT);
                                // try{
                                    // $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                                    // if( !($mysqli->query("UPDATE users
                                                    // SET users.u_password = '$newPass'
                                                    // WHERE users.email = '$email'")) )
                                                    // throw new Exception();
                                    // $mysqli->commit();
                                // } catch(Exception $e){
                                    // $mysqli->rollback();
                                    // header('Refresh: 3; URL = logout.php');
                                    // die("<h2 align=\"center\">ERROR WITH LOGIN</h2><h3 align=\"center\">Going back to login page</h3>");
                                // }
                            // }

                            // Store data in session variables
                            $_SESSION["email"] = $email;
                            $_SESSION['valid'] = true;
                            $_SESSION['timeout'] = time();
                            // Set the user status to active
                            try{
                                $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                                if( !($stmt = $mysqli->prepare("UPDATE users SET active=1 WHERE email=? ")) )
                                    throw new Exception();
                                $stmt->bind_param("s",$param_email);
                                if( !($stmt->execute()) )
                                    throw new Exception();
                                $stmt->close();
                                $mysqli->commit();
                            } catch(Exception $e){
                                $mysqli->rollback();
                                header('Refresh: 3; URL = logout.php');
                                die("<h2 align=\"center\">ERROR WITH LOGGING IN</h2><h3 align=\"center\">Going back to login page</h3>");
                            }
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
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
<title>Snickr Login</title>

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
html{
    background: url(https://images.pexels.com/photos/265047/pexels-photo-265047.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500)
    no-repeat center center fixed; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
</style>
</head>
<body>

<!-- login form -->
<div class="login-form">
    <h1 style="text-align:center">Login</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- Email -->
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="email"
                value="<?php echo $email; ?>" required>
            <span class="help-block text-danger"><?php echo $email_err; ?></span>
        </div>
        <!-- Password  -->
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="password" class="form-control"
            required>
            <span class="help-block text-danger"><?php echo $password_err; ?></span>
        </div>
        <!-- submit -->
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <!-- registration link -->
        <p>Don't have an account? <a href="registration.php">Sign up now</a>.</p>
    </form>
</div>

</body>
</html>