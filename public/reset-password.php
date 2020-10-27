<?php
session_start();

require_once '../config/config.php';
require "../src/functions.php";

global $pdo;
$password = $confirm_password = "";
$password_err = $confirm_password_err = "";

if (!isset($_REQUEST["token"])) {
    header("location: login.php");
}

$sql = "SELECT id, token_expiry FROM users WHERE reset_token = :token";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(":token", $_REQUEST["token"], PDO::PARAM_STR);

if (!$stmt->execute()) {
    throw new Exception("Failed to retrieve token");
}

if ($stmt->rowCount() == 0) {
    $_SESSION["flash"] = ["That reset link has expired", "warning"];
    header("location: forgotten-password.php");
    exit;
}

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$expiry = $row["token_expiry"];
$id = $row["id"];

if ($expiry > time()) {
    $token_err = "Password reset token has expired";
}


if($_SERVER["REQUEST_METHOD"] == "POST"){

     // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif ( !is_password_valid(trim($_POST["password"])) ) {
        $password_err = "Passwords must be at least 8 characters
            and contain a mix of uppercase, lowercase, numbers and symbols.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif ( !is_password_valid(trim($_POST["password"])) ) {
        $password_err = "Passwords must be at least 8 characters
            and contain a mix of uppercase, lowercase, numbers and symbols.";
    } else{
        $password = trim($_POST["password"]);
    }    

    if(empty($password_err) && empty($confirm_password_err)){
        try {
            set_password($id, $password);
            header("Location: login.php");
		} catch (Exception $e) {
            echo $e;  
		}
    }

    if(empty($token_err) && empty($new_password_err) && empty($confirm_password_err)){
        try {
            set_password($id, $password);
            header("location: login.php");
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
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>New Password</label>
                        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control">
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a class="btn btn-link" href="welcome.php">Cancel</a>
                    </div>
                </form>  
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>