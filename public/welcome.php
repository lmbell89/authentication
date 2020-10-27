<?php
session_start();
require "../src/auth/functions.php";
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
    <div class="page-header">
        <h1>Hi, <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
    </div>
    <p>This page is only accessible when logged in.</p>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <button class="btn btn-danger" onclick="signOut()">Sign Out</button> 
    </p>

    <script src="https://apis.google.com/js/platform.js"></script>
    <script src="./login.js"></script>
</body>
</html>