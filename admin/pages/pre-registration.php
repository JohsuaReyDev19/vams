<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../../index.php"); // Redirect users who are not logged in
    exit();
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Vehicles</title>
    <style>
    .scanner-container {
      width: 180px;
      height: 180px;
      border-radius: 18px;
      background: #ffffff;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
      border: 1px solid #e2e8f0;
      position: relative;
      overflow: hidden;
    }

    .scan-line {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(to right, transparent, #0ea5e9, transparent);
      animation: scanDown 2.2s ease-in-out infinite;
      opacity: 0.9;
    }

    @keyframes scanDown {
      0% {
        top: 0;
        opacity: 0;
      }
      10% {
        opacity: 1;
      }
      50% {
        top: 100%;
        opacity: 1;
      }
      90% {
        opacity: 1;
      }
      100% {
        top: 0;
        opacity: 0;
      }
    }

    .glass-glow {
      position: absolute;
      inset: 0;
      border-radius: 18px;
      background: rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(6px);
      pointer-events: none;
      z-index: 1;
    }

    .center-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #1e293b;
      font-size: 14px;
      z-index: 2;
      font-weight: 500;
    }
  </style>
    <style>
        #ownerList {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        #ownerList::-webkit-scrollbar {
            display: none;
        }
        body {
            background-color: #f4faf9;
        }
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
<body>
<?php include './sidebar.php'; ?>

<div class="p-6 sm:ml-64">
    <div class="w-full m-auto bg-white rounded-lg p-6 mt-8 fade-in">
        <div class="w-full p-4 border-b">
            <h2 class="text-2xl font-semibold mb-6">Pre Registration</h2>

            <div class="grid grid-cols-1  lg:grid-cols-3 gap-6">
            <div class="bg-gray-100 p-6 rounded border">
               <div class="flex items-start justify-between">
                     <div>
                        <h1 class="text-lg">Pending Registrations</h1>
                        <p class="text-gray-500 text-sm ">Awaiting approval</p>
                        <h3 class="text-2xl mt-2" id="pending"></h3>
                     </div>
                     <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="fas fa-car text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </i>
                     </div>
               </div>
            </div>
            
            <div class="bg-gray-100 p-6 rounded border">
               <div class="flex items-start justify-between">
                     <div>
                        <p class="text-lg">Approved Vehicles</p>
                        <p class="text-gray-500 text-sm ">Active registrations</p>
                        <h3 class="text-2xl font-semibold mt-2" id="approved"></h3>
                     </div>
                     <div class="w-10 h-10 rounded-full bg-green-200 flex items-center justify-center">
                        <i class="fas fa-arrow-right text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big-icon lucide-circle-check-big"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>
                        </i>
                     </div>
               </div>
            </div>
            <div class="bg-gray-100 p-6 rounded border">
               <div class="flex items-start justify-between">
                     <div>
                        <p class="text-lg">Rejected Vehicles</p>
                        <p class="text-gray-500 text-sm ">Not registered</p>
                        <h3 class="text-2xl font-semibold mt-2" id="rejected"></h3>
                     </div>
                     <div class="w-10 h-10 rounded-full bg-red-200 flex items-center justify-center">
                        <i class="fas fa-arrow-right text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </i>
                     </div>
               </div>
            </div>
            </div>
         </div>
            <!-- Filter Buttons -->
            <div class="bg-gray-100 flex flex-wrap justify-start mt-4 gap-4 rounded">
                <button onclick="fetchVehicles()" data-filter="pending" class="filter-btn flex justify-center items-center gap-2 text-gray-700 px-4 py-2 rounded font-semibold hover:bg-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>    
                    Pending
                </button>
                <button onclick="fetchVehiclesApproved()" data-filter="approved" class="filter-btn flex justify-center items-center gap-2 text-gray-700 px-4 py-2 rounded font-semibold hover:bg-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>    
                    Approved
                </button>
                <button onclick="fetchRejectedVehicles()" data-filter="rejected" class="filter-btn flex justify-center items-center gap-2 text-gray-700 px-4 py-2 rounded font-semibold hover:bg-gray-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>    
                    Rejected
                </button>
            </div>
        </div>

        <!-- Vehicle List -->
        <div id="vehicleList" class="overflow-x-auto mt-6">
                
        </div>
    </div>
</div>
    <!-- Modal Structure -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="relative">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-white bg-gray-800 px-3 py-1 rounded-full">X</button>
            <img id="modalImage" class="max-w-full max-h-screen rounded-md shadow-lg" />
        </div>
    </div>
    <!-- RFID Input Modal -->
    <div id="rfidModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4">Scan the RFID Sticker</h2>
            <div id="scanbar" class="flex justify-center items-center m-3">
                 <div class="scanner-container">
                    <div class="scan-line"></div>
                    <div class="glass-glow"></div>
                    <div class="center-text">Scanning...</div>
                </div>
            </div>
            <input type="text" id="rfidInput" class="w-full border p-2 rounded mb-4 hidden" placeholder="Scan the RFID here" required>
            <div id="plate_status"></div>
            <div class="flex justify-end gap-2">
                <button id="cancelModalBtn" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                <button id="submitModalBtn" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 hidden" disabled>Approved</button>
            </div>
        </div>
    </div>
    
    <!-- Fullscreen Loading Overlay -->
    <div id="loader" class="fixed inset-0 flex items-center justify-center bg-gray-500
        bg-opacity-50 z-50 hidden">
        <div class="loading loading-bars loading-lg text-yellow-600">

        </div>
    </div>

        <script>

            function fetchTag() {
                fetch('../../scanprocess/read_tag.php')
                    .then(response => response.text())
                    .then(tag => {
                        const input = document.getElementById("rfidInput");
                        const cleanTag = tag.trim();

                        // If a valid tag is read (not empty or "Waiting for tag...")
                        if (cleanTag && cleanTag !== "Waiting for tag...") {
                            input.value = cleanTag;
                            const scanbar = document.getElementById('scanbar');
                            const btnApprove = document.getElementById('submitModalBtn');
                            const rfidInput = document.getElementById('rfidInput');
                            scanbar.classList.add('hidden');
                            btnApprove.classList.remove('hidden');
                            rfidInput.classList.remove('hidden');
                            // Trigger keyup for any validation handlers
                            input.dispatchEvent(new Event('keyup'));

                            // Simulate "Enter" key press
                            const enterEvent = new KeyboardEvent('keyup', {
                                key: 'Enter',
                                keyCode: 13,
                                which: 13,
                                bubbles: true
                            });
                            input.dispatchEvent(enterEvent);
                        }
                    });
            }

            setInterval(fetchTag, 1000); // Poll every second

            $(document).on("click", "#cancelModalBtn", function () {
                $("#rfidModal").addClass('hidden');
                $("#rfidInput").val(""); // Clear input
                $("#plate_status").html(""); // Clear status
            });
            $(document).on("keyup", "#rfidInput", function () {
                let rfidTag = $(this).val();
                if (rfidTag.length > 2) {
                    $.ajax({
                        type: "POST",
                        url: "../backend/check_rfid.php",
                        data: { rfid_tag: rfidTag },
                        dataType: "json",
                        success: function (response) {
                            if (response.exists) {
                                $("#plate_status").html('<span style="color: red;">' + response.message + '</span>');
                                $("#submitModalBtn").prop('disabled', true); // Disable Submit button
                                $("#submitModalBtn").addClass('bg-gray-400 hover:bg-gray-500').removeClass('bg-green-500 hover:bg-green-600');
                            } else {
                                $("#plate_status").html('<span style="color: green;">RFID tag available.</span>');
                                $("#submitModalBtn").prop('disabled', false); // Enable Submit button
                                $("#submitModalBtn").addClass('bg-green-500 hover:bg-green-600').removeClass('bg-gray-400 hover:bg-gray-500');
                            }
                        },
                        error: function () {
                            $("#plate_status").html('<span style="color: red;">Error checking RFID tag.</span>');
                            $("#submitModalBtn").prop('disabled', true); // Disable on error
                            $("#submitModalBtn").addClass('bg-gray-400 hover:bg-gray-500').removeClass('bg-green-500 hover:bg-green-600');
                        }
                    });
                } else {
                    $("#plate_status").html("");
                    $("#submitModalBtn").prop('disabled', true); // Disable if input is too short
                    $("#submitModalBtn").addClass('bg-gray-400 hover:bg-gray-500').removeClass('bg-green-500 hover:bg-green-600');
                }
            });

            // Optional: Disable submit button by default when modal opens
            $(document).ready(function() {
                $("#submitModalBtn").prop('disabled', true);
            });
        </script>


    <script>
        function fetchVehicles() {
            const loader = document.getElementById('loader');
            loader.classList.remove('hidden');
    fetch('../pre_register/get_vehicles.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const vehicleListDiv = document.getElementById('vehicleList');
                vehicleListDiv.innerHTML = ''; // Clear previous data

                let tableHTML = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 py-1 text-left text-xs">Image</th>
                                    <th class="px-2 py-1 text-left text-xs">Plate Number</th>
                                    <th class="px-2 py-1 text-left text-xs">Vehicle Type</th>
                                    <th class="px-2 py-1 text-left text-xs">Owner</th>
                                    <th class="px-2 py-1 text-left text-xs">Owner Type</th>
                                    <th class="px-2 py-1 text-left text-xs">Contact</th>
                                    <th class="px-2 py-1 text-left text-xs">Status</th>
                                    <th class="px-2 py-1 text-left text-xs">Date</th>
                                    <th class="px-2 py-1 text-center text-xs">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                data.vehicles.reverse().forEach(vehicle => {
                    // Format the date
                    let formattedDate = 'N/A';
                    if (vehicle.registered_at) {
                        const dateObj = new Date(vehicle.registered_at);
                        formattedDate = dateObj.toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });
                        //add hidden to loading
                        loader.classList.add('hidden');
                    }

                    tableHTML += `
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-2 py-1">
                                <img src="${vehicle.vehicle_image}" alt="Vehicle Image" class="w-16 h-10 object-cover rounded-lg cursor-pointer" onclick="showModal('${vehicle.vehicle_image}')">
                            </td>
                            <td class="px-2 py-1 text-xs">${vehicle.plate_number}</td>
                            <td class="px-2 py-1 text-xs">${vehicle.vehicle_type}</td>
                            <td class="px-2 py-1 text-xs">${vehicle.owner_details.split(',')[0]}</td>
                            <td class="px-2 py-1 text-xs">
                                <span class="bg-red-300 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    ${vehicle.owner_type.toUpperCase()}
                                </span>
                            </td>
                            <td class="px-2 py-1 text-xs">${vehicle.owner_email || 'N/A'}</td>
                            <td class="px-2 py-1 text-xs">
                                <span class="bg-yellow-400 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    ${vehicle.status}
                                </span>
                            </td>
                            <td class="px-2 py-1 text-xs text-blue-500">${formattedDate}</td>
                            <td class="px-2 py-1 text-center">
                                <button onclick="approveVehicle(${vehicle.vehicle_id}, '${vehicle.owner_email}', '${vehicle.owner_details.split(',')[0]}', '${vehicle.owner_type}')" class="text-xs px-2 py-1 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition">
                                    Approve
                                </button>
                                <button onclick="updateVehicleStatus(${vehicle.vehicle_id}, 'Rejected', '${vehicle.owner_email}', '${vehicle.owner_details.split(',')[0]}', '${vehicle.owner_type}')" class="text-xs px-2 py-1 bg-red-500 text-white rounded-lg shadow-md hover:bg-red-600 transition ml-2">
                                    Reject
                                </button>
                            </td>
                        </tr>
                    `;
                });

                tableHTML += `</tbody></table></div>`;
                vehicleListDiv.innerHTML = tableHTML;
            } else {
                document.getElementById('vehicleList').textContent = data.message;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('vehicleList').textContent = 'An error occurred.';
        });
}

        // Initial call to fetch vehicles when the page loads
function fetchVehiclesApproved() {
    fetch('../backend/get_registered.php')
        .then(response => response.json())
        .then(data => {
            const vehicleListDiv = document.getElementById('vehicleList');
            vehicleListDiv.innerHTML = '';

            if (data.success) {
                let tableHTML = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 py-1 text-left">Image</th>
                                    <th class="px-2 py-1 text-left">Plate #</th>
                                    <th class="px-2 py-1 text-left">Type</th>
                                    <th class="px-2 py-1 text-left">Owner</th>
                                    <th class="px-2 py-1 text-left">Role</th>
                                    <th class="px-2 py-1 text-left">Date</th>
                                    <th class="px-2 py-1 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                const reversedVehicles = [...data.vehicles].reverse();

                reversedVehicles.forEach(vehicle => {
                    const registeredDate = vehicle.registered_at
                        ? new Date(vehicle.registered_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        })
                        : 'N/A';

                    tableHTML += `
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-2 py-1">
                                <img src="${vehicle.vehicle_image}" alt="Vehicle" class="w-12 h-10 object-cover rounded cursor-pointer" onclick="showModal('${vehicle.vehicle_image}')">
                            </td>
                            <td class="px-2 py-1">${vehicle.plate_number}</td>
                            <td class="px-2 py-1">${vehicle.vehicle_type}</td>
                            <td class="px-2 py-1">${vehicle.owner_details.split(',')[0]}</td>
                            <td class="px-2 py-1">
                                <span class="bg-blue-400 text-white text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                    ${vehicle.owner_type.toUpperCase()}
                                </span>
                            </td>
                            <td class="px-2 py-1 text-blue-500">${registeredDate}</td>
                            <td class="px-2 py-1 text-center">
                                <span class="bg-green-500 text-white text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                    ${vehicle.status}
                                </span>
                            </td>
                        </tr>
                    `;
                });

                tableHTML += `</tbody></table></div>`;
                vehicleListDiv.innerHTML = tableHTML;
            } else {
                vehicleListDiv.textContent = data.message;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('vehicleList').textContent = 'An error occurred.';
        });
}



        function fetchRejectedVehicles() {
    fetch('../backend/get_rejected.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const vehicleListDiv = document.getElementById('vehicleList');
                vehicleListDiv.innerHTML = '';

                let tableHTML = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-2 py-1 text-left">Image</th>
                                    <th class="px-2 py-1 text-left">Plate #</th>
                                    <th class="px-2 py-1 text-left">Type</th>
                                    <th class="px-2 py-1 text-left">Owner</th>
                                    <th class="px-2 py-1 text-left">Role</th>
                                    <th class="px-2 py-1 text-left">Date</th>
                                    <th class="px-2 py-1 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                const reversedVehicles = [...data.vehicles].reverse();

                reversedVehicles.forEach(vehicle => {
                    const registeredDate = vehicle.registered_at
                        ? new Date(vehicle.registered_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        })
                        : 'N/A';

                    tableHTML += `
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-2 py-1">
                                <img src="${vehicle.vehicle_image}" alt="Vehicle" class="w-12 h-10 object-cover rounded cursor-pointer" onclick="showModal('${vehicle.vehicle_image}')">
                            </td>
                            <td class="px-2 py-1">${vehicle.plate_number}</td>
                            <td class="px-2 py-1">${vehicle.vehicle_type}</td>
                            <td class="px-2 py-1">${vehicle.owner_details.split(',')[0]}</td>
                            <td class="px-2 py-1">
                                <span class="bg-gray-500 text-white text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                    ${vehicle.owner_type.toUpperCase()}
                                </span>
                            </td>
                            <td class="px-2 py-1 text-blue-500">${registeredDate}</td>
                            <td class="px-2 py-1 text-center">
                                <span class="bg-red-500 text-white text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                    ${vehicle.status}
                                </span>
                            </td>
                        </tr>
                    `;
                });

                tableHTML += `</tbody></table></div>`;
                vehicleListDiv.innerHTML = tableHTML;
            } else {
                document.getElementById('vehicleList').textContent = data.message;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('vehicleList').textContent = 'An error occurred.';
        });
}

// Initial call to fetch rejected vehicles when the page loads
let currentVehicleId = null; // Global variable to hold vehicleId when modal opens

function approveVehicle(vehicleId, email, name, ownerType) {
    currentVehicleId = vehicleId;
    owner_email = email;
    ownername = name;
    ownertype = ownerType;
    document.getElementById('rfidInput').value = ''; // Clear previous input
    document.getElementById('rfidModal').classList.remove('hidden');
}

// Handle modal buttons
document.getElementById('rfidInput').addEventListener('input', function() {
    const submitBtn = document.getElementById('submitModalBtn');
    if (this.value.trim() !== '') {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
});

// Handle Submit Button
document.getElementById('submitModalBtn').addEventListener('click', function() {
    loader.classList.remove('hidden'); // Show loader
    const submitBtn = this; // Save reference
    const rfidTag = document.getElementById('rfidInput').value.trim();

    if (!rfidTag) {
        Swal.fire("Error", "Please enter the RFID Tag.", "error");
        return;
    }

    // Show loading spinner
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
    </svg> Saving...`;

    fetch('../pre_register/update_vehicle_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `vehicle_id=${currentVehicleId}&rfid_tag=${encodeURIComponent(rfidTag)}&email=${owner_email}&name=${ownername}&owner_type=${ownertype}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {

            Swal.fire("Approved Successful!");
            fetchVehicles();
        } else {
            Swal.fire("Error!", data.message, "error");
        }
        closeRfidModal();
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire("Error!", "An error occurred while approving the vehicle.", "error");
        closeRfidModal();
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = "Save";
    });
});

function updateVehicleStatus(vehicleId,status, email, name, ownerType) {
    if (status === 'Rejected') {
        // Show a confirmation prompt before rejecting
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to reject this vehicle?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Reject it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                loader.classList.remove('hidden');
                // If user confirms rejection, send the request to update the status
                sendStatusUpdateRequest(vehicleId, status, email, name, ownerType);
            } else {
                // If user cancels, do nothing
                Swal.fire('Canceled', 'The vehicle has not been rejected.', 'info');
            }
        });
    } else {
        // If the status is not 'Rejected', directly send the request
        sendStatusUpdateRequest(vehicleId,status, email, name, ownerType);
    }
}

// Helper function to send the request to the backend
function sendStatusUpdateRequest(vehicleId, status, email, name, ownerType) {
    const loader = document.getElementById('loader'); // Ensure this references the loader element
    loader.classList.remove('hidden'); // Show loader

    fetch('../pre_register/rejectvehicle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `vehicle_id=${vehicleId}&status=${encodeURIComponent(status)}&email=${encodeURIComponent(email)}&name=${encodeURIComponent(name)}&owner_type=${encodeURIComponent(ownerType)}`
    })
    .then(response => response.json())
    .then(data => {
        loader.classList.add('hidden'); // Hide loader after response

        if (data.success) {
            Swal.fire("Action Successful!", "Vehicle has been " + status + ".", "success");
            fetchVehicles(); // Refresh the list
        } else {
            Swal.fire("Error!", data.message, "error");
        }
    })
    .catch(error => {
        loader.classList.add('hidden'); // Hide loader on error too
        console.error('Error:', error);
        Swal.fire("Error!", "An error occurred while updating vehicle status.", "error");
    });
}


// Helper to close modal
function closeRfidModal() {
    document.getElementById('rfidModal').classList.add('hidden');
}



        function showModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        fetchVehicles(); // Load vehicles on page load


        // this for button action 
        document.addEventListener("DOMContentLoaded", function () {
        const filterButtons = document.querySelectorAll(".filter-btn");

        filterButtons.forEach(button => {
            button.addEventListener("click", function () {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove("bg-white", "shadow-md"));

                // Add active class to clicked button
                this.classList.add("bg-white", "shadow-md");
            });
        });
    }); 

    </script>

            <!-- for count -->
            <script>
            $(document).ready(function() {
                function fetchVehicleCounts() {
                    $.ajax({
                        url: '../pre_register/get_vehicle_counts.php',
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
            });
            </script>
</body>
</html>
