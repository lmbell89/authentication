<?php
session_start();

require "../src/functions.php";

$password_err = $confirm_password_err = "";    

if (!isset($_REQUEST["token"])) {
    header("location: login.php");
    exit;
}

if (!is_reset_token_valid($_REQUEST["token"])) {
    $_SESSION["flash"] = ["That reset link has expired", "danger"];
    header("location: forgotten-password.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if ($password !== $confirm_password) {
        $password_err = "Passwords do not match";
	}
    
    if (!is_password_valid($password)) {
        $password_err = $invalid_password_msg;
    }

    if (!is_password_valid($confirm_password)) {
        $confirm_password_err = $invalid_password_msg;
    }  

    if (empty($password_err) && empty($confirm_password_err)) {
        set_password($id, $password);
        header("Location: login.php");
        exit;
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
    <div class="col-10 col-sm-8 col-md-6 col-lg-4 mx-auto mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Reset Password</h2>
                <p>Please fill out this form to reset your password.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post"> 
                    <div class="form-group">
                        <label>New Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            class="form-control <?php echo ($password_err) ? 'is-invalid' : ''; ?>"
                        >
                        <div class="invalid-feedback">
                            <?php echo $password_err; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input 
                            type="password" 
                            name="confirm_password" 
                            class="form-control <?php echo ($confirm_password_err) ? 'is-invalid' : ''; ?>"
                        >
                        <div class="invalid-feedback">
                            <?php echo $confirm_password_err; ?>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                </form>  
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>
</html>