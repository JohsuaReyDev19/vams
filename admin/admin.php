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
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Dashboard</title>
</head>
<body>
    <!-- Semester Modal -->
   <div id="modal_semester" class="hidden fixed inset-0 flex justify-center items-center bg-gray-600 bg-opacity-50 z-50">
      <div class="bg-white p-6 sm:p-8 rounded-xl shadow-2xl w-[95%] sm:w-[85%] md:w-[70%] lg:w-[60%] xl:w-[50%] max-w-5xl">
         
         <h2 class="text-2xl font-semibold mb-4 text-center">Reminder: Admin please update semester</h2>
         
         <div class="flex flex-col sm:flex-row sm:flex-wrap sm:justify-center gap-4 items-center">
            <p class="text-gray-700 font-medium">Set the date of Semester</p>

            <!-- Date Inputs -->
            <input type="date" id="startDateInput" class="p-2 border rounded w-full sm:w-auto">
            <input type="date" id="endDateInput" class="p-2 border rounded w-full sm:w-auto">

            <!-- Buttons -->
            <div class="flex gap-2">
               <button id="StartSemesterBtn"
                  class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg shadow-md text-sm transition duration-150 ease-in-out">
                  Start Semester
               </button>
               <button id="EndSemesterBtn"
                  class="hidden px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg shadow-md text-sm transition duration-150 ease-in-out">
                  End Semester
               </button>
            </div>
         </div>
      </div>
   </div>
<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center justify-start rtl:justify-end">
          <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
              <span class="sr-only">Open sidebar</span>
              <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                 <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
              </svg>
           </button>
          <a href="#" class="flex ms-2 md:me-24">
            <img src="../prmsu.png" class="h-8 me-3" alt="FlowBite Logo" />
            <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">RFID-VAMS</span>
          </a>
        </div>
        <div class="flex items-center">
            <div class="flex items-center ms-3">
              <div>
                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                  <span class="sr-only">Open user menu</span>
                 <span class="text-white">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-user-icon lucide-shield-user"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="M6.376 18.91a6 6 0 0 1 11.249.003"/><circle cx="12" cy="11" r="4"/></svg>
                 </span>
                </button>
              </div>
              <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-sm shadow-sm dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                <div class="px-4 py-3" role="none">
                  <p class="text-sm text-gray-900 dark:text-white" role="none">
                  <?php echo $_SESSION['full_name'];?>
                  </p>
                  <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                  <?php echo $_SESSION['email'];?>
                  </p>
                </div>
                  <li>
                    <a href="./database/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Log out</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
      </div>
    </div>
  </nav>
  
  <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
     <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
           <li>
              <a href="admin.php" class="flex items-center p-2 text-gray-200 rounded-lg bg-gray-700 group">
               <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-blocks"><rect width="7" height="7" x="14" y="3" rx="1"/><path d="M10 21V8a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H3"/></svg>
                 <span class="ms-3">Dashboard</span>
              </a>
           </li>
           <li class="hidden">
              <a href="./pages/pre-registration.php" class="flex items-center p-2 text-gray-200 rounded-lg  hover:bg-gray-100 dark:hover:bg-gray-700 group">
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-checks-icon lucide-list-checks"><path d="m3 17 2 2 4-4"/><path d="m3 7 2 2 4-4"/><path d="M13 6h8"/><path d="M13 12h8"/><path d="M13 18h8"/></svg>
                 <span class="flex-1 ms-3 whitespace-nowrap">Pre Registration</span>
              </a>
           </li>
           <li>
              <a href="./pages/vehicles.php" class="flex items-center p-2 text-gray-200 rounded-lg  hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                 <span class="flex-1 ms-3 whitespace-nowrap">Registered Vehicle</span>
              </a>
           </li>
           <li>
              <a href="./pages/manage-user.php" class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                 <span class="flex-1 ms-3 whitespace-nowrap">Manage User</span>
              </a>
           </li>
           <li>
              <a href="./pages/accesslogs.php" class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                 <span class="flex-1 ms-3 whitespace-nowrap">Access Logs</span>
              </a>
              <li>
              <a href="./pages/account.php" class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                 <span class="flex-1 ms-3 whitespace-nowrap">Settings</span>
              </a>
           </li>
           </li>
              <a href="./database/logout.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                 <span class="flex-1 ms-3 whitespace-nowrap text-red-500">Log out</span>
              </a>
           </li>
        </ul>
     </div>
  </aside>
  <div class="p-6 sm:ml-64">
      <div class="w-full mt-12 mb-4">
          <h1 class="font-bold text-3xl">Dashboard</h1>
      </div>
      <div id="showdashboard" class="space-y-6 animate-slide-in">
         <div class="grid grid-cols-1  lg:grid-cols-3 gap-6">
            <div class="bg-gray-100 p-8 rounded-xl border">
               <div class="flex items-start justify-between">
                     <div>
                        <p class="text-m text-gray-500">Registered Vehicles</p>
                        <h3 class="text-4xl font-semibold mt-2" id="approved"></h3>
                     </div>
                     <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-car text-blue-600">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car-front"><path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8"/><path d="M7 14h.01"/><path d="M17 14h.01"/><rect width="18" height="8" x="3" y="10" rx="2"/><path d="M5 18v2"/><path d="M19 18v2"/></svg>
                        </i>
                     </div>
               </div>
            </div>
            
            <div class="bg-gray-100 p-6 rounded-xl border">
               <div class="flex items-start justify-between">
                     <div>
                        <p class="text-m text-gray-500">Today's Entries</p>
                        <h3 class="text-4xl font-semibold mt-2" id="total_entered"></h3>
                     </div>
                     <div class="w-10 h-10 rounded-full bg-green-200 flex items-center justify-center">
                        <i class="fas fa-arrow-right text-green-600">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-from-line"><path d="m9 6-6 6 6 6"/><path d="M3 12h14"/><path d="M21 19V5"/></svg>
                        </i>
                     </div>
               </div>
            </div>
            <div class="bg-gray-100 p-6 rounded-xl border">
               <div class="flex items-start justify-between">
                     <div>
                        <p class="text-m text-gray-500">Today's Exits</p>
                        <h3 class="text-4xl font-semibold mt-2" id="total_exit"></h3>
                     </div>
                     <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-arrow-left text-red-600">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-from-line"><path d="M3 5v14"/><path d="M21 12H7"/><path d="m15 18 6-6-6-6"/></svg>
                        </i>
                     </div>
               </div>
            </div>
         </div>

         <!-- Recent Activity -->
         <div class="bg-gray-100 p-6 rounded border-black">
            <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
            <div id="recent-activity" class="space-y-4 max-h-[300px] overflow-y-auto pr-2">
               <!-- Activity will be dynamically inserted here -->
            </div>
         </div>
   </div>
  </div>
<script>
$(document).ready(function() {
    let semesterEnded = false; // 🔹 Flag to prevent modal from showing again

    // 🔹 Load saved dates on page load
    $.post("./database/semester.php", { action: "get_dates" }, function(res) {
        console.log("Loaded semester dates:", res); // DEBUG

        if (res.success && res.data) {
            $("#startDateInput").val(res.data.start_date);
            $("#endDateInput").val(res.data.end_date);

            checkEndDate(); // check if end date <= today

            // If semester already started, lock Start button + disable date inputs
            if (res.data.start_date !== "0000-00-00" && res.data.end_date !== "0000-00-00") {
                $("#StartSemesterBtn")
                    .text("Ongoing Semester")
                    .prop("disabled", true)
                    .addClass("opacity-50 cursor-not-allowed");

                // ⛔ Disable date inputs
                $("#startDateInput, #endDateInput").prop("disabled", true);
                $("#modal_semester").addClass("hidden");
            }
        } else {
            console.warn("No semester dates found in DB");
        }
    }, "json");

    // 🔹 Handle Start Semester
    $("#StartSemesterBtn").click(function() {
        let startDate = $("#startDateInput").val();
        let endDate = $("#endDateInput").val();

        if (!startDate || !endDate) {
            Swal.fire({
                icon: "warning",
                title: "Missing Dates",
                text: "Please select both start and end dates."
            });
            return;
        }

        $.post("./database/semester.php", 
            { action: "start", start_date: startDate, end_date: endDate }, 
            function(res) {
                console.log("Start Semester Response:", res); // DEBUG
                if (res.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Semester Started",
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Update button state
                    $("#StartSemesterBtn")
                        .text("Ongoing Semester")
                        .prop("disabled", true)
                        .addClass("opacity-50 cursor-not-allowed");

                    $("#modal_semester").addClass("hidden");

                    // ⛔ Disable date inputs until semester ends
                    $("#startDateInput, #endDateInput").prop("disabled", true);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.message
                    });
                }
            }, 
        "json");
    });

    // 🔹 Function to check if End Date <= Today
    function checkEndDate() {
        if (semesterEnded) return; // ⛔ Skip if already ended

        let endDate = $("#endDateInput").val();
        let today = new Date().toISOString().split("T")[0]; // YYYY-MM-DD

        if (endDate && endDate <= today) {   // ✅ less than OR equal
            $("#EndSemesterBtn").removeClass("hidden");
            $("#StartSemesterBtn").addClass("hidden");
            $("#modal_semester").removeClass("hidden");
        } else {
            $("#EndSemesterBtn").addClass("hidden");
            if (!$("#StartSemesterBtn").prop("disabled")) {
                $("#StartSemesterBtn").removeClass("hidden");
            }
        }
    }

    // Recheck when endDate input changes
    $("#endDateInput").on("change", checkEndDate);

    // Auto-check every 700ms (change to 60000 for every 1 minute)
    setInterval(checkEndDate, 700);

    // 🔹 Handle End Semester
    $("#EndSemesterBtn").click(function() {
        $.post("./database/semester.php", { action: "end" }, function(res) {
            console.log("End Semester Response:", res); // DEBUG

            if (res.success) {
                Swal.fire({
                    icon: "success",
                    title: "Semester Ended",
                    text: res.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    semesterEnded = true; // ✅ Prevent modal from reappearing

                    $("#modal_semester").addClass("hidden");

                    // Reset Start button
                    $("#StartSemesterBtn")
                        .text("Start Semester")
                        .prop("disabled", false)
                        .removeClass("opacity-50 cursor-not-allowed hidden");

                    $("#EndSemesterBtn").addClass("hidden");

                    // ✅ Clear and re-enable date inputs
                    $("#startDateInput, #endDateInput").val("").prop("disabled", false);
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: res.message
                });
            }
        }, "json");
    });
});
</script>

  <script src="../javascript/totalowner.js"></script>
  <script src="../javascript/totalentered.js"></script>
    <script>
        $(document).ready(function() {
            $("#menu-button").click(function() {
               $("#sidebar").toggleClass("-translate-x-full");
            });
            $("#closebtn").click(function(){
               $("#sidebar").toggleClass("-translate-x-full");
            });

            // Fetch recent access logs
            fetchRecentActivity();

            function fetchRecentActivity() {
    const container = $('#recent-activity');
    
    // Show loading placeholder
    container.html(`<p class="text-sm text-gray-500 italic">Loading recent activity...</p>`);

    $.ajax({
        url: './database/recentActivity.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            container.empty(); // Clear existing entries

            if (data.length === 0) {
                container.html(`<p class="text-sm text-gray-500 italic">No activity for today.</p>`);
                return;
            }

            data.forEach(log => {
                const actionText = log.time_out 
                    ? `Vehicle ${escapeHtml(log.plate_number)} exited campus` 
                    : `Vehicle ${escapeHtml(log.plate_number)} entered campus`;

                container.append(`
                    <div class="flex items-center justify-between py-2 border-b last:border-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" 
                                     class="lucide lucide-car">
                                     <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/>
                                     <circle cx="7" cy="17" r="2"/>
                                     <path d="M9 17h6"/>
                                     <circle cx="17" cy="17" r="2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">${actionText}</p>
                                <p class="text-sm text-gray-500">${formatTimeAgo(log.timestamp)}</p>
                            </div>
                        </div>
                    </div>
                `);
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching access logs:', error);
            container.html(`<p class="text-sm text-red-500 italic">Failed to load activity. Try again later.</p>`);
        }
    });
}

// Escapes HTML to prevent XSS
function escapeHtml(text) {
    return $('<div>').text(text).html();
}

            // Helper function: Format "time ago"
            function formatTimeAgo(timestamp) {
               const time = new Date(timestamp);
               const now = new Date();
               const secondsAgo = Math.floor((now - time) / 1000);

               if (secondsAgo < 60) return `${secondsAgo} seconds ago`;
               const minutesAgo = Math.floor(secondsAgo / 60);
               if (minutesAgo < 60) return `${minutesAgo} minutes ago`;
               const hoursAgo = Math.floor(minutesAgo / 60);
               if (hoursAgo < 24) return `${hoursAgo} hours ago`;
               const daysAgo = Math.floor(hoursAgo / 24);
               return `${daysAgo} days ago`;
            }
         });

        $(document).ready(function() {
                function fetchVehicleCounts() {
                    $.ajax({
                        url: './pre_register/get_vehicle_counts.php',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $("#approved").text(data.approved);
                            $("#pending").text(data.pending);
                            $("#rejected").text(data.rejected);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching data: " + error);
                        }
                    });
                }

                // Fetch data on page load
                fetchVehicleCounts();

                // Refresh every 10 seconds
                setInterval(fetchVehicleCounts, 10000);

                function registeredVehicleCounts() {
                    $.ajax({
                        url: './pre_register/total_registered.php',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $("#approved").text(data.registered);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching data: " + error);
                        }
                    });
                }

                // Fetch data on page load
                registeredVehicleCounts();

                // Refresh every 10 seconds
                setInterval(registeredVehicleCounts, 10000);
            });
    </script>
</body>
</html>