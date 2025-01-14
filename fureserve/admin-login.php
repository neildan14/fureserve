<?php
// Start the session
session_start();

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

// Handle form submission
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_username = $_POST['username'];
    $admin_password = $_POST['password'];

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM admin_accounts WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $admin_username, $admin_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Valid login
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin_username; // Store username in session
        header("Location: admin-dashboard.php");
        exit();
    } else {
        // Invalid login
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Paws & Claws Grooming</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-center">
  <div class="flex justify-center items-center h-screen bg-gradient-to-br from-green-700 to-green-300">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
      <h1 class="text-3xl font-bold text-gray-700 mb-6">Admin Login</h1>
      <?php if ($error): ?>
        <div class="mb-4 text-red-500 text-sm"><?php echo $error; ?></div>
      <?php endif; ?>
      <form action="" method="POST">
        <!-- Username Input -->
        <input 
          type="text" 
          name="username" 
          placeholder="Username" 
          required 
          class="w-full px-4 py-2 mb-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400"
        >

        <!-- Password Input -->
        <div class="relative mb-4">
          <input 
            type="password" 
            name="password" 
            id="password" 
            placeholder="Password" 
            required 
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400"
          >
          <!-- Toggle Checkbox -->
          <div class="flex items-center mt-2">
            <input 
              type="checkbox" 
              id="showPassword" 
              class="mr-2 cursor-pointer"
              onclick="togglePassword()"
            >
            <label for="showPassword" class="text-sm text-gray-600 cursor-pointer">
              Show Password
            </label>
          </div>
        </div>

        <!-- Login Button -->
        <button 
          type="submit"
          class="w-full bg-green-500 text-white text-lg font-medium py-2 rounded-md hover:bg-green-400 transition duration-300"
        >
          Login
        </button>
      </form>
    </div>
  </div>

  <!-- Script to Toggle Password Visibility -->
  <script>
    function togglePassword() {
      const passwordField = document.getElementById('password');
      const showPasswordCheckbox = document.getElementById('showPassword');
      
      if (showPasswordCheckbox.checked) {
        passwordField.type = 'text';
      } else {
        passwordField.type = 'password';
      }
    }
  </script>
</body>
</html>
