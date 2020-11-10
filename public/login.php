<?php
session_start();

require "../src/functions.php";

$username = $username_err = $password_err = "";
 
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
    header("location: welcome.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){    
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $id = get_user_id($username);

    if(isset($_POST["id_token"])) {
        google_sign_in($_POST["id_token"]);
        header("location: welcome.php");
        exit;
	}

    if(isset($_POST["access_token"])) {
        facebook_sign_in($_POST["access_token"]);
        header("location: welcome.php");
        exit;
	}   

    if (empty($username)) {
        $username_err = "Please enter an email address.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $username_err = "Email address is invalid.";
    } elseif (is_null($id)) {
        $username_err = "No account found with that username.";
	}
    
    if(empty($password)){
        $password_err = "Please enter your password.";
    }
    
    if(empty($username_err) && empty($password_err)){
        if (is_password_correct($username, $password)) {
            login($id, $username);
            header("location: welcome.php");
            exit;
		} else {
            $password_err = "The password you entered was not valid.";
		}
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="google-signin-client_id" content="<?php echo GOOGLE_CLIENT_ID ?>">

    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">  
    <style>
        #socials {
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
		}
        #spinner {
            height: 40px;
		}
    </style>
</head>
<body>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v8.0&appId=<?php echo FACEBOOK_APP_ID?>" nonce="GXe5LCyF"></script>

    <div class="col-10 col-sm-8 col-md-6 col-lg-4 mx-auto mt-5">
        <div class="card">
            <div class="card-body">
                <h2>Login</h2>
                <p>Please fill in your credentials to login.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                    <div class="form-group">
                        <label>Password</label>
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
                        <input type="submit" class="btn btn-success btn-block" value="Login">
                    </div>
                    <p class="text-center">
                        <a href="forgotten-password.php">Forgot your password?</a>
                    </p>
                </form>

                <hr/>

                <div id="socials">
                    <div
                        class="g-signin2 mb-2"
                        data-scope='profile email'
                        data-height='40'
                        data-width='250'
                        data-longtitle='true'
                        data-theme='dark'
                        data-onsuccess="googleSignIn"
                    >
                    </div>
                    
                    <div id="spinner">
                        <div class="spinner-border text-primary loading mr-3" role="status"></div>
                        <span class="loading text-primary font-weight-bold">Loading Facebook...</span>
                        <div 
                            class="fb-login-button" 
                            data-size="large"
                            data-width="250"
                            data-button-type="continue_with"
                            data-layout="default" 
                            data-auto-logout-link="true" 
                            data-use-continue-as="false" 
                            onlogin="fbSignIn()"
                            data-scope="public_profile,email"
                        >
                        </div>
                    </div>

                    <a 
                        class="btn btn-secondary mt-2 font-weight-bold" 
                        href="register.php" 
                        style="width: 250px;"
                    >
                        Create Account
                    </a>
                </div>                
            </div>            
        </div>
    </div>    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://apis.google.com/js/platform.js"></script>
    <script src="./login.js"></script>
</body>
</html>