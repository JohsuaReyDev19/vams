<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4">

    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Vehicle Approval</h1>

        <div id="vehicleList" class="space-y-4"></div>
    </div>

    <!-- Modal Structure -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden">
        <div class="relative">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-white bg-gray-800 px-3 py-1 rounded-full">X</button>
            <img id="modalImage" class="max-w-full max-h-screen rounded-md shadow-lg" />
        </div>
    </div>

    <script>
        function fetchVehicles() {
            fetch('./get_vehicles.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const vehicleListDiv = document.getElementById('vehicleList');
                        vehicleListDiv.innerHTML = ''; // Clear previous data

                        data.vehicles.forEach(vehicle => {
                            const vehicleDiv = document.createElement('div');
                            vehicleDiv.className = 'bg-white border rounded-2xl p-4 flex flex-col md:flex-row items-center md:items-start gap-4 shadow-md';

                            vehicleDiv.innerHTML = `
                                <!-- Image Placeholder -->
                                <div class="w-full md:w-40 h-28 bg-gray-300 rounded-xl flex items-center justify-center text-gray-500 cursor-pointer">
                                    <img src="${vehicle.vehicle_image}" alt="Vehicle Image" class="w-full h-full object-cover rounded-xl" onclick="showModal('${vehicle.vehicle_image}')">
                                </div>

                                <!-- Vehicle Info -->
                                <div class="flex-1 text-center md:text-left">
                                    <div class="flex justify-center md:justify-start items-center gap-2">
                                        <span class="bg-yellow-400 text-white text-xs font-semibold px-2 py-1 rounded-full">Pending</span>
                                        <span class="bg-red-300 text-white text-xs font-semibold px-2 py-1 rounded-full">${vehicle.owner_type.toUpperCase()}</span>
                                    </div>

                                    <h2 class="text-lg font-semibold mt-1">${vehicle.plate_number} - ${vehicle.vehicle_type}</h2>
                                    <p class="text-gray-700">${vehicle.owner_details.split(',')[0]}</p>
                                    <p class="text-sm text-gray-500">${vehicle.owner_details.split(',')[1]}</p>
                                    <p class="text-sm text-gray-500">${vehicle.owner_details.split(',')[2]}</p>
                                    <p class="text-sm text-blue-500 mt-1">Registered: ${vehicle.registration_date || 'N/A'}</p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                                    <button onclick="approveVehicle(${vehicle.vehicle_id})" class="flex items-center justify-center gap-2 px-6 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition w-full md:w-auto">
                                        ✅ Approve
                                    </button>
                                    <button onclick="rejectVehicle(${vehicle.vehicle_id})" class="flex items-center justify-center gap-2 px-6 py-2 bg-red-500 text-white rounded-lg shadow-md hover:bg-red-600 transition w-full md:w-auto">
                                        ❌ Reject
                                    </button>
                                </div>
                            `;
                            vehicleListDiv.appendChild(vehicleDiv);
                        });
                    } else {
                        document.getElementById('vehicleList').textContent = data.message;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('vehicleList').textContent = 'An error occurred.';
                });
        }

        function approveVehicle(vehicleId) {
            updateVehicleStatus(vehicleId, 'Approved');
        }

        function rejectVehicle(vehicleId) {
            updateVehicleStatus(vehicleId, 'Rejected');
        }

        function updateVehicleStatus(vehicleId, status) {
            fetch('./update_vehicle_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `vehicle_id=${vehicleId}&status=${status}`,
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) fetchVehicles();
            })
            .catch(error => console.error('Error:', error));
        }

        function showModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        fetchVehicles(); // Load vehicles on page load
    </script>

</body>
</html>
