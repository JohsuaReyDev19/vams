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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <title>Vehicles</title>
    <style>
        #ownerList {
            scrollbar-width: none; /* Hide scrollbar for Firefox */
            -ms-overflow-style: none; /* Hide scrollbar for IE and Edge */
        }

        #ownerList::-webkit-scrollbar {
            display: none; /* Hide scrollbar for Chrome, Safari, and Edge */
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
    <?php include './sidebar.php'?>
  <div class="p-6 sm:ml-64">
    <div class="w-full mx-auto" id="vehicleshow">
                    <div class="w-full mt-12">
                        <h1 class="font-bold text-3xl mb-2">Vehicle Management</h1>
                        <p class="text-gray-500">Registered vehicles, edit and manage vehicles with RFID access</p>
                    </div>
                    <div class="bg-gray-100 p-6 rounded-xl flex flex-wrap gap-4 justify-between items-center my-3">
                    <!-- Filters Section -->
                    <div class="w-full flex flex-col md:flex-row md:items-center md:justify-between md:gap-4 space-y-2 md:space-y-0">
    
                        <!-- Search with icon -->   
                        <div class="relative w-full md:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <!-- Search Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                    class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                                </svg>
                            </span>
                            <input type="text" id="search" placeholder="Search owners..." 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none 
                                    focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-sm text-sm"
                            >
                        </div>

                        <!-- Right-side controls -->
                        <?php include '../semesterdate/date_input.php'; ?>
                    </div>
                
                    <!-- vehicle list -->
                </div>
                    <div id="vehicleList" class="bg-white p-4 rounded-lg"></div>

                </div>
                <!-- Image Modal -->
                <div id="imageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
                    <div class="relative max-w-4xl w-full mx-4 rounded-2xl overflow-hidden shadow-2xl bg-white">
                        
                        <!-- Close button -->
                        <button onclick="closeModal()" 
                            class="absolute top-3 right-3 bg-black/60 hover:bg-black/80 text-white rounded-full p-2 transition">
                            ✕
                        </button>

                        <!-- Image -->
                        <img id="modalImage" 
                            class="w-full h-auto max-h-[85vh] object-contain bg-black" 
                            alt="Preview Image" />
                        
                    </div>
                </div>

            </div>
  </div>

<!-- Edit Modal -->
<div id="editModal" class="hidden px-2 fixed inset-0 flex justify-center items-center bg-gray-600 bg-opacity-50 z-50 fade-in">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-3/4 md:w-1/2 lg:w-1/3 xl:w-1/4">
        <h2 class="text-xl font-semibold mb-4">Edit Vehicle Details</h2>
        <input type="hidden" id="editVehicleId">
        
        <label for="editPlateNumber" class="block text-sm font-medium text-gray-700">Plate Number</label>
        <input type="text" id="editPlateNumber" class="w-full border px-2 py-1 rounded">

        <label for="editVehicleType" class="block text-sm font-medium text-gray-700 mt-4">Vehicle Type</label>
            <select id="editVehicleType" class="mt-1 p-2 w-full border rounded-md" required>
            <option value="" disabled selected>Select vehicle type</option>
            <option value="Sedan">Sedan</option>
            <option value="SUV">SUV</option>
            <option value="Truck">Truck</option>
            <option value="Van">Van</option>
            <option value="Motorcycle">Motorcycle</option>
        </select>

        <input type="text" id="editOwnerType" hidden class="mt-1 p-2 w-full border rounded-md" required>

        <label for="editOwnerName" class="block text-sm font-medium text-gray-700 mt-4">Owner Name</label>
        <input type="text" id="editOwnerName" class="w-full border px-2 py-1 rounded" required>

        <div class="hidden">
            <label for="editExtraInfo" class="block text-sm font-medium text-gray-700 mt-4">Dept/Course</label>
            <input type="text" id="editExtraInfo" class="w-full border px-2 py-1 rounded">
        </div>
        
        <input type="hidden" id="editVehicle_ID"  class="w-full border px-2 py-1 rounded">
        <div>
            <label for="editRfidTag" class="block text-sm font-medium text-gray-700 mt-4">RFID Tag</label>
            <div class="flex items-center gap-1">
                <input type="text" id="editRfidTag" class="w-full border px-2 py-1 rounded" readonly>
                <span id="scanRfid" class="bg-green-500 text-white p-1 rounded-md" title="change rfid">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw-icon lucide-refresh-cw"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
            </span>
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-4">
            <button onclick="closeEditModal()" class="bg-gray-400 text-white px-4 py-2 rounded-md">Cancel</button>
            <button onclick="saveVehicleDetails()" class="bg-blue-500 text-white px-4 py-2 rounded-md">Save</button>
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

<!-- Fullscreen Loading Overlay -->
<div id="loader" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-50 hidden">
  <div class="loading loading-bars loading-lg text-yellow-600"></div>
</div>

<?php
    include '../semesterdate/set_semester.php';
?>

  <script>

     /** 📡 RFID Scan Loop **/
    function startRFIDScan() {
        const intervalId = setInterval(() => {
            fetch('../../scanprocess/read_tag.php')
                .then(response => response.text())
                .then(tag => {
                    const cleanTag = tag.trim();
                    const input = $('#rfidInput');

                    if (cleanTag && cleanTag !== "Waiting for tag...") {
                        input.val(cleanTag);
                        $('#scanbar').addClass('hidden');
                        $('#submitModalBtn').removeClass('hidden').prop('disabled', false);
                        input.removeClass('hidden');

                        input.trigger('input');
                        clearInterval(intervalId); // stop scanning
                    }
                })
                .catch(err => console.error("RFID Scan Error:", err));
        }, 500);
    }

    /** 📡 Open RFID Modal **/
    $('#scanRfid').on('click', function () {
        $('#rfidModal').removeClass('hidden');
        $('#rfidInput').val('').removeClass('hidden');
        $('#submitModalBtn').addClass('hidden').prop('disabled', true);
        startRFIDScan(); // Start scanning on modal open
    });

    /** ❌ Cancel RFID Modal **/
    $('#cancelModalBtn').on('click', function () {
        $('#rfidModal').addClass('hidden');
    });

    /** 🎯 RFID Input Change **/
    $('#rfidInput').on('input', function () {
        let tagValue = $(this).val().trim();
        $('#submitModalBtn').toggleClass('hidden', tagValue.length === 0).prop('disabled', tagValue.length === 0);
    });

    /** ✅ Submit RFID from Modal to Form **/
    $('#submitModalBtn').on('click', function () {
        let tagValue = $('#rfidInput').val().trim();
        $('#editRfidTag').val(tagValue);
        $('#rfidModal').addClass('hidden');
        $('#clear').removeClass('hidden');
    });


    document.addEventListener("DOMContentLoaded", function () {
    fetchVehicleOwners();

    document.getElementById("search").addEventListener("input", function () {
        const searchTerm = this.value.toLowerCase();
        filterVehicleList(searchTerm);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    fetchVehicleOwners(); // Load all vehicles at first

    document.getElementById("vehiclesearch").addEventListener("change", function () {
        const selectedType = this.value.toLowerCase();
        filterVehicleList(selectedType);
    });
});

function filterVehicleList(selectedType) {
    // Assuming you have the vehicles stored somewhere after fetching, example:
    const filteredVehicles = vehicles.filter(vehicle => {
        // If no type selected, show all vehicles
        if (selectedType === '') return true;
        // Otherwise, match vehicle_type
        return vehicle.vehicle_type.toLowerCase() === selectedType;
    });

    renderVehicleList(filteredVehicles);
}


let vehicles = []; // global storage

function fetchVehicleOwners() {
    const loader = document.getElementById('loader');
    loader.classList.remove('hidden'); // Show loader before AJAX

    fetch('../backend/fetch_vehicle.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Sort by registered_at in descending order (newest first)
                vehicles = data.vehicles.sort((a, b) => new Date(b.registered_at) - new Date(a.registered_at));
                renderVehicleList(vehicles);
            } else {
                document.getElementById('vehicleList').textContent = data.message;
                loader.classList.add('hidden'); // Show loader before AJAX

            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('vehicleList').textContent = 'An error occurred.';
        });
}

function renderVehicleList(vehicles) {
    loader.classList.add('hidden'); 
    const vehicleListDiv = document.getElementById('vehicleList');
    vehicleListDiv.innerHTML = '';

    let tableHTML = `
        <div class="overflow-x-auto w-full">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md text-xs sm:text-xs"> <!-- Smaller font -->
                <thead class="bg-gray-100">
                    <tr class="text-center">
                        <th class="px-2 sm:px-2 py-1.5 whitespace-nowrap">Image</th>
                        <th class="px-2 sm:px-2 py-1.5 whitespace-nowrap">Plate/Mv file file</th>
                        <th class="px-2 sm:px-2 py-1.5">Type</th>
                        <th class="px-2 sm:px-2 py-1.5">RFID</th>
                        <th class="px-2 sm:px-2 py-1.5">Owner Type</th>
                        <th class="px-2 sm:px-2 py-1.5">Name</th>
                        <th class="px-2 sm:px-2 py-1.5 text-center">Dept/Course</th>
                        <th class="px-2 sm:px-2 py-1.5">Contact</th>
                        <th class="px-2 sm:px-2 py-1.5">Registered at</th>
                        <th class="px-2 sm:px-2 py-1.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;

    vehicles.forEach(vehicle => {
        const registeredDate = vehicle.registered_at
            ? new Date(vehicle.registered_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            })
            : 'N/A';
        tableHTML += `
            <tr class="text-center border-b border-gray-200 hover:bg-gray-50">
                <td class="px-2 sm:px-2 py-1.5">
                    <img src="${vehicle.vehicle_image}" alt="Vehicle" class="m-auto w-12 h-8 sm:w-14 sm:h-12 object-cover rounded-md cursor-pointer"
                        onerror="this.src='../images/default_vehicle.jpg';"
                        onclick="showModal('${vehicle.vehicle_image}')">
                </td>
                <td class="px-2 sm:px-2 py-1.5 truncate max-w-[80px]">${vehicle.plate_number}</td>
                <td class="px-2 sm:px-2 py-1.5">${vehicle.vehicle_type}</td>
                <td class="px-2 sm:px-2 py-1.5">${vehicle.rfid_tag || 'N/A'}</td>
                <td class="px-2 sm:px-2 py-1.5 font-semibold">${vehicle.owner_type}</td>
                <td class="px-2 sm:px-2 py-1.5">${vehicle.owner_name}</td>
                <td class="px-2 sm:px-2 py-1.5 text-center">${vehicle.extra_info || 'N/A'}</td>
                <td class="px-2 sm:px-2 py-1.5 text-center">${vehicle.owner_email || 'N/A'}</td>
                <td class="px-2 sm:px-2 py-1.5 text-blue-500 whitespace-nowrap">${registeredDate}</td>
                <td class="px-2 sm:px-2 py-1.5 text-center">
                    <div class="flex justify-center space-x-2 items-center">
                        <!-- View Profile -->
                        <a href="./viewDetails.php?id=${vehicle.id}&name=${encodeURIComponent(vehicle.owner_name)}&purpose=${encodeURIComponent(vehicle.owner_type)}&contact=${encodeURIComponent(vehicle.owner_email)}" title="View Owner's Other Vehicles" 
                            class="bg-blue-100 text-green-600 hover:bg-green-200 rounded p-1.5 transition">
                            View
                        </a>

                        <!-- Edit Button -->
                        <button class="bg-blue-100 text-blue-600 rounded p-1.5" onclick="showEditModal(${vehicle.id}, '${vehicle.owner_type}', ${vehicle.id})">Edit</button>

                        <!-- Delete Button -->
                        <button class="bg-red-100 text-red-600 rounded p-1.5" onclick="deleteVehicle(${vehicle.id}, ${vehicle.vehicle_id})">Remove</button>
                    </div>
                </td>
            </tr>
        `;
    });

    tableHTML += `</tbody></table></div>`;
    vehicleListDiv.innerHTML = tableHTML;
}



function filterVehicleList(searchTerm) {
    const filtered = vehicles.filter(vehicle => {
        const allData = Object.values(vehicle).join(' ').toLowerCase();
        return allData.includes(searchTerm);
    });

    // Sort filtered results so newest entries appear first
    const sortedFiltered = filtered.sort((a, b) => new Date(b.registered_at) - new Date(a.registered_at));
    renderVehicleList(sortedFiltered);
}

// Function to show image in modal
function showModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

// Function to close the image modal
function closeModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Function to show the Edit modal with current vehicle data
function showEditModal(vehicleId, ownerType, vehicleRefId) {
    console.log('Fetching vehicle details for ID:', vehicleId);
    console.log('Fetching vehicle details for OwnerType:', ownerType);
    console.log('Fetching vehicle_id details for VehicleID:', vehicleRefId);

    const url = `../backend/vehicles.php?id=${encodeURIComponent(vehicleId)}&ownertype=${encodeURIComponent(ownerType)}&vehicle_id=${encodeURIComponent(vehicleRefId)}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const vehicle = data.vehicle;

                // Populate the modal with the current vehicle details
                document.getElementById('editPlateNumber').value = vehicle.plate_number;
                document.getElementById('editVehicleType').value = vehicle.vehicle_type;
                document.getElementById('editRfidTag').value = vehicle.rfid_tag || '';
                document.getElementById('editOwnerType').value = vehicle.owner_type;
                document.getElementById('editOwnerName').value = vehicle.owner_name;
                document.getElementById('editExtraInfo').value = vehicle.extra_info || '';
                document.getElementById('editVehicle_ID').value = vehicle.vehicle_id;
                
                
                // Show the modal
                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editVehicleId').value = vehicle.id;
            } else {
                alert("Failed to fetch vehicle details.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching vehicle details.');
        });
}


function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function saveVehicleDetails() {
    const vehicleId   = document.getElementById('editVehicleId').value;
    const plateNumber = document.getElementById('editPlateNumber').value;
    const vehicleType = document.getElementById('editVehicleType').value;
    const rfidTag     = document.getElementById('editRfidTag').value;
    const ownerType   = document.getElementById('editOwnerType').value;
    const ownerName   = document.getElementById('editOwnerName').value;
    const extraInfo   = document.getElementById('editExtraInfo').value;
    const vehicleID   = document.getElementById('editVehicle_ID').value;

    const updatedVehicle = {
        id: vehicleId,
        plate_number: plateNumber,
        vehicle_type: vehicleType,
        rfid_tag: rfidTag,
        owner_type: ownerType,
        owner_name: ownerName,
        extra_info: extraInfo,
        vehicle_ID: vehicleID
    };

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to update this vehicle\'s details?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../backend/update_vehicle.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(updatedVehicle)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire('Updated!', data.message, 'success');
                    fetchVehicleOwners();   // refresh table
                    closeEditModal();       // close modal
                } else {
                    // Show different alerts for specific validation issues
                    if (data.message.includes('RFID')) {
                        Swal.fire('Duplicate RFID', data.message, 'warning');
                    } else if (data.message.includes('Plate')) {
                        Swal.fire('Duplicate Plate', data.message, 'warning');
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred: ' + error.message, 'error');
            });
        } else {
            Swal.fire('Cancelled', 'The update was cancelled.', 'info');
        }
    });
}

function deleteVehicle(vehicleId, vehicle_id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to remove this Owner? and remove all his other vehicles',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../backend/delete_vehicle.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: vehicleId, vehicle_id: vehicle_id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'The owner was removed successfully.', 'success');
                    fetchVehicleOwners(); // refresh list
                } else {
                    Swal.fire('Error', data.message || 'Error removing the vehicle.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while removing the owner.', 'error');
            });
        } else {
            Swal.fire('Cancelled', 'The owner was not removed.', 'info');
        }
    });
}




// Ensure function is correctly called
fetchVehicleOwners();


  </script>
  
    <!-- Main modal -->
</body>
</html>