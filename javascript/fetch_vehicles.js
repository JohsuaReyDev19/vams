$(document).ready(function() {
    function loadVehicles() {
        $.ajax({
            url: "/admin/database/fetch_vehicles.php", // Make sure this file path is correct
            method: "GET",
            dataType: "json",
            success: function(data) {
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                let vehicleList = "";
                data.forEach(vehicle => {
                    vehicleList += `
                        
                        <table class="min-w-full text-sm text-left text-gray-600">
                            <thead class="text-xs uppercase bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3">Owner</th>
                                    <th class="px-6 py-3">RFID#</th>
                                    <th class="px-6 py-3">Plate #</th>
                                    <th class="px-6 py-3">Registered_at</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="vehicleList">
                                <tr class="bg-white border-b status-row" data-status="pending">
                                    <td class="px-6 py-4">${vehicle.owner_name}</td>
                                    <td class="px-6 py-4">${vehicle.rfid_number}</td>
                                    <td class="px-6 py-4">${vehicle.plate_number}</td>
                                    <td class="px-6 py-4">${vehicle.registered_at}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2 mt-4 sm:mt-0">
                                            <div class="edit" data-id="${vehicle.id}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen">
                                                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                    <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/>
                                                </svg>
                                            </div>
                                            <div class="delete" data-id="${vehicle.id}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ff1a1a" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    `;
                });

                $("#ownerList").html(vehicleList);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    // Load vehicles on page load
    loadVehicles();

    // Reload every 10 seconds (optional)
    setInterval(loadVehicles, 400);
});