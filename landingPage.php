<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body{
            scroll-behavior: smooth;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 1s ease-out; }
    </style>

</head>
<body class="font-sans scroll-smooth bg-fixed bg-cover bg-center block justify-center" style="background-image: url('./480156258_1036463918529354_3266044399125512979_n.jpg');">
<div class="block justify-center min-h-screen bg-gray-200 bg-opacity-90 w-full pt-8">
    <!-- Hero Section -->
    <section class="flex flex-col items-center pt-6 justify-center text-center lg:pt-22 px-6">
        <img src="./prmsu.png" width="120px" alt="" class="animate-fade-in-up">
        <h2 class="text-3xl text-yellow-800 sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 animate-fade-in-up"><br> Vehicle RFID Card Registration Portal</h2>
        <p class="text-base sm:text-lg md:text-xl mb-6 animate-fade-in-up delay-200">Secure and streamlined vehicle registration for <br>President Ramon Magsaysay State University Candelaria campus access</p>
        <div class="flex space-x-4 animate-fade-in-up delay-400">
            <a href="#register" class="bg-white text-yellow-800 font-bold px-6 py-3 rounded-lg shadow hover:bg-gray-200 transition text-sm sm:text-base">Learn more</a>
        </div>
    </section>  

    <div class="container mx-auto mt-8">
        <div class="p-4 lg:px-12 rounded">
            <h2 class="text-2xl sm:text-3xl font-bold text-center mb-8">How It Works</h2>
            <div class="grid md:grid-cols-3 gap-8 text-center lg:px-16 py-8">
                <div class="p-6 border bg-white rounded-lg shadow-sm hover:shadow-lg transition transform hover:scale-105 animate-fade-in-up">
                    <div class=" flex justify-center items-center text-blue-500 text-3xl sm:text-4xl mb-4">
                        <div class="bg-blue-200 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car-icon lucide-car"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-lg sm:text-xl">Pre-Register Your Vehicle</h3>
                    <p class="text-gray-600 text-sm sm:text-base">Submit your vehicle information and personal details for verification</p>
                </div>
                <div class="p-6 border bg-white rounded-lg shadow-sm hover:shadow-lg transition transform hover:scale-105 animate-fade-in-up">
                    <div class=" flex justify-center items-center text-green-800 text-3xl sm:text-4xl mb-4">
                        <div class="bg-green-200 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-check-icon lucide-user-round-check"><path d="M2 21a8 8 0 0 1 13.292-6"/><circle cx="10" cy="8" r="5"/><path d="m16 19 2 2 4-4"/></svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-lg sm:text-xl">Approval Process</h3>
                    <p class="text-gray-600 text-sm sm:text-base">Administrators review and approve your vehicle <br> registration</p>
                </div>
                <div class="p-6 border bg-white rounded-lg shadow-sm hover:shadow-lg transition transform hover:scale-105 animate-fade-in-up">
                    <div class=" flex justify-center items-center text-orange-500 text-3xl sm:text-4xl mb-4">
                        <div class="bg-orange-200 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-icon lucide-shield"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-lg sm:text-xl">Secure Access</h3>
                    <p class="text-gray-600 text-sm sm:text-base">Receive RFID access for seamless entry and exit from campus</p>
                </div>
            </div>
        </div>

        <section id="register" class="py-16 bg-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">
                Get Started Today
                </h2>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Enhance your campus security with our state-of-the-art RFID vehicle monitoring system.
                </p>
                <a href="./pre.php" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Register now
                </a>
            </div>
        </section>
    </div>
    <!-- Footer Section -->
    <footer class="bg-gray-900 text-white py-6">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-between px-12 text-center md:text-left">
            <div class="mb-4 md:mb-0">
                <h1 class="text-lg font-semibold">Vehicle Registration Portal</h1>
                <p class="text-xs text-gray-300">Securely manage vehicle access on campus</p>
            </div>
            <div>
                <p class="text-xs text-gray-300">&copy; 2025 PRMSU Candelaria. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    </div>
</body>
</html>
