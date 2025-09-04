<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Vehicles</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            fetchVehicles(); // Call function on page load

            function fetchVehicles() {
                $.ajax({
                    url: "/database/fetch_vehicles.php",
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $("#vehicle-list").html(""); // Clear old data
                        $.each(data, function (index, vehicle) {
                            let vehicleHtml = `
                                <div class="flex sm:flex-row justify-between bg-gray-100 border rounded-lg p-5 mb-4">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car p-2 bg-gray-200 mr-3 rounded-lg">
                                            <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/>
                                            <circle cx="7" cy="17" r="2"/>
                                            <path d="M9 17h6"/>
                                            <circle cx="17" cy="17" r="2"/>
                                        </svg> 
                                        <div>
                                            <h3 class="text-lg sm:text-xl font-bold">${vehicle.plate_number}</h3>
                                            <div class="mt-3">
                                                <p class="flex items-center gap-2 text-sm sm:text-base">
                                                    Owner: <span class="text-gray-600"> ${vehicle.owner_name}</span>
                                                </p>
                                                <p class="flex items-center gap-2 text-sm sm:text-base">
                                                    RFID num: <span class="text-gray-600"> ${vehicle.rfid_number}</span>
                                                </p>
                                            </div>
                                            <p class="text-gray-500 flex items-center gap-2 text-xs sm:text-sm">
                                                Registered_at:
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-4">
                                                        <circle cx="12" cy="12" r="10"/>
                                                        <polyline points="12 6 12 12 16 14"/>
                                                    </svg>
                                                </span>
                                                ${vehicle.registered_at}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2 mt-4 sm:mt-0">
                                        <div class="edit" data-id="${vehicle.id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen">
                                                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/>
                                            </svg>
                                        </div>
                                        <div class="delete" data-id="${vehicle.id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ff1a1a" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash">
                                                <path d="M3 6h18"/>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $("#vehicle-list").append(vehicleHtml);
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching vehicles: ", error);
                    }
                });
            }
        });
    </script>
</head>
<body>
    <div id="vehicle-list"></div>
</body>
</html>
