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
                        <p class="text-gray-500">Register, edit and manage vehicles with RFID access</p>
                    </div>
                    <div class="bg-gray-100 p-6 rounded-xl flex flex-wrap gap-4 justify-between items-center my-6">
                    <!-- Filters Section -->
                    <div class="w-full flex flex-col md:flex-row md:items-center md:gap-4 space-y-2 md:space-y-0">
                        <input type="text" id="search" placeholder="Search owners..." 
                            class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-sm text-sm"
                        >
                        <select id="vehiclesearch" 
                            class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-sm text-sm">
                            <option value="">All Vehicle Types</option>
                            <option value="Car">Car</option>
                            <option value="Motorcycle">Motorcycle</option>
                        </select>
                    </div>
                
                    <!-- vehicle list -->
                </div>
                    <div id="vehicleList" class="bg-white p-4 rounded-lg"></div>
                </div>

                <!-- image modal -->
                <div id="imageModal" class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-75 flex justify-center items-center z-50">
                    <div class="relative">
                        <button onclick="closeModal()" class="bg-gray-700 rounded-full absolute top-0 right-0 p-2 text-white text-xl">×</button>
                        <img id="modalImage" class="max-w-full max-h-screen object-contain" alt="Modal Image" />
                    </div>
                </div>

            </div>
  </div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 flex justify-center items-center bg-gray-600 bg-opacity-50 z-50">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-3/4 md:w-1/2 lg:w-1/3 xl:w-1/4">
        <h2 class="text-xl font-semibold mb-4">Edit Vehicle Details</h2>
        <input type="hidden" id="editVehicleId">
        
        <label for="editPlateNumber" class="block text-sm font-medium text-gray-700">Plate Number</label>
        <input type="text" id="editPlateNumber" class="mt-1 p-2 w-full border rounded-md">

        <label for="editVehicleType" class="block text-sm font-medium text-gray-700 mt-4">Vehicle Type</label>
            <select id="editVehicleType" class="mt-1 p-2 w-full border rounded-md" required>
            <option value="" disabled selected>Select vehicle type</option>
            <option value="Car">Car</option>
            <option value="Motorcycle">Motorcycle</option>
            <option value="Van">Van</option>
        </select>

        <label for="editRfidTag" class="block text-sm font-medium text-gray-700 mt-4">RFID Tag</label>
        <input type="text" id="editRfidTag" class="mt-1 p-2 w-full border rounded-md">

        <input type="text" id="editOwnerType" hidden class="mt-1 p-2 w-full border rounded-md" required>

        <label for="editOwnerName" class="block text-sm font-medium text-gray-700 mt-4">Owner Name</label>
        <input type="text" id="editOwnerName" class="mt-1 p-2 w-full border rounded-md" required>

        <label for="editExtraInfo" class="block text-sm font-medium text-gray-700 mt-4">Dept/Course</label>
        <input type="text" id="editExtraInfo" class="mt-1 p-2 w-full border rounded-md">
        
        <input type="hidden" id="editVehicle_ID"  class="mt-1 p-2 w-full border rounded-md">

        <div class="mt-6 flex justify-end space-x-4">
            <button onclick="closeEditModal()" class="bg-gray-400 text-white px-4 py-2 rounded-md">Cancel</button>
            <button onclick="saveVehicleDetails()" class="bg-blue-500 text-white px-4 py-2 rounded-md">Save</button>
        </div>
    </div>
</div>

<!-- Fullscreen Loading Overlay -->
<div id="loader" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-50 hidden">
  <div class="loading loading-bars loading-lg text-yellow-600"></div>
</div>

  <script>
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
                        <th class="px-2 sm:px-2 py-1.5 whitespace-nowrap">Plate</th>
                        <th class="px-2 sm:px-2 py-1.5">Type</th>
                        <th class="px-2 sm:px-2 py-1.5">RFID</th>
                        <th class="px-2 sm:px-2 py-1.5">Owner Type</th>
                        <th class="px-2 sm:px-2 py-1.5">Name</th>
                        <th class="px-2 sm:px-2 py-1.5 text-center">Dept/Course</th>
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
                <td class="px-2 sm:px-2 py-1.5 text-blue-500 whitespace-nowrap">${registeredDate}</td>
                <td class="px-2 sm:px-2 py-1.5 text-center">
                    <div class="flex justify-center space-x-2 items-center">
                        <!-- View Profile -->
                        <button onclick="showEditModal(${vehicle.id}, '${vehicle.owner_type}', ${vehicle.id})" 
                            class="bg-blue-100 text-green-600 hover:bg-green-200 rounded p-1.5 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" 
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 13a3 3 0 1 0-6 0"/>
                                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>
                                <circle cx="12" cy="8" r="2"/>
                            </svg>
                        </button>

                        <!-- Edit Button -->
                        <button onclick="showEditModal(${vehicle.id}, '${vehicle.owner_type}', ${vehicle.id})" 
                            class="bg-blue-100 text-blue-600 hover:bg-blue-200 rounded p-1.5 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" 
                                stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 21a8 8 0 0 1 10.821-7.487"/>
                                <path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/>
                                <circle cx="10" cy="8" r="5"/>
                            </svg>
                        </button>

                        <!-- Delete Button -->
                        <button onclick="deleteVehicle(${vehicle.id}, ${vehicle.vehicle_id})" 
                            class="bg-red-100 text-red-600 hover:bg-red-200 rounded p-1.5 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" 
                                stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"/>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                <line x1="10" x2="10" y1="11" y2="17"/>
                                <line x1="14" x2="14" y1="11" y2="17"/>
                            </svg>
                        </button>
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
    const vehicleId = document.getElementById('editVehicleId').value;
    const plateNumber = document.getElementById('editPlateNumber').value;
    const vehicleType = document.getElementById('editVehicleType').value;
    const rfidTag = document.getElementById('editRfidTag').value;
    const ownerType = document.getElementById('editOwnerType').value;
    const ownerName = document.getElementById('editOwnerName').value;
    const extraInfo = document.getElementById('editExtraInfo').value;
    const vehicleID = document.getElementById('editVehicle_ID').value;

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

    // Show SweetAlert confirmation before proceeding
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to update this vehicle\'s details?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with updating the vehicle details
            fetch('../backend/update_vehicle.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
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
                    Swal.fire('Updated!', 'The vehicle details were updated successfully.', 'success');
                    fetchVehicleOwners();
                    closeEditModal();
                } else {
                    Swal.fire('Alert', data.message, 'warning');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while saving the vehicle details: ' + error.message, 'error');
            });
        } else {
            Swal.fire('Cancelled', 'The update was cancelled.', 'info');
        }
    });
}


// Function to delete a vehicle
function deleteVehicle(vehicleId, vehicle_id) {
    // Show SweetAlert confirmation dialog
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this vehicle?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with deleting the vehicle
            fetch('../backend/delete_vehicle.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    id: vehicleId,
                    vehicle_id: vehicle_id
                 })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'The vehicle was deleted successfully.', 'success');
                    fetchVehicleOwners(); // Re-fetch and update the vehicle list
                } else {
                    Swal.fire('Error', 'Error deleting the vehicle.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while deleting the vehicle.', 'error');
            });
        } else {
            Swal.fire('Cancelled', 'The vehicle was not deleted.', 'info');
        }
    });
}


// Ensure function is correctly called
fetchVehicleOwners();


  </script>
  
    <!-- Main modal -->
</body>
</html>