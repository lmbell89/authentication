<?php
session_start();
require "../src/functions.php";
login_required();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google-signin-client_id" content="<?php echo GOOGLE_CLIENT_ID ?>">

    <title>Liam Bell</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">  
</head>
<body>
    <div class="col-10 col-sm-8 col-md-6 col-lg-4 mx-auto mt-5">
        <div class="card">
            <div class="card-body">
                <h1>Logged in as</h1>
                <h3><?php echo htmlspecialchars($_SESSION["username"]);?></h3>

                <p>This page is only accessible when logged in.</p>
                <p>
                    If you try to access this page without logging in 
                    you will be redirected to login.php
                </p>

                <a href="change-password.php" class="btn btn-warning btn-block">Change Your Password</a>
                <button class="btn btn-danger btn-block" onclick="signOut()">Sign Out</button> 
            </div>
        </div>
    </div>

    <script src="https://apis.google.com/js/platform.js"></script>
    <script src="./login.js"></script>
</body>
</html>