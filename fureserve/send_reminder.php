<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fureserve";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch confirmed appointments happening in the next 3 hours
$sql = "SELECT 
            owner_name, 
            owner_email, 
            pet_name, 
            selected_services, 
            selected_date, 
            selected_time 
        FROM appointments 
        WHERE status = 'confirmed' 
        AND TIMESTAMP(selected_date, selected_time) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 3 HOUR)";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($appointment = $result->fetch_assoc()) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ambrad144@gmail.com';
            $mail->Password = 'qxhi wmgs njbd vypy';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('ambrad144@gmail.com', 'FuReserve');
            $mail->addAddress($appointment['owner_email'], $appointment['owner_name']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Upcoming Appointment Reminder';
            $mail->Body = "
                <h2>Appointment Reminder</h2>
                <p>Dear {$appointment['owner_name']},</p>
                <p>This is a friendly reminder for your upcoming grooming appointment. Here are the details:</p>
                <ul>
                    <li><strong>Pet's Name:</strong> {$appointment['pet_name']}</li>
                    <li><strong>Service:</strong> {$appointment['selected_services']}</li>
                    <li><strong>Date:</strong> {$appointment['selected_date']}</li>
                    <li><strong>Time:</strong> " . date("h:i A", strtotime($appointment['selected_time'])) . "</li>
                </ul>
                <p>We look forward to seeing you and your pet!</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            echo "Reminder email could not be sent to {$appointment['owner_email']}. Error: {$mail->ErrorInfo}";
        }
    }
} else {
    echo "No upcoming confirmed appointments within the next 3 hours.";
}

$conn->close();
?>
