<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Use forward slashes in the file paths
require 'PHPMailer-master\src\Exception.php';
require 'PHPMailer-master\src\PHPMailer.php';
require 'PHPMailer-master\src\SMTP.php';

$mail = new PHPMailer(true);

// Uncomment the line below if you want to enable SMTP debugging
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com"; // Replace with your SMTP server address
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // You may need to adjust this based on your server settings
$mail->Port = 587; // Specify the appropriate port for your SMTP server
$mail->Username = "vijaykmr2422@gmail.com"; // Replace with your SMTP username
$mail->Password = "sdgsstvteyjydgan"; // Replace with your SMTP password

$mail->isHTML(true);

return $mail;
