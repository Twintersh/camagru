<?php
require(__DIR__ . '/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';
// use PHPMailer\PHPMailer\PHPMailer;

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