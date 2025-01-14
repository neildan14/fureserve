<?php
session_start();

if (!isset($_SESSION['appointment_id'])) {
    die("No appointment ID found.");
}

$appointment_id = $_SESSION['appointment_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "fureserve";

  // Check if selected_services is set
  if (empty($_POST['selected_services'])) {
      die("Please select at least one service to proceed.");
  }
  $selected_services = implode(", ", $_POST['selected_services']);
  $selected_date = $_POST['selected_date'] ?? ""; // Provide a default if not set
  $selected_time = $_POST['selected_time'] ?? ""; // Provide a default if not set

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sql = "UPDATE appointments SET selected_services = ?, selected_date = ?, selected_time = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssi", $selected_services, $selected_date, $selected_time, $appointment_id);

  if ($stmt->execute()) {
      header("Location: select-date-time.php");
      exit();
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
  <title>FuReserve - Service Customization</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            lightGreen: '#88d39a',
            darkGreen: '#2b8446',
            buttonGreen: '#99cc66',
            buttonHoverGreen: '#388e3c',
            dimmedGray: '#ff2c2c',
          },
        },
      },
    };
  </script>
  <style>
    .service-box {
      transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
    }

    .service-box.selected {
      background-color: #2b8446; /* Dark Green */
      color: white;
    }

    .service-box.dimmed {
      background-color: #ff2c2c; /* Dimmed Gray */
      pointer-events: none;
    }

    .service-box:hover:not(.dimmed) {
      transform: scale(1.05);
    }
  </style>
</head>
<body class="bg-lightGreen font-sans">

<div class="container max-w-5xl mx-auto p-6 bg-darkGreen text-white rounded-lg">
<a href="pet-owner-info.php" class="absolute top-4 left-4 bg-buttonGreen text-darkGreen px-4 py-2 rounded font-bold hover:bg-buttonHoverGreen">Back</a>

  <div class="fixed top-4 right-4 bg-gray-300 w-36 h-8 rounded flex items-center justify-center font-bold text-sm shadow-md">
    <div class="bg-darkGreen w-full h-full flex items-center justify-center rounded">Step 2 of 4</div>
  </div>

  <div class="text-center">
    <h1 class="text-3xl font-bold">FUReserve</h1>
    <img src="assets/images/logo.png" alt="Paw Icon" class="w-12 h-12 mx-auto my-2">
  </div>

  <div class="bg-buttonGreen text-white font-bold py-2 px-4 mb-6 rounded inline-block">Service Customization</div>

  <form method="POST" action="" class="grid grid-cols-2 md:grid-cols-3 gap-6" id="servicesForm">
    <label class="service-box bg-buttonGreen rounded p-4 text-center cursor-pointer" data-type="full-package">
      <input type="checkbox" name="selected_services[]" value="Full Package" class="hidden">
      <img src="assets/images/full-package.jpg" alt="Full Package" class="w-24 h-24 mx-auto object-cover">
      <h3 class="mt-4">Full Package</h3>
    </label>

    <label class="service-box bg-buttonGreen rounded p-4 text-center cursor-pointer" data-type="individual">
      <input type="checkbox" name="selected_services[]" value="Hair Cut" class="hidden">
      <img src="assets/images/haircut.jpg" alt="Hair Cut" class="w-24 h-24 mx-auto object-cover">
      <h3 class="mt-4">Hair Cut</h3>
    </label>

    <label class="service-box bg-buttonGreen rounded p-4 text-center cursor-pointer" data-type="individual">
      <input type="checkbox" name="selected_services[]" value="Fur Brushing" class="hidden">
      <img src="assets/images/fur-brushing.jpg" alt="Fur Brushing" class="w-24 h-24 mx-auto object-cover">
      <h3 class="mt-4">Fur Brushing</h3>
    </label>

    <label class="service-box bg-buttonGreen rounded p-4 text-center cursor-pointer" data-type="individual">
      <input type="checkbox" name="selected_services[]" value="Paw Cleaning" class="hidden">
      <img src="assets/images/paw-cleaning.jpeg" alt="Paw Cleaning" class="w-24 h-24 mx-auto object-cover">
      <h3 class="mt-4">Paw Cleaning</h3>
    </label>

    <label class="service-box bg-buttonGreen rounded p-4 text-center cursor-pointer" data-type="individual">
      <input type="checkbox" name="selected_services[]" value="Ear Cleaning" class="hidden">
      <img src="assets/images/ear-cleaning.jpg" alt="Ear Cleaning" class="w-24 h-24 mx-auto object-cover">
      <h3 class="mt-4">Ear Cleaning</h3>
    </label>

    <label class="service-box bg-buttonGreen rounded p-4 text-center cursor-pointer" data-type="individual">
      <input type="checkbox" name="selected_services[]" value="Nail Cutting" class="hidden">
      <img src="assets/images/nail-cutting.jpg" alt="Nail Cutting" class="w-24 h-24 mx-auto object-cover">
      <h3 class="mt-4">Nail Cutting</h3>
    </label>

    <label class="service-box bg-buttonGreen rounded p-4 text-center cursor-pointer" data-type="individual">
      <input type="checkbox" name="selected_services[]" value="Toothbrush" class="hidden">
      <img src="assets/images/toothbrush.jpg" alt="Toothbrush" class="w-24 h-24 mx-auto object-cover">
      <h3 class="mt-4">Toothbrush</h3>
    </label>

    <label class="service-box bg-buttonGreen rounded p-4 text-center cursor-pointer" data-type="individual">
      <input type="checkbox" name="selected_services[]" value="Cologne" class="hidden">
      <img src="assets/images/cologne.jpg" alt="Cologne" class="w-24 h-24 mx-auto object-cover">
      <h3 class="mt-4">Cologne</h3>
    </label>
  </form>

  <div class="flex justify-center items-center">
  <button type="submit" form="servicesForm" class="mt-6 bg-buttonGreen hover:bg-buttonHoverGreen text-white font-bold px-20 py-3 rounded">Next</button>
</div>

</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const serviceBoxes = document.querySelectorAll('.service-box');
    const form = document.getElementById('servicesForm');

    form.addEventListener('change', () => {
      const fullPackageCheckbox = [...form.elements].find(el => el.value === 'Full Package');
      const individualCheckboxes = [...form.elements].filter(el => el.type === 'checkbox' && el.value !== 'Full Package');

      const isFullPackageSelected = fullPackageCheckbox.checked;
      const areAnyIndividualSelected = individualCheckboxes.some(cb => cb.checked);

      serviceBoxes.forEach(box => {
        const checkbox = box.querySelector('input[type="checkbox"]');
        if (isFullPackageSelected && checkbox.value !== 'Full Package') {
          box.classList.add('dimmed');
          checkbox.checked = false;
        } else if (areAnyIndividualSelected && checkbox.value === 'Full Package') {
          box.classList.add('dimmed');
          checkbox.checked = false;
        } else {
          box.classList.remove('dimmed');
        }
      });
    });

    serviceBoxes.forEach(box => {
      box.addEventListener('click', () => {
        const checkbox = box.querySelector('input[type="checkbox"]');
        if (!checkbox.disabled) {
          checkbox.checked = !checkbox.checked;
          box.classList.toggle('selected', checkbox.checked);
          form.dispatchEvent(new Event('change'));
        }
      });
    });
  });

  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('servicesForm');
    const submitButton = document.querySelector('button[type="submit"]');

    submitButton.addEventListener('click', (event) => {
      const selectedServices = form.querySelectorAll('input[name="selected_services[]"]:checked');

      if (selectedServices.length === 0) {
        event.preventDefault(); // Prevent form submission
        alert("Please select at least one service to proceed."); // Show pop-up message
      }
    });
  });
</script>

</body>
</html>
