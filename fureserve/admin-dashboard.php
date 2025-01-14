<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit();
}

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

// Handle Confirm and Cancel actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $action = $_POST['action'];

    if ($action === 'confirm') {
        $update_sql = "UPDATE appointments SET status = 'confirmed' WHERE id = ?";
    } elseif ($action === 'cancel') {
        $update_sql = "UPDATE appointments SET status = 'cancelled' WHERE id = ?";
    }

    if (isset($update_sql)) {
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $stmt->close();
    }

    // Refresh the page to show updated results
    header("Location: admin-dashboard.php");
    exit();
}

// Fetch upcoming appointments
$sql_upcoming = "
    SELECT id, owner_name AS client_name, pet_name, selected_services, selected_date, selected_time, status
    FROM appointments
    WHERE status = 'pending'
    ORDER BY selected_date, selected_time
";
$result_upcoming = $conn->query($sql_upcoming);
$upcoming_appointments = $result_upcoming->fetch_all(MYSQLI_ASSOC);

// Fetch confirmed appointments for the current day
$current_date = date('Y-m-d');
$sql_confirmed = "
    SELECT id, owner_name AS client_name, pet_name, selected_services, selected_date, selected_time
    FROM appointments
    WHERE status = 'confirmed' AND selected_date = ?
    ORDER BY selected_time
";
$stmt_confirmed = $conn->prepare($sql_confirmed);
$stmt_confirmed->bind_param("s", $current_date);
$stmt_confirmed->execute();
$result_confirmed = $stmt_confirmed->get_result();
$confirmed_appointments = $result_confirmed->fetch_all(MYSQLI_ASSOC);
$stmt_confirmed->close();

// Get the current date formatted for display
$display_date = date("F j, Y"); // Example: January 14, 2025

// Fetch cancelled appointments
$sql_cancelled = "
    SELECT id, owner_name AS client_name, pet_name, selected_services, selected_date, selected_time
    FROM appointments
    WHERE status = 'cancelled'
    ORDER BY selected_date, selected_time
";
$result_cancelled = $conn->query($sql_cancelled);
$cancelled_appointments = $result_cancelled->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FUReserve - Admin Dashboard</title>
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

    <!-- Content -->
    <div class="container mx-auto px-4 mt-8">
        <h1 class="text-3xl font-bold text-center text-white">Admin Dashboard</h1>

        <!-- Upcoming Appointments -->
        <section class="mt-8">
            <div class="bg-white rounded-lg shadow-lg max-w-5xl mx-auto">
                <div class="bg-[#2b8446] text-white font-semibold p-4 rounded-t-lg">
                    Upcoming Appointments
                </div>
                <div class="p-4">
                    <?php if (!empty($upcoming_appointments)) : ?>
                        <ul class="divide-y divide-gray-200">
                            <?php foreach ($upcoming_appointments as $appointment) : ?>
                                <?php $formatted_time = date("g:i A", strtotime($appointment['selected_time'])); ?>
                                <li class="py-4">
                                    <p><strong>Client:</strong> <?= htmlspecialchars($appointment['client_name']); ?></p>
                                    <p><strong>Pet:</strong> <?= htmlspecialchars($appointment['pet_name']); ?></p>
                                    <p><strong>Service:</strong> <?= htmlspecialchars($appointment['selected_services']); ?></p>
                                    <p><strong>Date:</strong> <?= htmlspecialchars($appointment['selected_date']); ?>, <?= htmlspecialchars($formatted_time); ?></p>
                                    <form method="POST" class="mt-2 flex gap-2">
                                        <input type="hidden" name="appointment_id" value="<?= $appointment['id']; ?>">
                                        <button type="submit" name="action" value="confirm"
                                            class="bg-[#99cc66] text-white px-4 py-1 rounded hover:bg-[#66b32f]">
                                            Confirm
                                        </button>
                                        <button type="submit" name="action" value="cancel"
                                            class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">
                                            Cancel
                                        </button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="text-gray-600 text-center">No upcoming appointments.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Confirmed Appointments -->
        <section class="mt-8">
            <div class="bg-white rounded-lg shadow-lg max-w-5xl mx-auto">
                <div class="bg-[#2b8446] text-white font-semibold p-4 rounded-t-lg">
                    Confirmed Appointments (<?= $display_date; ?>)
                </div>
                <div class="p-4">
                    <?php if (!empty($confirmed_appointments)) : ?>
                        <ul class="divide-y divide-gray-200">
                            <?php foreach ($confirmed_appointments as $appointment) : ?>
                                <?php $formatted_time = date("g:i A", strtotime($appointment['selected_time'])); ?>
                                <li class="py-4">
                                    <p><strong>Client:</strong> <?= htmlspecialchars($appointment['client_name']); ?></p>
                                    <p><strong>Pet:</strong> <?= htmlspecialchars($appointment['pet_name']); ?></p>
                                    <p><strong>Service:</strong> <?= htmlspecialchars($appointment['selected_services']); ?></p>
                                    <p><strong>Time:</strong> <?= htmlspecialchars($formatted_time); ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="text-gray-600 text-center">No confirmed appointments for today.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Cancelled Appointments -->
        <section class="mt-8">
            <div class="bg-white rounded-lg shadow-lg max-w-5xl mx-auto">
                <div class="bg-[#2b8446] text-white font-semibold p-4 rounded-t-lg">
                    Cancelled Appointments
                </div>
                <div class="p-4">
                    <?php if (!empty($cancelled_appointments)) : ?>
                        <ul class="divide-y divide-gray-200">
                            <?php foreach ($cancelled_appointments as $appointment) : ?>
                                <?php $formatted_time = date("g:i A", strtotime($appointment['selected_time'])); ?>
                                <li class="py-4">
                                    <p><strong>Client:</strong> <?= htmlspecialchars($appointment['client_name']); ?></p>
                                    <p><strong>Pet:</strong> <?= htmlspecialchars($appointment['pet_name']); ?></p>
                                    <p><strong>Service:</strong> <?= htmlspecialchars($appointment['selected_services']); ?></p>
                                    <p><strong>Date:</strong> <?= htmlspecialchars($appointment['selected_date']); ?>, <?= htmlspecialchars($formatted_time); ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="text-gray-600 text-center">No cancelled appointments.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>

    <footer class="bg-[#2b8446] text-white text-center py-4">
        <p>&copy; 2024 FUReserve. All rights reserved.</p>
    </footer>
</body>

</html>