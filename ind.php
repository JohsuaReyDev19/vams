<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRMSU Candelaria Vehicle Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Fade-in effect
            $(".fade-in").hide().fadeIn(700);

            // Logo animation
            $(".logo").hide().fadeIn(800);

            // Button hover effects
            $(".animate-btn").hover(
                function () {
                    $(this).addClass("scale-105 shadow-lg transition");
                },
                function () {
                    $(this).removeClass("scale-105 shadow-lg");
                }
            );
        });
    </script>
</head>
<body class=" flex items-center bg-fixed bg-cover bg-center justify-center" style="background-image: url('./480156258_1036463918529354_3266044399125512979_n.jpg');">
    <div class="flex justify-center min-h-screen bg-gray-200 bg-opacity-80 w-full pt-8">
        <div class="w-full max-w-2xl rounded-lg p-6 fade-in">
            <!-- Logo -->
            <div class="flex justify-center">
                <img src="prmsu.png" alt="PRMSU Logo" class="w-32 logo">
            </div>

            <!-- Title -->
            <div class="text-center mt-4">
                <h1 class="text-2xl md:text-3xl font-bold text-yellow-800 leading-snug">
                    Welcome to <br>
                    Web-based RFID Vehicle Access Monitoring Registration
                </h1>
                <p class="text-gray-600 text-m mt-1">PRMSU Candelaria Campus</p>
            </div>

            <!-- Subtitle -->
            <div class="text-center mt-6">
                <h2 class="text-lg font-semibold text-gray-700">Register Your Vehicle Today!</h2>
            </div>

            <!-- Buttons -->
            <div class="grid grid-cols-1 gap-4 mt-6">
                <a href="student.php" class="block w-full max-w-md mx-auto text-center bg-yellow-500 text-white py-3 rounded-lg text-lg font-semibold animate-btn">Student</a>
                <a href="faculty.php" class="block w-full max-w-md mx-auto text-center bg-yellow-500 text-white py-3 rounded-lg text-lg font-semibold animate-btn">Faculty/Stuff</a>
                <a href="visitors.php" class="block w-full max-w-md mx-auto text-center bg-yellow-500 text-white py-3 rounded-lg text-lg font-semibold animate-btn">Ojt</a>
                <a href="others.php" class="block w-full max-w-md mx-auto text-center bg-yellow-500 text-white py-3 rounded-lg text-lg font-semibold animate-btn">Visitors/Others</a>
            </div>
        </div>
    </div>
</body>
</html>
