<?php
require 'vendor/autoload.php';

session_start();

// Check if appointment ID exists in the session
if (!isset($_SESSION['appointment_id'])) {
    die("No appointment ID found. Please go back and try again.");
}

$appointment_id = $_SESSION['appointment_id'];

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

// Fetch appointment details
$sql = "SELECT 
            owner_name,
            owner_email,
            owner_phone,
            owner_address,
            pet_name,
            pet_breed,
            pet_age,
            pet_gender,
            selected_services,
            selected_date,
            selected_time
        FROM appointments
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

// Default values in case data is missing
$appointment = [
    "owner_name" => "Not provided",
    "owner_email" => "Not provided",
    "owner_phone" => "Not provided",
    "owner_address" => "Not provided",
    "pet_name" => "Not provided",
    "pet_breed" => "Not provided",
    "pet_age" => "Not provided",
    "pet_gender" => "Not provided",
    "selected_services" => "Not selected",
    "selected_date" => "Not selected",
    "selected_time" => "Not selected",
];

if ($result && $result->num_rows > 0) {
    $appointment = $result->fetch_assoc();
}

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Send email function
function sendConfirmationEmail($appointment) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ambrad144@gmail.com'; // Replace with your Gmail
        $mail->Password = 'qxhi wmgs njbd vypy';   // Replace with your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('ambrad144@gmail.com', 'FuReserve'); // Sender email and name
        $mail->addAddress($appointment['owner_email'], $appointment['owner_name']); // Recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Appointment Confirmation';
        $mail->Body = "
            <h2>Appointment Confirmed</h2>
            <p>Dear {$appointment['owner_name']},</p>
            <p>Your appointment for grooming has been confirmed with the following details:</p>
            <ul>
                <li><strong>Pet's Name:</strong> {$appointment['pet_name']}</li>
                <li><strong>Service:</strong> {$appointment['selected_services']}</li>
                <li><strong>Date:</strong> {$appointment['selected_date']}</li>
                <li><strong>Time:</strong> " . date("h:i A", strtotime($appointment['selected_time'])) . "</li>
            </ul>
            <p>Thank you for choosing FuReserve!</p>
        ";

        $mail->send();
        echo 'Confirmation email has been sent.';
    } catch (Exception $e) {
        echo "Email could not be sent. Error: {$mail->ErrorInfo}";
    }
}

// Send confirmation email
sendConfirmationEmail($appointment);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FuReserve - Confirmation</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            lightGreen: '#88d39a',
            darkGreen: '#2b8446',
            buttonGreen: '#2b8446',
            buttonHoverGreen: '#66b32f',
          },
        },
      },
    };
  </script>
</head>
<body class="bg-lightGreen font-sans">

  <!-- Confirmation Box -->
  <div class="max-w-4xl mx-auto mt-6 mb-6 p-8 bg-white text-gray-800 rounded-xl shadow-lg text-center">
    <h2 class="text-3xl font-bold mb-6 text-darkGreen">Appointment Confirmed</h2>
    
    <!-- Confirmation Details -->
    <div class="space-y-4 text-lg mb-6">
      <p><strong>Pet Owner Name:</strong> <span class="text-darkGreen"><?= htmlspecialchars($appointment['owner_name']); ?></span></p>
      <p><strong>Email:</strong> <span class="text-darkGreen"><?= htmlspecialchars($appointment['owner_email']); ?></span></p>
      <p><strong>Phone:</strong> <span class="text-darkGreen"><?= htmlspecialchars($appointment['owner_phone']); ?></span></p>
      <p><strong>Address:</strong> <span class="text-darkGreen"><?= htmlspecialchars($appointment['owner_address']); ?></span></p>
      <p><strong>Pet's Name:</strong> <span class="text-darkGreen"><?= htmlspecialchars($appointment['pet_name']); ?></span></p>
      <p><strong>Pet's Breed:</strong> <span class="text-darkGreen"><?= htmlspecialchars($appointment['pet_breed']); ?></span></p>
      <p><strong>Pet's Age:</strong> <span class="text-darkGreen"><?= htmlspecialchars($appointment['pet_age']); ?></span></p>
      <p><strong>Pet's Gender:</strong> <span class="text-darkGreen"><?= htmlspecialchars($appointment['pet_gender']); ?></span></p>
      <p><strong>Selected Service:</strong> <span class="text-darkGreen"><?= htmlspecialchars($appointment['selected_services']); ?></span></p>
      <p><strong>Date:</strong> <span class="text-darkGreen"><?= htmlspecialchars($appointment['selected_date']); ?></span></p>
      <p><strong>Time:</strong> <span class="text-darkGreen"><?= htmlspecialchars(date("h:i A", strtotime($appointment['selected_time']))); ?></span></p>
    </div>

    <!-- Button Container -->
    <div class="flex justify-center gap-6 mt-8">
      <a href="pet-owner-info.php" 
         class="bg-buttonGreen text-white font-bold py-3 px-6 rounded hover:bg-buttonHoverGreen transition-transform transform hover:scale-105 shadow">
        Make another appointment?
      </a>
      <a href="homepage.php" 
         class="bg-buttonGreen text-white font-bold py-3 px-6 rounded hover:bg-buttonHoverGreen transition-transform transform hover:scale-105 shadow">
        Home
      </a>
    </div>
  </div>

</body>
</html>
