<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
   header("Location: login.php"); // Redirect users who are not logged in
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRMSU Candelaria Vehicle Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Fade-in animation */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.3s ease-out forwards;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="scroll-smooth min-h-screen bg-fixed bg-cover bg-center flex justify-center" style="background-image: url('../480156258_1036463918529354_3266044399125512979_n.jpg');">
    <div class="w-full flex justify-center bg-gray-200 bg-opacity-80">
        <!-- Navbar -->
        <nav class="bg-yellow-600 bg-opacity-90 text-white shadow-lg fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center">
                        <img src="prmsu.png" alt="PRMSU Logo" class="w-10 mr-3">
                        <span class="font-bold text-lg">PRMSU Vehicle Access</span>
                    </div>
                    <div class="hidden md:flex space-x-6">
                        <p class="block">Security Personel (<?php echo $_SESSION['full_name'];?>)</p>

                    </div>
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-button" class="focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div id="mobile-menu" class="md:hidden hidden px-4 pb-4 space-y-2">
                <p class="block">Logout (<?php echo $_SESSION['full_name'];?>)</p>
            </div>
        </nav>
        <div class="w-full max-w-7xl sm:px-10 md:px-5 pt-20 ">
            <!-- Header Section -->
            <div class="text-center mb-8 mt-6 fade-in w-full">
                <img src="prmsu.png" alt="" class="w-32 m-auto items-center mb-4">
                <h1 class="text-3xl md:text-5xl font-bold text-yellow-700 mb-3">
                    Web-based RFID <br> Vehicle Access Monitoring System
                </h1>
                <p class="text-gray-800 text-sm md:text-base">
                    Streamline your campus vehicle access with our modern management system
                </p>
            </div>
    
            <!-- Card Section -->
            <div class="grid w-full grid-cols-1 w-full md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <a href="../pre.php" class="bg-yellow-500 text-white rounded-lg p-6 shadow-md hover:shadow-lg transform transition-all duration-300 hover:scale-105 fade-in">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car">
                        <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/>
                        <circle cx="7" cy="17" r="2"/>
                        <path d="M9 17h6"/>
                        <circle cx="17" cy="17" r="2"/>
                    </svg>
                    <h2 class="font-bold text-lg">Registration of Vehicle</h2>
                    <p class="text-sm">Add your vehicle to the system</p>
                </a>
    
                <!-- Card 2 -->
                <a href="./scanprocess/visitors.php" class="bg-yellow-500 text-white rounded-lg p-6 shadow-md hover:shadow-lg transform transition-all duration-300 hover:scale-105 fade-in">
                     <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-notebook-pen"><path d="M13.4 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7.4"/><path d="M2 6h4"/><path d="M2 10h4"/><path d="M2 14h4"/><path d="M2 18h4"/><path d="M21.378 5.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/></svg>
                    <h2 class="font-bold text-lg">Visitor's Entry</h2>
                    <p class="text-sm">Create visitor vehicle entry/exit's</p>
                </a>
    
                <!-- Card 3 -->
                <a href="./scanprocess/index.php" 
   target="_blank" 
   class="bg-yellow-500 text-white rounded-lg p-6 shadow-md hover:shadow-lg transform transition-all duration-300 hover:scale-105 fade-in">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-qr-code-icon lucide-scan-qr-code"><path d="M17 12v4a1 1 0 0 1-1 1h-4"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M17 8V7"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M7 17h.01"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="5" height="5" rx="1"/></svg>
                    <h2 class="font-bold text-lg">Scan Vehicle</h2>
                    <p class="text-sm">Monitor vehicle entry/exit in real time</p>
                </a>
                <a href="../admin/database/logout.php" class="bg-yellow-500 text-white rounded-lg p-6 shadow-md hover:shadow-lg transform transition-all duration-300 hover:scale-105 fade-in">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                    <h2 class="font-bold text-lg">Log out</h2>
                    <p class="text-sm"><?php echo $_SESSION['full_name'];?></p>
                </a>
            </div>
        </div>
    </div>

    <script>
    // Toggle mobile menu
    $('#mobile-menu-button').on('click', function () {
        $('#mobile-menu').toggleClass('hidden');
    });
</script>
</body>
</html>
