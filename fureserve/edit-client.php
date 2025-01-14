<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to the login page if not logged in
    header("Location: admin-login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fureserve";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$error = '';
$success = '';

// Check if the query parameters are set
if (isset($_GET['name'], $_GET['email'], $_GET['phone'])) {
    $ownerName = urldecode($_GET['name']);
    $ownerEmail = urldecode($_GET['email']);
    $ownerPhone = urldecode($_GET['phone']);
} else {
    // Redirect back to clients page if no data provided
    header("Location: clients.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newOwnerName = $_POST['owner_name'];
    $newOwnerEmail = $_POST['owner_email'];
    $newOwnerPhone = $_POST['owner_phone'];

    // Validate inputs
    if (empty($newOwnerName) || empty($newOwnerEmail) || empty($newOwnerPhone)) {
        $error = "All fields are required.";
    } else {
        // Update the database
        $stmt = $conn->prepare("UPDATE appointments SET owner_name = ?, owner_email = ?, owner_phone = ? WHERE owner_name = ? AND owner_email = ? AND owner_phone = ?");
        $stmt->bind_param("ssssss", $newOwnerName, $newOwnerEmail, $newOwnerPhone, $ownerName, $ownerEmail, $ownerPhone);

        if ($stmt->execute()) {
            $success = "Client information updated successfully.";
            // Update the query parameter values
            $ownerName = $newOwnerName;
            $ownerEmail = $newOwnerEmail;
            $ownerPhone = $newOwnerPhone;
        } else {
            $error = "Error updating client information: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#88d39a] font-sans">

    <!-- Navbar -->
    <nav class="bg-[#2b8446] text-white shadow-lg">
        <div class="container mx-auto flex justify-between items-center px-4 py-3">
            <a href="#" class="flex items-center text-white font-bold text-lg">
                <img src="assets/images/logo.png" alt="Logo" class="h-10 w-auto mr-2">
                FUReserve
            </a>
            <ul class="hidden lg:flex space-x-4">
                <li><a href="clients.php" class="hover:text-[#ffebcd] font-semibold">Back to Clients</a></li>
                <li><a href="logout.php" class="hover:text-[#ffebcd] font-semibold">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 mt-8 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Edit Client</h1>

            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-4">
                    <label for="owner_name" class="block text-gray-700 font-semibold mb-2">Client Name</label>
                    <input type="text" id="owner_name" name="owner_name" value="<?= htmlspecialchars($ownerName); ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="owner_email" class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" id="owner_email" name="owner_email" value="<?= htmlspecialchars($ownerEmail); ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="owner_phone" class="block text-gray-700 font-semibold mb-2">Phone Number</label>
                    <input type="text" id="owner_phone" name="owner_phone" value="<?= htmlspecialchars($ownerPhone); ?>" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <button type="submit" class="bg-[#99cc66] text-white px-4 py-2 rounded hover:bg-[#66b32f]">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#2b8446] text-white text-center py-4 mt-20">
        <p>&copy; 2024 FUReserve. All rights reserved.</p>
    </footer>

</body>

</html>
