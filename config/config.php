<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../../../vendor/autoload.php';

// used to append the password reset link to
define('EXTERNAL_BASE_URL', 'https://liambell.info');

// SQL details
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', '');

// social media login details
define('GOOGLE_CLIENT_ID', '000000000000-aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.apps.googleusercontent.com');
define('FACEBOOK_APP_ID', '999999999999999');
define('FACEBOOK_APP_SECRET', '00aa00aa00aa00aa00aa00aa00aa00aa');

// smtp details
define('SMTP_HOST', '');
define('SMTP_PORT', );
define('SMTP_AUTH', false);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS); // see https://github.com/PHPMailer/PHPMailer for alternatives
define('SMTP_EXCEPTIONS', true);
define('SMTP_DEFAULT_ADDRESS', 'info@liambell.info');
define('SMTP_DEFAULT_NAME', null);

$mail = new PHPMailer(SMTP_EXCEPTIONS);
$mail->isSMTP();
$mail->Host = SMTP_HOST;
$mail->SMTPAuth = SMTP_AUTH;
$mail->Username = SMTP_USERNAME;
$mail->Password = SMTP_PASSWORD;
$mail->SMTPSecure = SMTP_ENCRYPTION;
$mail->Port = SMTP_PORT;   

try{
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>