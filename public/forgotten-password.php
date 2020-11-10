<?php
session_start();
require "../src/functions.php";

$username = $username_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);

    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $username_err = "Email address is not valid.";
    } elseif (empty(get_user_id($username))) {
        $username_err = "Account not found.";  
	} else {
        send_password_reset($username);            
        $_SESSION["flash"] = ["Password reset link sent!", "success"];
        header("location:" . $_SERVER["REQUEST_URI"], true, 303); //PRG
        exit;
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
                <h2>Forgotten Password?</h2>
                
                <?php echo flash(); ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <p>Enter your email address to reset your password.</p>

                    <div class="form-group">
                        <label>Email</label>
                        <input 
                            type="text" 
                            name="username" 
                            class="form-control <?php echo ($username_err) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $username; ?>"
                        >
                        <div class="invalid-feedback">
                            <?php echo $username_err; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-block btn-primary">Send Reset Link</button>
                </form>

                <div class="text-center mt-3">
                    <a href="login.php">Back to login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>