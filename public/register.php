<?php
require "../src/functions.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    if (!filter_var(trim($_POST["username"]), FILTER_VALIDATE_EMAIL)) {
        $username_err = "Please enter a valid email.";   
    } else {
        try {
            $existing_id = get_user_id(trim($_POST["username"]));
            if ($existing_id) {
                $username_err = "That email address is already in use.";
			} else {
                $username = trim($_POST["username"]);     
			}
		} catch (Exception $e) {
            echo $e;
		}
    }

    if (trim($_POST["password"]) !== trim($_POST["confirm_password"])) {
        $password_err = "Passwords do not match";
	}
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif ( !is_password_valid(trim($_POST["password"])) ) {
        $password_err = password_error();
    } else {
        $password = trim($_POST["password"]);
	}
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $password_err = "Please enter a password.";     
    } elseif ( !is_password_valid(trim($_POST["confirm_password"])) ) {
        $password_err = password_error();
    }

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        try {
            create_user($username, $password);
            header("Location: login.php");
		} catch (Exception $e) {
            echo $e;  
		}
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">  
</head>
<body>
    <div class="col-10 col-sm-8 col-md-6 col-lg-4 mx-auto mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Sign Up</h2>
                <p>Please fill this form to create an account.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Email</label>
                        <input 
                            type="text" 
                            name="username" 
                            class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $username; ?>"
                        >
                        <div class="invalid-feedback">
                            <?php echo $username_err; ?>
                        </div>
                    </div>    
                    <div class="form-group">
                        <label>Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $password; ?>"
                        >
                        <div class="invalid-feedback">
                            <?php echo $password_err; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input 
                            type="password" 
                            name="confirm_password" 
                            class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $confirm_password; ?>"
                        >
                        <div class="invalid-feedback">
                            <?php echo $confirm_password_err; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <input type="reset" class="btn btn-default" value="Reset">
                    </div>
                    <p>Already have an account? <a href="login.php">Login here</a>.</p>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>