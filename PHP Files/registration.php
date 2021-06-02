<?php
	session_start();
	include "db_connect.php";
	
	// Define variables and initialize with empty values
	$email = $password = $confirm_password = "";
	$first_name = $last_name = $nickname = "";
	$email_err = $password_err = $confirm_password_err = "";
	$first_name_err = $last_name_err = $nickname_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
	 
		// Validate email
		if(empty(trim($_POST["email"]))){
			$email_err = "Please enter a email.";
		} else{
			// Prepare a select statement
			$sql = "SELECT email FROM users WHERE email = ?";
			
			if($stmt = $mysqli->prepare($sql)){
				// Bind variables to the prepared statement as parameters
				$stmt->bind_param("s", $param_email);
				
				// Set parameters
				$param_email = trim($_POST["email"]);
				
				// Attempt to execute the prepared statement
				if($stmt->execute()){
					/* store result */
					$stmt->store_result();
					
					if($stmt->num_rows() == 1){
						$email_err = "This email is already taken.";
					} else{
						$email = trim($_POST["email"]);
					}
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}
			}
			 
			// Close statement
			$stmt->close();
		}
		
		// Validate password
		if(empty(trim($_POST["password"]))){
			$password_err = "Please enter a password.";     
		} elseif(strlen(trim($_POST["password"])) < 6){
			$password_err = "Password must have atleast 6 characters.";
		} else{
			$password = trim($_POST["password"]);
		}
		
		// Validate confirm password
		if(empty(trim($_POST["confirm_password"]))){
			$confirm_password_err = "Please confirm password.";     
		} else{
			$confirm_password = trim($_POST["confirm_password"]);
			if(empty($password_err) && ($password != $confirm_password)){
				$confirm_password_err = "Password did not match.";
			}
		}
		
		if(empty(trim($_POST["first_name"]))){
			$first_name_err = "Please enter your first name.";     
		} else{
			$first_name = trim($_POST["first_name"]);
		}
		
		if(empty(trim($_POST["last_name"]))){
			$last_name_err = "Please enter your last name.";     
		} else{
			$last_name = trim($_POST["last_name"]);
		}
		
		if(empty(trim($_POST["nickname"]))){
			$nickname_err = "Please enter a nickname.";     
		} else{
			$nickname = trim($_POST["nickname"]);
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
    <meta charset="UTF-8">
    <title>Snickr Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; background-color: #0000;}
		
		.wrapper {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
            color: #017572;
         }

         .wrapper .wrapper-heading,
         .wrapper .checkbox {
            margin-bottom: 10px;
         }

         .wrapper .checkbox {
            font-weight: normal;
         }

         h2{
            text-align: center;
         }
		 
		 html { 
			background: url('https://images.pexels.com/photos/19670/pexels-photo.jpg?auto=compress&cs=tinysrgb&dpr=2&w=500') no-repeat center center fixed; 
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block text-danger"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block text-danger"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block text-danger"><?php echo $confirm_password_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>">
                <span class="help-block text-danger"><?php echo $first_name_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>">
                <span class="help-block text-danger"><?php echo $last_name_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($nickname_err)) ? 'has-error' : ''; ?>">
                <label>Nickname <span><?php echo "(name that others see)"; ?></span> </label>
                <input type="text" name="nickname" class="form-control" value="<?php echo $nickname; ?>">
                <span class="help-block text-danger"><?php echo $nickname_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="index.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>