<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin-login.php");
    exit();
}

$admin_username = $_SESSION['admin_username'];

// Database connection
$host = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "fureserve";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$message_color = ""; // For setting message color dynamically
$password_updated = false;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if current password is correct
    $stmt = $conn->prepare("SELECT * FROM admin_accounts WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $admin_username, $current_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Check if new password matches current password
        if ($new_password === $current_password) {
            $message = "New password cannot be the same as the current password.";
            $message_color = "text-red-500";
        } elseif ($new_password === $confirm_password) {
            // Update password in the database
            $stmt = $conn->prepare("UPDATE admin_accounts SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $new_password, $admin_username);
            if ($stmt->execute()) {
                $message = "Password updated successfully.";
                $message_color = "text-green-500";
                $password_updated = true; // Set the flag to true
            } else {
                $message = "Error updating password.";
                $message_color = "text-red-500";
            }
        } else {
            $message = "New password and confirmation do not match.";
            $message_color = "text-red-500";
        }
    } else {
        $message = "Current password is incorrect.";
        $message_color = "text-red-500";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FUReserve - Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // JavaScript function to show the confirmation popup
        function showConfirmation() {
            alert("Password is updated successfully.");
            window.location.href = "admin-dashboard.php";
        }
    </script>
    <style>
        /* Ensure the footer sticks to the bottom */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
        }
    </style>
</head>

<body class="bg-[#88d39a] font-sans">
    <nav class="bg-[#2b8446] text-white shadow-lg">
        <div class="container mx-auto flex justify-between items-center px-4 py-3">
            <a href="#" class="flex items-center text-white font-bold text-lg">
                <img src="assets/images/logo.png" alt="Logo" class="h-10 w-auto mr-2">
                FUReserve
            </a>
            <ul class="hidden lg:flex space-x-6">
                <li><a href="admin-dashboard.php" class="hover:text-[#ffebcd] font-semibold">Dashboard</a></li>
                <li><a href="clients.php" class="hover:text-[#ffebcd] font-semibold">Clients</a></li>
            </ul>
            <ul class="hidden lg:flex space-x-4">
                <li><a href="change-password.php" class="hover:text-[#ffebcd] font-semibold">Change Password</a></li>
                <li><a href="logout.php" class="hover:text-[#ffebcd] font-semibold">Logout</a></li>
            </ul>
        </div>
    </nav>

    <main class="container mx-auto px-4 mt-8">
        <section id="change-password" class="mb-8 mx-auto max-w-4xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-[#2b8446] text-white font-semibold p-4 rounded-t-lg">
                    Change Password
                </div>
                <div class="p-4">
                    <?php if ($message): ?>
                        <p class="<?php echo $message_color; ?> text-center mb-4"><?php echo $message; ?></p>
                        <?php if ($password_updated): ?>
                            <script>
                                showConfirmation();
                            </script>
                        <?php endif; ?>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <!-- Current Password -->
                        <div class="mb-4">
                            <label for="current_password" class="block text-gray-700 font-semibold mb-2">Current Password</label>
                            <input type="password" name="current_password" id="current_password" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2b8446]">
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="new_password" class="block text-gray-700 font-semibold mb-2">New Password</label>
                            <input type="password" name="new_password" id="new_password" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2b8446]">
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="confirm_password" class="block text-gray-700 font-semibold mb-2">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2b8446]">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-[#2b8446] text-white font-semibold py-2 rounded-md hover:bg-[#1f6235] transition duration-300">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-[#2b8446] text-white text-center py-4">
        <p>&copy; 2024 FUReserve. All rights reserved.</p>
    </footer>
</body>

</html>
