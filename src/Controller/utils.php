<?php
require(__DIR__ . '/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';
// use PHPMailer\PHPMailer\PHPMailer;

function isValidMail($email)
{
	$db = new DatabaseManager;
	if ($db->checkEmailExists($_POST["email"]))
		$_SESSION["error_message"] = 'This email is already used.';
	elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
		$_SESSION["error_message"] = 'Invalid email format.';
	else
		return true;
	return false;
}

function isValidUsername($username)
{
	$db = new DatabaseManager;
	if (strlen($_POST["username"]) < 5){
		$_SESSION["error_message"] = 'The username must be at least 5 characters long.';
	}
	else if (strlen($_POST["username"]) > 12){
		$_SESSION["error_message"] = 'The username can\'t be more than 12 characters long.';
	}
	elseif (preg_match('/[^a-zA-Z0-9]/', $_POST["username"])){
		$_SESSION["error_message"] = 'The username cannot contain special characters.';
	}
	elseif ($db->checkUserExists($username)){
		$_SESSION["error_message"] = 'This username is already taken.';
	}
	else{
		return true;
	}
	return false;
}

function isValidPassword($password) {
	if (strlen($password) < 8 || strlen($password) > 256) {
		return false;
	}
	if (!preg_match('/\d/', $password)) {
		return false;
	}
	return true;
}

function sendMail($recipientEmail, $username, $subject, $content)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = getenv('EMAIL_USERNAME');
        $mail->Password = getenv('APP_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->setFrom(getenv('EMAIL_USERNAME'), '42Camagru');
        $mail->addAddress($recipientEmail, $username);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $content;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}

?>