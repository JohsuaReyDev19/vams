<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
   header("Location: ../index.php"); // Redirect users who are not logged in
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
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
    <div class="w-full flex justify-center">
        <!-- Navbar -->
        <nav class="bg-yellow-600 bg-opacity-90 text-white shadow-lg fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center">
                        <img src="prmsu.png" alt="PRMSU Logo" class="w-10 mr-3">
                        <span class="font-bold text-lg">RFID-VAMS</span>
                    </div>
                    <div class="hidden md:flex space-x-6 items-center relative">

                        <a href="./index.php" class="block hover:underline cursor-pointer">Scan Vehicle</a>
                        <a href="./visitor_entry.php" class="block hover:underline cursor-pointer">Visitor's Entry</a>

                        <!-- Dropdown Button -->
                        <div class="relative group">
                            <button class="flex items-center gap-1 hover:underline focus:outline-none">
                                Add Vehicle
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 w-40 bg-white border border-gray-200 rounded shadow-md hidden group-hover:block z-50">
                                <a href="./register_form.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">Vehicle Registration</a>
                                <a href="./add_vehicle.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">Add vehicle</a>
                            </div>
                        </div>

                        <!-- Dropdown Button -->
                        <div class="relative group">
                            <button class="flex items-center gap-1 hover:underline focus:outline-none">
                                My Profile
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 w-40 bg-white border border-gray-200 rounded shadow-md hidden group-hover:block z-50">
                                <a href="./index.php" class="btnProfile block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="../admin/database/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m16 17 5-5-5-5"/>
                                        <path d="M21 12H9"/>
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    </svg>
                                    Logout
                                </a>
                            </div>
                        </div>

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
                        <a class="btnscan block hover:underline cursor-pointer">Scan Vehicle</a>
                        <a href="./visitor_entry.php" class="block hover:underline cursor-pointer">Visitor's Entry</a>
                        <a href="./register_form.php" class="block hover:underline cursor-pointer">Registration</a>
                        <a href="./add_vehicle.php" class="addVehicle block hover:underline cursor-pointer">Add vehicle</a>
                        <a class="btnProfile block hover:underline cursor-pointer">My Profile</a>
                        <a href="../admin/database/logout.php" target="_blank"  class="btnvisitor block hover:underline flex justify-center items-center gap-1">
                             <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                            Log out
                        </a>
            </div>
        </nav>
        <div class="w-full max-w-7xl sm:px-10 md:px-5 pt-20 ">
            <!-- Card Section -->

            <div id="pre-registered" class="fade-in hidden">
                <?php
                    include './pre.php';
                ?>
            </div>

            <div id="visitors-entry" class="hidden fade-in">
                <?php
                    include './visitors.php';
                ?>
            </div>

            <div id="scan-rfid" class="fade-in">
                <?php
                    include './scan.php';
                ?>
            </div>

            <div id="profile" class="hidden fade-in">
                <?php
                    include './profile.php';
                ?>
            </div>
            
            <div id="showAddVehicle" class="hidden fade-in">
                <?php
                    include './addVehicle.php';
                ?>
            </div>
        </div>
    </div>
    <!-- RFID Input Modal -->
    <div id="rfidModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md fade-in">
            <h2 class="text-2xl font-bold mb-4">Scan the RFID Sticker <br>
                <p class="text-sm text-gray-400">Place the RFID Sticker on reader device</p>
            </h2>
            <div id="scanbar" class="flex justify-center items-center m-3">
                 <div class="scanner-container">
                    <div class="scan-line"></div>
                    <div class="glass-glow"></div>
                    <div class="center-text">Scanning...</div>
                </div>
            </div>
            <input type="text" id="rfidInput" class="w-full px-4 py-2 border rounded-md hidden" placeholder="Scanning..." required readonly>
            <div id="plate_status"></div>
            <br>
            <div class="flex justify-end gap-2">
                <span id="cancelModalBtn" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 cursor-pointer">Cancel</span>
                <button id="submitModalBtn" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 hidden" disabled>Done</button>
            </div>
        </div>
    </div>


<!-- RFID Modal -->
<div id="rfid_Modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-100">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md fade-in">
        <h2 class="text-2xl font-bold mb-4">Scan the RFID Sticker <br>
            <p class="text-sm text-gray-400">Place the RFID Sticker on reader device</p>
        </h2>
        <div id="scan_bar" class="flex justify-center items-center m-3">
            <div class="scanner-container">
                <div class="scan-line"></div>
                <div class="glass-glow"></div>
                <div class="center-text">Scanning...</div>
            </div>
        </div>
        <input type="text" id="rfid_Input" 
               class="w-full px-4 py-2 border rounded-md hidden" 
               placeholder="Scanning..." required readonly>
        <br>
        <div class="flex justify-end gap-2">
            <span id="cancel_ModalBtn" 
                  class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 cursor-pointer">
                  Cancel
            </span>
            <button id="submit_ModalBtn" 
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 hidden" disabled>
                    Done
            </button>
        </div>
    </div>
</div>

<script>
    let scanInterval; // keep interval reference so we can restart

    /** 📡 RFID Scan Loop **/
    function startRFIDScan() {
        // clear previous interval if modal reopened
        clearInterval(scanInterval);

        scanInterval = setInterval(() => {
            fetch('../scanprocess/read_tag.php')
                .then(response => response.text())
                .then(tag => {
                    const cleanTag = tag.trim();
                    const input = $('#rfid_Input');

                    if (cleanTag && cleanTag !== "Waiting for tag...") {
                        input.val(cleanTag);
                        $('#scanbar').addClass('hidden');
                        $('#submit_ModalBtn').removeClass('hidden').prop('disabled', false);
                        input.removeClass('hidden');

                        clearInterval(scanInterval); // stop scanning
                    }
                })
                .catch(err => console.error("RFID Scan Error:", err));
        }, 500);
    }

    /** 📡 Open RFID Modal **/
    $('#scan_rfid').on('click', function () {
        $('#rfid_Modal').removeClass('hidden');
        $('#rfid_Input').val('').addClass('hidden');
        $('#scan_bar').removeClass('hidden');
        $('#submit_ModalBtn').addClass('hidden').prop('disabled', true);
        startRFIDScan(); // Start scanning
    });

    /** ❌ Cancel RFID Modal **/
    $('#cancel_ModalBtn').on('click', function () {
        $('#rfid_Modal').addClass('hidden');
        clearInterval(scanInterval); // stop scanning when closed
    });

    /** ✅ Submit RFID from Modal to Form **/
    $('#submit_ModalBtn').on('click', function () {
        let tagValue = $('#rfid_Input').val().trim();
        $('#rfidtag').val(tagValue).removeClass('hidden');
        $('#rfid_Modal').addClass('hidden');
        $('#clear').removeClass('hidden');
    });

    /** 🔄 Clear RFID from Form **/
    $('#clear').on('click', function () {
        $('#rfidtag').val('').addClass('hidden');
        $('#clear').addClass('hidden');
    });
</script>

    <script>
    // Toggle mobile menu
    $('#mobile-menu-button').on('click', function () {
        $('#mobile-menu').toggleClass('hidden');
    });

    $('.btnscan').on('click', function () {
        $('#scan-rfid').removeClass('hidden');
        $('#pre-registered').addClass('hidden');
        $('#visitors-entry').addClass('hidden');
        $('#profile').addClass('hidden');
        $('#showAddVehicle').addClass('hidden');
    });

    $('.btnProfile').on('click', function () {
        $('#profile').removeClass('hidden');
        $('#scan-rfid').addClass('hidden');
        $('#pre-registered').addClass('hidden');
        $('#visitors-entry').addClass('hidden');
        $('#showAddVehicle').addClass('hidden');
    });
</script>
</body>
</html>
