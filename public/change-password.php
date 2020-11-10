<?php
session_start();
require "../src/functions.php";
login_required();

$current_password_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = trim($_POST["current_password"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $username = $_SESSION["username"];

    if (!is_password_correct($username, $current_password)) {
        $current_password_err = "That password is not correct.";
	}

    if ($password !== $confirm_password) {
        $password_err = "Passwords do not match";
	}
    
    if (!is_password_valid($password)) {
        $password_err = $invalid_password_msg;
    }

    if (!is_password_valid($confirm_password)) {
        $confirm_password_err = $invalid_password_msg;
    }

    if (empty($current_password_err) && empty($password_err) && empty($confirm_password_err)) {
        set_password($username, $password);
        header("Location: welcome.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
    <div class="col-10 col-sm-8 col-md-6 col-lg-4 mx-auto mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Change Password</h2>        
                <p>Fill out this form to change your password.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input 
                            type="password" 
                            name="current_password" 
                            class="form-control <?php echo ($current_password_err) ? 'is-invalid' : ''; ?>"
                        >
                        <div class="invalid-feedback">
                            <?php echo $current_password_err; ?>
                        </div>
                    </div>
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
                    <input type="submit" class="btn btn-primary btn-block" value="Submit">
                </form>  
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>