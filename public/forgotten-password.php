<?php
require "../src/functions.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"])) {
        $id = get_user_id(trim($_POST["username"]));

        if (empty($id)) {
            $_SESSION["flash"] = ["Account not found", "danger"];  
		} else {
            send_password_reset(trim($_POST["username"]));            
            $_SESSION["flash"] = ["Password reset link sent!", "success"];

            // use PRG pattern to prevent unnecessary emails
            header("location:" . $_SERVER["REQUEST_URI"], true, 303);
            exit;
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
                <h2>Forgotten Password?</h2>        
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <?php echo flash("success");?>
                    <p>Enter your email address to reset your password.</p>
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label>Email</label>
                        <input type="text" name="username" class="form-control">                    
                    </div>
                    <button type="submit" class="btn btn-block btn-primary">Send Reset Link</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>