<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';          // Set the SMTP server
    $mail->SMTPAuth = true;                  // Enable SMTP authentication
    $mail->Username = 'ambrad144@gmail.com'; // Your Gmail address
    $mail->Password = 'qxhi wmgs njbd vypy';   // Your Gmail app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port = 587;                       // TCP port for TLS

    // Recipients
    $mail->setFrom('ambrad144@gmail.com', 'Your Name');
    $mail->addAddress('ambrad144@gmail.com', 'Recipient Name'); // Add a recipient

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = '<h1>Hello!</h1><p>This is a test email sent using PHPMailer.</p>';
    $mail->AltBody = 'Hello! This is a test email sent using PHPMailer.';

    // Send email
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
