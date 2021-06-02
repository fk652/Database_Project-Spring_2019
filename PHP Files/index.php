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
	 
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
	 
		// Check if email is empty
		if(empty(trim($_POST["email"]))){
			$email_err = "Please enter email.";
		} else{
			$email = trim($_POST["email"]);
		}
		
		// Check if password is empty
		if(empty(trim($_POST["password"]))){
			$password_err = "Please enter your password.";
		} else{
			$password = trim($_POST["password"]);
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
    <meta charset="UTF-8">
    <title>Snickr Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 15px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
		
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

         .wrapper .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
         }

         .wrapper .form-control:focus {
            z-index: 2;
         }

         h2{
            text-align: center;
         }
		 
    </style>
</head>
<body style="background-image:url(https://images.pexels.com/photos/265047/pexels-photo-265047.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500);
		background-repeat:no-repeat;overflow-x:hidden;background-position: top center; 
		background-attachment: fixed;">
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" placeholder="email" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" placeholder="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="registration.php">Sign up now</a>.</p>
        </form>
    </div>    
</body>
</html>