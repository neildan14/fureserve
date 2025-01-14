<?php
session_start();

// Ensure the appointment ID is available
if (!isset($_SESSION['appointment_id'])) {
    die("No appointment ID found. Please start from the beginning.");
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

// Fetch unavailable dates and times, excluding the current appointment
$sql = "SELECT selected_date, selected_time FROM appointments WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

$unavailableSlots = [];
while ($row = $result->fetch_assoc()) {
    $unavailableSlots[$row['selected_date']][] = $row['selected_time'];
}

$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedDate = $_POST['date'] ?? '';
    $selectedTime = $_POST['time'] ?? '';

    if (!empty($selectedDate) && !empty($selectedTime)) {
        // Check if the selected slot is already taken
        $sql = "SELECT COUNT(*) AS count FROM appointments WHERE selected_date = ? AND selected_time = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $selectedDate, $selectedTime, $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['count'] > 0) {
            echo "<script>alert('The selected date and time are unavailable. Please choose a different slot.');</script>";
            echo "<script>window.location.href = 'select-date-time.php';</script>";
            exit();
        }

        // Update the selected date and time in the database
        $sql = "UPDATE appointments SET selected_date = ?, selected_time = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $selectedDate, $selectedTime, $appointment_id);

        try {
            if ($stmt->execute()) {
                echo "<script>alert('Date and time saved successfully!');</script>";
                header("Location: summary-of-appointment.php");
                exit();
            } else {
                throw new Exception("Error saving date and time. Please try again.");
            }
        } catch (Exception $e) {
            echo "<script>alert('" . $e->getMessage() . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please select both a date and a time.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FuReserve - Select Date and Time</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#88d39a] font-sans min-h-screen flex flex-col items-center justify-center">

  <a href="service-customization.php" 
     class="absolute top-4 left-5 bg-[#99cc66] text-[#2b8446] px-4 py-2 rounded-md font-bold hover:bg-[#388e3c] text-sm md:text-base">Back</a>

  <div class="fixed top-4 right-5 w-32 h-8 bg-gray-300 rounded-md shadow-md flex items-center justify-center font-bold text-white">
    <div class="bg-[#2b8446] w-full h-full text-center leading-8 rounded-md">Step 3 of 4</div>
  </div>

  <div class="container mx-auto mt-10 p-6 max-w-4xl bg-[#2b8446] text-white rounded-lg shadow-lg text-center">
    <div class="flex flex-col md:flex-row justify-center items-center gap-3 mb-4">
      <img src="assets/images/logo.png" alt="Paw Icon" class="w-12 h-12">
      <h1 class="text-2xl md:text-3xl font-bold">FuReserve</h1>
    </div>

    <div class="bg-[#66b32f] font-bold py-2 px-4 rounded-md mb-6 text-lg md:text-xl">SELECT DATE and TIME</div>

    <form id="dateTimeForm" action="select-date-time.php" method="POST" class="flex flex-col md:flex-row justify-between items-stretch gap-6">
      <div class="w-full md:w-1/2 flex flex-col">
        <label for="date-input" class="block text-sm md:text-lg mb-2">Select Date:</label>
        <input type="date" id="date-input" name="date" 
               class="w-full p-2 text-sm md:text-lg rounded-md border-gray-300 text-black focus:ring-2 focus:ring-[#66b32f] focus:outline-none" required>
               <label for="date-input" class="block text-sm md:text-lg mb-2">(Weekends are not available for booking)</label>
      </div>

      <div class="bg-[#88d39a] rounded-md p-4 w-full md:w-1/2 flex flex-col">
        <label for="time-input" class="text-sm md:text-lg mb-2">Select Time:</label>
        <select id="time-input" name="time" 
                class="w-full p-2 text-sm md:text-lg rounded-md border-gray-300 text-black focus:ring-2 focus:ring-[#66b32f] focus:outline-none" required>
          <option value="" disabled selected>Select a time</option>
          <?php
            $timeSlots = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00"];
            foreach ($timeSlots as $slot) {
                $time = date("h:i A", strtotime($slot)); // Convert to 12-hour format with AM/PM
                echo "<option value='$slot'>$time</option>";
            }
            ?>
        </select>
      </div>
    </form>

    <div class="flex justify-center mt-6">
      <button type="button" 
              onclick="validateForm()"
              class="px-8 py-3 bg-[#99cc66] text-white text-sm md:text-lg font-bold rounded-md hover:bg-[#66b32f] focus:outline-none focus:ring-2 focus:ring-green-700 transition">
        CONFIRM DATE and TIME
      </button>
    </div>
  </div>

  <script>
    const dateInput = document.getElementById("date-input");

    // Define the allowed year
    const allowedYear = 2025;

    // Get today's date
    const today = new Date();
    const year = today.getFullYear();
    const month = (today.getMonth() + 1).toString().padStart(2, "0");
    const day = today.getDate().toString().padStart(2, "0");

    // Ensure the minimum date is today if we're in the allowed year
    const minDate = (year === allowedYear) ? `${year}-${month}-${day}` : `${allowedYear}-01-01`;

    // Define the maximum date as the last day of 2025
    const maxDate = `${allowedYear}-12-31`;

    // Set the min and max attributes dynamically
    dateInput.min = minDate;
    dateInput.max = maxDate;

    const unavailableSlots = <?= json_encode($unavailableSlots); ?>;
    const timeInput = document.getElementById("time-input");

    // Add event listener for changes in the date input
    dateInput.addEventListener("change", () => {
        const selectedDate = dateInput.value;
        const selectedDateObj = new Date(selectedDate);
        const selectedYear = selectedDateObj.getFullYear();
        const selectedDay = selectedDateObj.getDay();
        const options = timeInput.options;

        // Ensure the selected year is 2025
        if (selectedYear !== allowedYear) {
            alert(`Only dates in the year ${allowedYear} are allowed. Please select a valid date.`);
            dateInput.value = "";
            return;
        }

        // Check if the selected date is in the past
        const currentDate = new Date(`${year}-${month}-${day}`);
        if (selectedDateObj < currentDate) {
            alert("Past dates are not allowed. Please select a valid date.");
            dateInput.value = "";
            return;
        }

        // Disable weekends
        if (selectedDay === 0 || selectedDay === 6) {
            alert("Weekends are not available for booking.");
            dateInput.value = "";
            return;
        }

        // Reset time options
        for (let i = 0; i < options.length; i++) {
            options[i].disabled = false;
        }

        // Disable unavailable slots
        for (let i = 0; i < options.length; i++) {
            const timeValue = options[i].value;
            const isUnavailable = unavailableSlots[selectedDate]?.includes(timeValue) || false;
            const isPastTime = 
                selectedDate === `${year}-${month}-${day}` &&
                today.getHours() >= parseInt(timeValue.split(":")[0]);

            if (isUnavailable || isPastTime) {
                options[i].disabled = true;
            }
        }
    });

    function validateForm() {
        const dateValue = dateInput.value;
        const timeValue = timeInput.value;

        if (!dateValue || !timeValue) {
            alert("Please select both a date and a time.");
        } else {
            document.getElementById("dateTimeForm").submit();
        }
    }
</script>

</body>
</html>
