<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "fureserve";

    // Sanitize and format inputs
    $owner_name = ucwords(trim($_POST['owner_name']));
    $owner_email = trim($_POST['owner_email']);
    $owner_phone = trim($_POST['owner_phone']);
    $owner_address = ucwords(trim($_POST['owner_address']));
    $pet_name = ucwords(trim($_POST['pet_name']));
    $pet_breed = ucwords(trim($_POST['pet_breed']));
    $pet_age = $_POST['pet_age'];
    $pet_gender = $_POST['pet_gender'];

    // Server-side validation
    if (strlen($owner_name) < 3 || !preg_match('/^[a-zA-Z\s]+$/', $owner_name)) {
        die('Full Name must be at least 3 characters long and contain only letters and spaces.');
    }

    if (strlen($owner_email) < 5 || !preg_match('/^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|icloud\.com|aol\.com)$/', $owner_email)) {
        die('Email must be at least 5 characters long and end with a valid domain.');
    }

    if (strlen($owner_address) < 5 || !preg_match('/^[a-zA-Z0-9\s.,\-#&\/]+$/', $owner_address)) {
        die('Address must be at least 5 characters long and only contain valid characters.');
    }

    if (strlen($pet_name) < 2 || !preg_match('/^[a-zA-Z\s]+$/', $pet_name)) {
        die('Pet\'s Name must be at least 2 characters long and contain only letters and spaces.');
    }

    if (!empty($pet_breed) && strlen($pet_breed) < 2) {
        die('Breed, if provided, must be at least 2 characters long and contain only letters and spaces.');
    }

    if ($pet_age < 1 || $pet_age > 40) {
        die('Pet age must be between 1 and 40 years.');
    }

    // Database connection and insertion
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO appointments (owner_name, owner_email, owner_phone, owner_address, pet_name, pet_breed, pet_age, pet_gender) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssis", $owner_name, $owner_email, $owner_phone, $owner_address, $pet_name, $pet_breed, $pet_age, $pet_gender);

    if ($stmt->execute()) {
        $_SESSION['appointment_id'] = $conn->insert_id;
        header('Location: service-customization.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FUReserve - Pet Grooming Appointment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        lightGreen: '#88d39a',
                        darkGreen: '#2b8446',
                        buttonGreen: '#99cc66',
                        buttonHoverGreen: '#66b32f',
                        errorRed: '#ff5a5a',
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-lightGreen flex flex-col items-center">
    <a href="homepage.php" class="absolute top-4 left-4 bg-buttonGreen text-darkGreen px-4 py-2 rounded font-bold hover:bg-buttonHoverGreen">Back</a>
    <div class="relative max-w-3xl mx-auto mt-8 p-8 bg-darkGreen text-white rounded-lg text-center shadow-lg">

        <h2 class="text-2xl font-bold mb-6">FUReserve Grooming Appointment</h2>

        <form method="POST" action="" onsubmit="return validateForm()" class="space-y-6">
            <!-- Owner Profile -->
            <div class="bg-lightGreen p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4 text-darkGreen">Pet Owner Profile</h3>
                <div>
                    <label for="owner-name" class="block font-medium text-darkGreen">Full Name</label>
                    <input type="text" id="owner-name" name="owner_name" required 
                           placeholder="Ex. Juan Dela Cruz" class="w-full px-4 py-2 rounded border border-gray-300 text-darkGreen capitalize">
                    <p id="owner-name-error" class="text-errorRed text-sm hidden">Full Name must be at least 3 characters long.</p>
                </div>
                <div>
                    <label for="owner-email" class="block font-medium text-darkGreen">Email</label>
                    <input type="email" id="owner-email" name="owner_email" required 
                           placeholder="example@email.com" class="w-full px-4 py-2 rounded border border-gray-300 text-darkGreen">
                    <p id="owner-email-error" class="text-errorRed text-sm hidden">Email must be at least 5 characters long and valid.</p>
                </div>
                <div>
                    <label for="owner-phone" class="block font-medium text-darkGreen">Phone Number</label>
                    <input type="tel" id="owner-phone" name="owner_phone" required 
                           placeholder="Ex. 09123456789" pattern="^09[0-9]{9}$" class="w-full px-4 py-2 rounded border border-gray-300 text-darkGreen">
                </div>
                <div>
                    <label for="owner-address" class="block font-medium text-darkGreen">Address</label>
                    <input type="text" id="owner-address" name="owner_address" required 
                        placeholder="Street, Barangay, City" class="w-full px-4 py-2 rounded border border-gray-300 text-darkGreen capitalize">
                    <p id="owner-address-error" class="text-errorRed text-sm hidden">Address must be at least 5 characters long and valid.</p>
                </div>

            <!-- Pet Information -->
            <div class="bg-lightGreen p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4 text-darkGreen">My Pet Information</h3>
                <div>
                    <label for="pet-name" class="block font-medium text-darkGreen">Pet's Name</label>
                    <input type="text" id="pet-name" name="pet_name" required 
                           placeholder="Ex. Brownie" class="w-full px-4 py-2 rounded border border-gray-300 text-darkGreen capitalize">
                    <p id="pet-name-error" class="text-errorRed text-sm hidden">Pet's Name must be at least 2 characters long.</p>
                </div>
                <div>
                    <label for="pet-breed" class="block font-medium text-darkGreen">Breed</label>
                    <input type="text" id="pet-breed" name="pet_breed" 
                           placeholder="Ex. Aspin/Puspin" class="w-full px-4 py-2 rounded border border-gray-300 text-darkGreen capitalize">
                    <p id="pet-breed-error" class="text-errorRed text-sm hidden">Breed must be at least 2 characters long.</p>
                </div>
                <div>
                    <label for="pet-age" class="block font-medium text-darkGreen">Age</label>
                    <input type="number" id="pet-age" name="pet_age" required 
                           placeholder="Pet's Age (1-40 years)" class="w-full px-4 py-2 rounded border border-gray-300 text-darkGreen" min="1" max="40">
                </div>
                <div>
                    <label for="pet-gender" class="block font-medium text-darkGreen">Gender</label>
                    <select id="pet-gender" name="pet_gender" required class="w-full px-4 py-2 rounded border border-gray-300 text-darkGreen">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="px-6 py-3 bg-buttonGreen text-white font-bold rounded shadow hover:bg-buttonHoverGreen">Submit</button>
        </form>
    </div>

    <script>
        function validateField(fieldId, errorId, pattern, errorMessage, minLength = 0) {
            const field = document.getElementById(fieldId);
            const error = document.getElementById(errorId);
            if (field.value.trim().length < minLength) {
                error.textContent = `Minimum ${minLength} characters required.`;
                error.classList.remove('hidden');
                field.classList.add('border-red-500');
                field.focus();
                return false;
            } else if (!pattern.test(field.value.trim())) {
                error.textContent = errorMessage;
                error.classList.remove('hidden');
                field.classList.add('border-red-500');
                field.focus();
                return false;
            } else {
                error.classList.add('hidden');
                field.classList.remove('border-red-500');
                return true;
            }
        }

        function validateForm() {
            const isValidName = validateField('owner-name', 'owner-name-error', /^[a-zA-Z\s]+$/, 'Invalid Full Name', 3);
            const isValidEmail = validateField('owner-email', 'owner-email-error', /^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|icloud\.com|aol\.com)$/, 'Invalid Email', 5);
            const isValidAddress = validateField('owner-address', 'owner-address-error', /^[a-zA-Z0-9\s.,\-#&\/]+$/, 'Invalid Address', 5);
            const isValidPetName = validateField('pet-name', 'pet-name-error', /^[a-zA-Z\s]+$/, 'Invalid Pet Name', 2);
            const isValidPetBreed = validateField('pet-breed', 'pet-breed-error', /^[a-zA-Z\s]+$/, 'Invalid Pet Breed', 2);

            return isValidName && isValidEmail && isValidAddress && isValidPetName && isValidPetBreed;
        }
    </script>
</body>
</html>
