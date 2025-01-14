<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FuReserve - Pet Grooming</title>

  <!-- Tailwind CSS -->
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
            hoverCream: '#ffebcd',
          },
        },
      },
    };
  </script>

  <!-- Favicon -->
  <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
  <link rel="icon" href="assets/images/favicon.png" type="image/png">
</head>
<body class="font-sans bg-lightGreen m-0 p-0">

<!-- Navigation Bar -->
<nav class="bg-darkGreen flex items-center justify-between h-[70px] px-5 sticky top-0 z-50">
  <div class="flex items-center">
    <img src="assets/images/logo.png" alt="Logo" class="h-[50px] w-auto mr-3">
    <span class="text-white text-xl font-bold">FUReserve</span>
  </div>
  <div class="hidden md:flex space-x-4">
    <a href="#about" class="text-white text-lg font-bold transition-colors hover:text-hoverCream">About</a>
    <a href="#services" class="text-white text-lg font-bold transition-colors hover:text-hoverCream">Services</a>
    <a href="#contact" class="text-white text-lg font-bold transition-colors hover:text-hoverCream">Contact</a>
  </div>
  <div class="hamburger flex flex-col gap-[5px] cursor-pointer md:hidden">
    <div class="w-[25px] h-[3px] bg-white"></div>
    <div class="w-[25px] h-[3px] bg-white"></div>
    <div class="w-[25px] h-[3px] bg-white"></div>
  </div>
</nav>

<!-- FuReserve Section -->
<div id="start-grooming" class="bg-darkGreen text-white p-8 rounded-lg max-w-md mx-auto mt-10 text-center flex justify-center items-center">
  <div class="flex justify-center items-center gap-5">
    <img src="assets/images/paw.png" alt="FuReserve Icon" class="w-[100px] h-auto">
    <a href="pet-owner-info.php" class="bg-buttonGreen hover:bg-buttonHoverGreen text-white font-bold text-lg px-6 py-3 rounded shadow-md transform transition-transform hover:scale-105">Start Grooming</a>
  </div>
</div>

<div class="container max-w-5xl mx-auto px-5 py-10">
  <!-- About Section -->
  <!-- About Section -->
<section id="about" class="my-12">
  <h2 class="text-2xl text-darkGreen text-center mb-6">About Us</h2>
  <p class="text-center">
    Welcome to FUReserve! We at WILJHON PET GROOMING specialize in providing top-notch home grooming services
    for your beloved pets. 
  </p>
  <p class="text-center mb-6">
    Our expert groomers ensure that your furry friends
    look and feel their best. 
  </p>
  <p class="text-center mb-6">
  Come and visit us! We are located at 1887 Purok 5 Brgy. Dita, Santa Rosa City, Laguna.
  </p>
  <div class="flex flex-col md:flex-row items-center justify-center gap-6">
    <div class="text-center">
      <img src="assets/images/location.jpg" 
           alt="Wiljohn Pet Grooming Location" 
           class="w-[300px] h-[200px] object-cover rounded-lg shadow-md">
      <p class="mt-2 text-darkGreen font-semibold">Our Location</p>
    </div>
    <div class="text-center">
      <img src="assets/images/outside-view.jpg" 
           alt="Wiljohn Pet Grooming Outside View" 
           class="w-[300px] h-[200px] object-cover rounded-lg shadow-md">
      <p class="mt-2 text-darkGreen font-semibold">Outside View</p>
    </div>
  </div>
</section>

  <!-- Services Section -->
  <section id="services" class="my-12">
    <h2 class="text-2xl text-darkGreen text-center mb-6">Our Services</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php 
        $services = [
          ["Hair Cut", "assets/images/haircut.jpg", "Give your pet a stylish new look with a professional haircut."],
          ["Bath and Blow Dry", "assets/images/bath-blow-dry.jpg", "Keep your pet clean and fluffy with a refreshing bath and blow dry."],
          ["Fur Brushing", "assets/images/fur-brushing.jpg", "Maintain your pet's fur with a gentle and thorough brushing."],
          ["Paw Cleaning", "assets/images/paw-cleaning.jpeg", "Ensure your pet’s paws are clean and free from dirt."],
          ["Ear Cleaning", "assets/images/ear-cleaning.jpg", "Keep your pet's ears healthy and clean with regular ear cleaning."],
          ["Nail Cutting", "assets/images/nail-cutting.jpg", "Keep your pet's nails trimmed and healthy."],
          ["Toothbrush", "assets/images/toothbrush.jpg", "Maintain your pet's dental hygiene with a gentle brushing service."],
          ["Cologne", "assets/images/cologne.jpg", "Finish the grooming session with a refreshing cologne scent."]
        ];

        foreach ($services as $service) {
          echo "
          <div class='bg-white shadow rounded-lg p-5 flex flex-col items-center text-center'>
            <img src='{$service[1]}' alt='{$service[0]}' class='w-[120px] h-[120px] object-cover mb-4'>
            <h3 class='text-lg font-bold mb-2'>{$service[0]}</h3>
            <p>{$service[2]}</p>
          </div>";
        }
      ?>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="my-12">
    <h2 class="text-2xl text-darkGreen text-center mb-6">Contact Us</h2>
    <p class="text-center mb-4">
      Ready to book an appointment? Get in touch with us:
    </p>
    <p class="text-center"><strong>Phone:</strong></p>
    <p class="text-center">09484217177</p>
    <p class="text-center">09182091324</p>
    <p class="text-center">09922688053</p>
  </section>
</div>

<footer class="bg-darkGreen text-white text-center py-5">
  <p>&copy; <?php echo date("Y"); ?> FuReserve. All rights reserved.</p>
</footer>

</body>
</html>
