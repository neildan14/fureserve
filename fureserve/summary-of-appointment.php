<?php
session_start();

if (!isset($_SESSION['appointment_id'])) {
    die("No appointment ID found.");
}

$appointment_id = $_SESSION['appointment_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fureserve";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

$appointment = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FuReserve - Summary of Appointment</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans bg-[#88d39a]">

<!-- Fixed Progress Bar -->
<div class="fixed top-2 right-2 w-32 h-8 bg-gray-300 rounded-md shadow-lg flex items-center justify-center text-white font-bold text-sm z-50">
  <div class="w-full h-full bg-[#2b8446] flex items-center justify-center rounded-md">
    Step 4 of 4
  </div>
</div>

<div class="max-w-xl mx-auto mt-4 sm:mt-8 p-4 sm:p-6 bg-[#2b8446] text-white rounded-xl">
  <!-- Back Button -->
  <a href="select-date-time.php" class="absolute top-4 left-4 bg-[#99cc66] text-[#2b8446] px-3 py-2 rounded-md font-bold hover:bg-[#388e3c] text-xs sm:text-sm md:text-base">
    Back
  </a>

  <!-- Header -->
  <h2 class="text-xl sm:text-2xl font-bold mb-4 text-center">Summary of Your Appointment</h2>

  <!-- Appointment Summary -->
  <div class="bg-white text-gray-800 p-4 sm:p-6 rounded-lg shadow-lg mt-6">
    <h3 class="text-lg sm:text-xl font-bold text-[#2b8446] mb-4 text-center">Appointment Summary</h3>

    <div class="space-y-2">
      <p class="text-sm sm:text-base"><span class="font-bold">Pet Owner Name:</span> <?= htmlspecialchars($appointment['owner_name']); ?></p>
      <p class="text-sm sm:text-base"><span class="font-bold">Email:</span> <?= htmlspecialchars($appointment['owner_email']); ?></p>
      <p class="text-sm sm:text-base"><span class="font-bold">Phone:</span> <?= htmlspecialchars($appointment['owner_phone']); ?></p>
      <p class="text-sm sm:text-base"><span class="font-bold">Address:</span> <?= htmlspecialchars($appointment['owner_address']); ?></p>
      <p class="text-sm sm:text-base"><span class="font-bold">Pet's Name:</span> <?= htmlspecialchars($appointment['pet_name']); ?></p>
      <p class="text-sm sm:text-base"><span class="font-bold">Pet's Breed:</span> <?= htmlspecialchars($appointment['pet_breed']); ?></p>
      <p class="text-sm sm:text-base"><span class="font-bold">Pet's Age:</span> <?= htmlspecialchars($appointment['pet_age']); ?></p>
      <p class="text-sm sm:text-base"><span class="font-bold">Pet's Gender:</span> <?= htmlspecialchars($appointment['pet_gender']); ?></p>
      <p class="text-sm sm:text-base"><span class="font-bold">Selected Service:</span> <?= htmlspecialchars($appointment['selected_services']); ?></p>
      <p class="text-sm sm:text-base"><span class="font-bold">Date:</span> <?= htmlspecialchars($appointment['selected_date']); ?></p>
      <p class="text-sm sm:text-base"><span class="font-bold">Time:</span> 
        <?= htmlspecialchars(date("h:i A", strtotime($appointment['selected_time']))); ?>
      </p>
    </div>

    <!-- Confirm Appointment Button -->
    <div class="mt-6">
      <a href="confirmation.php" class="block bg-[#99cc66] text-white text-center text-sm sm:text-base font-bold py-3 px-4 sm:px-6 rounded-lg shadow-lg hover:bg-[#66b32f] transition duration-300">
        Confirm Appointment
      </a>
    </div>
  </div>
</div>

</body>
</html>
