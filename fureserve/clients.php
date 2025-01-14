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

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_client'])) {
    $owner_email = $conn->real_escape_string($_POST['owner_email']);
    $delete_sql = "DELETE FROM appointments WHERE owner_email = '$owner_email'";
    $conn->query($delete_sql);
}

// Fetch distinct pet owner information from appointments
$sql = "SELECT DISTINCT owner_name, owner_email, owner_phone FROM appointments ORDER BY owner_name ASC";
$result = $conn->query($sql);

// Store client information in an array
$clients = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FUReserve - Clients</title>
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
            <button class="lg:hidden block text-white" id="nav-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <ul id="nav-links" class="hidden lg:flex space-x-6">
                <li><a href="admin-dashboard.php" class="hover:text-[#ffebcd] font-semibold">Dashboard</a></li>
                <li><a href="clients.php" class="hover:text-[#ffebcd] font-semibold">Clients</a></li>
            </ul>
            <ul class="hidden lg:flex space-x-4">
                <li><a href="change-password.php" class="hover:text-[#ffebcd] font-semibold">Change Password</a></li>
                <li><a href="logout.php" class="hover:text-[#ffebcd] font-semibold">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 mt-8">
        <section id="manage-clients" class="mb-8 mx-auto max-w-4xl">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="bg-[#2b8446] text-white font-semibold p-4 rounded-t-lg">
                    Manage Clients
                </div>
                <div class="p-4">
                    <?php if (!empty($clients)) : ?>
                        <ul class="divide-y divide-gray-200">
                            <?php foreach ($clients as $client) : ?>
                                <li class="py-4 flex justify-between items-center">
                                    <div>
                                        <p><strong>Client:</strong> <?= htmlspecialchars($client['owner_name']); ?></p>
                                        <p><strong>Email:</strong> <?= htmlspecialchars($client['owner_email']); ?></p>
                                        <p><strong>Phone number:</strong> <?= htmlspecialchars($client['owner_phone']); ?></p>
                                    </div>
                                    <div>
                                        <a href="edit-client.php?name=<?= urlencode($client['owner_name']); ?>&email=<?= urlencode($client['owner_email']); ?>&phone=<?= urlencode($client['owner_phone']); ?>" class="bg-[#99cc66] text-white px-4 py-1 rounded hover:bg-[#66b32f]">Edit</a>
                                        <form method="POST" action="clients.php" class="inline-block" onsubmit="return confirmDelete(this);">
                                            <input type="hidden" name="owner_email" value="<?= htmlspecialchars($client['owner_email']); ?>">
                                            <button type="submit" name="delete_client" class="bg-[#cc6666] text-white px-4 py-1 rounded hover:bg-[#b32f2f]">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="text-gray-600 text-center">No clients found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="bg-[#2b8446] text-white text-center py-4 mt-8">
        <p>&copy; 2024 FUReserve. All rights reserved.</p>
    </footer>

    <script>
        // Toggle Navbar
        const navToggle = document.getElementById('nav-toggle');
        const navLinks = document.getElementById('nav-links');
        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('hidden');
        });

        // Confirm Delete Function
        function confirmDelete(form) {
            return confirm('Are you sure you want to delete this client? This action cannot be undone.');
        }
    </script>
</body>

</html>
