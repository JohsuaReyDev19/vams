<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access History</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body style="margin: 0; padding: 0;" class="bg-blue-100">
    <!-- <div class="pt-4 pl-8">
        <a href="index.php" class="flex items-center text-gray-700 hover:text-black">
            &#8592; Back
        </a>
    </div> -->
    <!-- Main Container -->
    <div class="min-h-screen flex flex-col lg:flex-row items-center justify-center px-4 gap-8">
    <!-- Left Section (Search & GIF) -->
    <div class="flex flex-col items-center w-full lg:w-auto">
        <!-- Search Bar -->
        <div class="relative w-full max-w-lg mb-8">
            <input type="text" id="rfid_scan" class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-300 opacity-0" placeholder="Search by Plate Number, Owner, or Vehicle Type...">
        </div>
        <!-- GIF Animation (Hidden on Small Screens) -->
        <div class="mt-8 hidden lg:block">
            <img src="./output-onlinegiftools.gif" alt="Loading GIF" class="w-64 md:w-80">
        </div>
    </div>

    <!-- Right Section (Recent Access Logs) -->
    <div class="w-full max-w-2xl lg:w-[900px]">
        <h1 class="text-3xl md:text-4xl font-semibold tracking-tight text-center">Recent Access</h1>
        <p class="text-gray-500 text-center">View all vehicle entry and exit records</p>
        <p id="current_datetime" class="text-gray-500 pr-8 text-center"></p>
        <!-- Scan Result -->
        <div id="scan_result" class="mt-4 text-lg font-semibold"></div>
        <!-- Loading Indicator -->
        <div id="loading" class="text-gray-500 mt-2">Loading logs...</div>
        <!-- Log Data (Scrollable) -->
        <div class="grid gap-4 overflow-y-auto max-h-[500px] p-2 border rounded-lg shadow-sm mt-4" id="log_data"></div>
    </div>
</div>





    <script>
    let lastLogID = 0; // Store last log ID to fetch only new records

    function loadLogs() {
        $.ajax({
            url: "../database/fetch_logs.php",
            type: "POST",
            data: { last_id: lastLogID },
            success: function(response) {
                let data = JSON.parse(response);
                if (data.logs.length > 0) {
                    lastLogID = data.last_id; // Update last seen log ID
                    let newLogs = "";
                    data.logs.forEach(log => {
                        let logItem = `
                            <div class="py-2 px-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 opacity-0 log-item" 
                                data-search="${log.plate_number.toLowerCase()} ${log.owner_name.toLowerCase()} ${log.vehicle_type.toLowerCase()}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center ${log.status === 'Entered' ? 'bg-green-100' : 'bg-red-100'}">
                                            ${log.vehicle_icon}
                                        </div>
                                        <div>
                                            <h3 class="font-semibold">${log.plate_number}</h3>
                                            <p class="text-sm text-gray-500">${log.owner_name}</p>
                                            <p class="text-sm text-gray-500">${log.vehicle_type}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">${log.scan_time}</p>
                                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs ${log.status === 'Entered' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'}">
                                            ${log.status}
                                        </span>
                                    </div>
                                </div>
                                ${log.alert}
                            </div>`;
                        newLogs += logItem;
                    });

                    let $newElements = $(newLogs);
                    $("#log_data").prepend($newElements);
                    
                    // Apply fade-in animation
                    setTimeout(() => {
                        $newElements.css("opacity", "1");
                    }, 100);

                }
                $("#loading").hide();
                setTimeout(loadLogs, 400); // Keep polling for new logs
            },
            error: function() {
                console.error("Error fetching logs.");
                setTimeout(loadLogs, 3000); // Retry after delay
            }
        });
    }

    // Search Functionality
    // $("#searchInput").on("keyup", function() {
    //     let searchText = $(this).val().toLowerCase();
    //     $(".log-item").each(function() {
    //         let itemText = $(this).attr("data-search");
    //         $(this).toggle(itemText.includes(searchText));
    //     });
    // });

    loadLogs();

    //for scanner
    $(document).ready(function () {
    // Keep focus on the input field
    $("#rfid_scan").focus();

    $(document).on("click", function () {
        $("#rfid_scan").focus();
    });

    // Handle RFID scanning
    $("#rfid_scan").on("keypress", function (e) {
        if (e.which == 13) { // Enter key is pressed
            let rfid = $(this).val().trim();
            
            if (rfid !== "") {
                $.post("./database/scan.php", { rfid_number: rfid }, function (response) {
                    
                    if (response.includes("Notice: Entry and exit already recorded for today")) {
                        // Toast for already recorded entry & exit
                        Swal.fire({
                            icon: "warning",
                            title: "Notice",
                            text: "Entry and exit already recorded for today. No further action needed.",
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } 
                    else if (response.includes("Success: Entered recorded")) {
                        // Toast for successful entry
                        Swal.fire({
                            icon: "success",
                            title: "Entry Recorded",
                            text: "Vehicle successfully entered.",
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else if (response.includes("RFID is not Registered")) {
                        // Toast for successful entry
                        Swal.fire({
                            icon: "error",
                            title: "Opps RFID is not Registered",
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } 
                    else if (response.includes("Success: Exited recorded")) {
                        // Toast for successful exit
                        Swal.fire({
                            icon: "info",
                            title: "Exit Recorded",
                            text: "Vehicle successfully exited.",
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } 
                    else {
                        $("#scan_result").html(response); // Show other messages in the result div
                    }
                });

                $("#rfid_scan").val("").focus(); // Clear input and refocus
            }
        }
    });

});

    //funtion for date and time
    function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
    document.getElementById('current_datetime').innerText = now.toLocaleDateString('en-US', options);
    }

    // Update every second
    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>

</body>
</html>
