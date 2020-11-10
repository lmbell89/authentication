<?php
require_once '../config/config.test.php';

function flash(): string {
    $flash = null;

    if (isset($_SESSION["flash"])) {
        list($message, $variant) = $_SESSION["flash"];
        $color = empty($variant) ? "" : "alert-$variant";
        $flash = "<div class='alert $color' role='alert'>$message</div>";        
	}

    unset($_SESSION["flash"]); 
    return $flash ?? "";
}

$invalid_password_msg = "Passwords must be at least 8 characters long
     and contain a mix of uppercase, lowercase, numbers and symbols.";

function is_password_valid(
    $password, 
    $min_length = 8, 
    $min_letters = 1, 
    $min_numbers = 1, 
    $min_symbols = 1, 
    $mixed_cases = false
): bool {
    $valid = true;

    $count_numbers = preg_match_all("/[0-9]/", $password);
    $count_letters = preg_match_all("/[\p{L}]/", $password);
    $count_symbols = preg_match_all("/[^\p{L}\p{N}]/", $password);

    if ($mixed_cases && strtoupper($password) == $password) {
        $valid = false;
	}
    if ($mixed_cases && strtolower($password) == $password) {
        $valid = false;
	}

    if (
        strlen($password) < $min_length
        || $count_letters < $min_letters 
        || $count_symbols < $min_symbols
        || $count_numbers < $min_numbers
    ) { 
        $valid = false;
	}

    return $valid;
}


function get_user_id($username): ?string {
    global $pdo;
    $sql = "SELECT id FROM users WHERE username = :username";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);            
            
    if($stmt->execute()){
        return $stmt->fetch(PDO::FETCH_ASSOC)["id"] ?? null;
    } else {
        throw new Exception("Failed to select users");
    }
}


function create_user($username, $password): string {
    global $pdo;
    $sql = "INSERT INTO users (username, password, created_at) 
        VALUES (:username, :password, :created_at)";

    $stmt = $pdo->prepare($sql);

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $timestamp = time();

    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->bindParam(":password", $hash, PDO::PARAM_STR);
    $stmt->bindParam(":created_at", $timestamp, PDO::PARAM_STR);

    if($stmt->execute()){
        return $pdo->lastInsertId();
    } else {
        throw new Exception("Failed to insert user");
	}
}

function login_required() {
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false) {
        $_SESSION["next"] = htmlspecialchars($_SERVER["REQUEST_URI"]);
        header("location: login.php");
        exit;
	}    
}


function login($user_id, $user_email): void {
    $_SESSION["loggedin"] = true;
    $_SESSION["id"] = $user_id;
    $_SESSION["username"] = $user_email;

    if (isset($_SESSION["next"])) {
        header("location: " . $_SESSION["next"]);
        unset($_SESSION["next"]);
        exit;
	}
}


function is_password_correct($username, $password): bool {
    global $pdo;
    $sql = "SELECT password FROM users WHERE username = :username";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);

    if (!$stmt->execute()) {
        throw new Exception("Failed to select from users table");
	}

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $is_correct = password_verify($password, $user["password"]);

    return $is_correct;
}


function google_sign_in($id_token): void {
    $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);
    $payload = $client->verifyIdToken($id_token);

    if ($payload && $payload['aud'] === GOOGLE_CLIENT_ID) {
        $username = $payload['email'];
        $password = null;

        $id = get_user_id($username) ?? create_user($username, $password);
        login($id, $username);
    } else {
        throw new Exception("Invalid ID token");
    }
}


function facebook_sign_in($access_token): void {
    $fb = new Facebook\Facebook([
        'app_id' => FACEBOOK_APP_ID,
        'app_secret' => FACEBOOK_APP_SECRET,
        'default_graph_version' => 'v8.0',
    ]);

    $response = $fb->get('/me?fields=email', $access_token);

    $graphNode = $response->getGraphNode();

    $username = $graphNode['email'];
    $password = null;

    $id = get_user_id($username) ?? create_user($username, $password);
    login($id, $username);
}


function get_password_reset_token($username): string {
    global $pdo;
    $sql = "SELECT reset_token, token_expiry FROM users WHERE username = :username";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);

    if(!$stmt->execute()){
        throw new Exception("Failed to select from users table");
	}
    if ($stmt->rowCount() == 0) {
        throw new Exception("User not found");
	}

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_token = $row['reset_token'];
    $current_expiry = $row['token_expiry'];

    if ($current_token && $current_expiry && $current_expiry > time() + (60 * 5)) {
        // current token has more than 5 minutes left
        $token = $current_token;
	} else {
        // make new token
        $sql = "UPDATE users SET reset_token = :token, 
            token_expiry = :expiry WHERE username = :username";

        $stmt = $pdo->prepare($sql);        

        $token = bin2hex(random_bytes(16));
        $expiry = time() + (30*60);

        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":token", $token, PDO::PARAM_STR);
        $stmt->bindParam(":expiry", $expiry, PDO::PARAM_STR);

        if(!$stmt->execute()){
            throw new Exception("Failed to create password reset link");
	    }
	}

    return $token;
}

function send_password_reset($username): void {
    global $mail;

    $token = get_password_reset_token($username);
    $link = EXTERNAL_BASE_URL . "reset-password.php?token=$token";

    $html_msg = "<h1>Reset Your Password</h1>
        <p>Click the button below to reset your password:</p>
        <a href='$link' style='padding: 1rem; text-decoration: none; border-radius: 10px; color: #fff; background-color: #28a745; font-weight: bold; font-family: sans-serif;'>Reset Password</a>
        <br />
        <br />
        <p>Button not working?</p>
        <br />
        <p>Copy the following link into your browser to reset your password:</p>
        <a href='$link'>$link</a>";

    $text_msg = "Copy the following link into your browser to reset your password: \r\n $link";

    $mail->setFrom(SMTP_DEFAULT_ADDRESS, SMTP_DEFAULT_NAME);
    $mail->addAddress($username);

    $mail->isHTML(true);
    $mail->Subject = 'Password Reset';
    $mail->Body = $html_msg;
    $mail->AltBody = $text_msg;

    $mail->send();
}


function set_password($username, $password): void {
    global $pdo;
    $sql = "UPDATE users 
        SET password = :hash, reset_token = null, token_expiry = null 
        WHERE username = :username";

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":hash", $hash, PDO::PARAM_STR);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);

    if(!$stmt->execute()){
        throw new Exception("Failed to set password");
    }
}

function is_reset_token_valid($token): bool {
    global $pdo;
    $sql = "SELECT id, token_expiry FROM users WHERE reset_token = :token";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":token", $token, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $valid = ($row === null || $row["token_expiry"] > time());
    return $valid;
}

?>