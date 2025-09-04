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
                    <div class="w-full justify-between md:flex md:items-center md:gap-4">
                        <input type="text" id="search" placeholder="Search owners..." 
                            class="w-full md:max-w-md bg-white pl-4 py-2 rounded-lg border text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-800 focus:border-yellow-800">
                        <!-- Add Owner Button -->
                        <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" id="openAddModal" 
                        class="bg-blue-500 text-white px-4 py-1 rounded">
                            <span class="mr-2 text-lg font-bold">+</span> Add Owner
                        </button>
                    </div>
                </div>
                    <div id="nodata" class="w-full text-center hidden">
                        <p class="text-2xl text-gray-600">No Vehicle found</p>
                    </div>
                    <!-- Owner List -->
                    <div id="ownerList" class="grid grid-cols-1 lg:grid-cols-3 gap-2 h-96 overflow-y-scroll">
                        <!-- Owner Card 1 -->
                    </div>
                </div>
            </div>
  </div>
  
    <!-- Main modal -->

   <?php include '../modals/addvehicle.php'?>
   <?php include '../modals/editVehicle.php'?>
   <script src="../javascript/fetch_vehicles.js"></script>
   <script src="/javascript/script.js"></script>
   
   <script>
    function editVehicle(vehicleId) {
        fetch(`../database/get_vehicle.php?id=${vehicleId}`)
            .then(response => response.json())
            .then(data => {
                console.log("Fetched Data:", data);
                if (data) {
                    document.getElementById('edit-vehicle-id').value = data.id;
                    document.getElementById('edit-plate-number').value = data.plate_number;
                    document.getElementById('edit-owner').value = data.owner_name;
                    document.getElementById('edit-vehicle-type').value = data.vehicle_type;
                    document.getElementById('edit-barcode').value = data.rfid_number;
                    document.getElementById('edit-contact').value = data.contact_number;
                    document.getElementById('edit-entry-type').value = data.entry_type;

                    // Show the modal
                    document.getElementById('editVehicleModal').classList.remove('hidden');
                } else {
                    Swal.fire("Error", "Vehicle not found!", "error");
                }
            })
            .catch(error => {
                console.error('Error fetching vehicle data:', error);
                Swal.fire("Error", "Failed to fetch vehicle data!", "error");
            });
    }

    function closeModal() {
        document.getElementById('editVehicleModal').classList.add('hidden');
    }

    document.getElementById("editVehicleForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append("vehicle_id", document.getElementById("edit-vehicle-id").value);
        formData.append("rfid_number", document.getElementById("edit-barcode").value);
        formData.append("owner_name", document.getElementById("edit-owner").value);
        formData.append("contact_number", document.getElementById("edit-contact").value);
        formData.append("entry_type", document.getElementById("edit-entry-type").value);
        formData.append("plate_number", document.getElementById("edit-plate-number").value);
        formData.append("vehicle_type", document.getElementById("edit-vehicle-type").value);

        fetch("../database/update_vehicle.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    Swal.fire({
                        title: "Success",
                        text: data.message,
                        icon: "success"
                    }).then(() => {
                        closeModal();
                        location.reload(); // Reload to reflect changes
                    });
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            })
            .catch(error => {
                console.error("Error updating vehicle:", error);
                Swal.fire("Error", "Failed to update vehicle details!", "error");
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

  <script>
        $("#registrationForm").submit(function (e) {
            e.preventDefault();
            
            $.post("../database/register_vehicle.php", $(this).serialize(), function (response) {
                let res = JSON.parse(response); // Parse JSON response
                
                if (res.status === "success") {
                    $("#message").text(res.message).addClass("text-green-500");
                    Swal.fire({
                        icon: "success",
                        title: "Registration Successful!",
                        text: res.message,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK"     
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // Reload the page after clicking OK
                        }
                    });
                } else {
                    $("#message").text(res.message).addClass("text-red-500");
                    Swal.fire({
                        icon: "error",
                        title: "Registration Failed!",
                        text: res.message,
                        confirmButtonColor: "#d33",
                        confirmButtonText: "Try Again"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); // Reload the page after clicking OK
                        }
                    });
                }

                console.log(res); // Debugging
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#menu-button").click(function() {
                $("#sidebar").toggleClass("-translate-x-full");
            });
            $("#closebtn").click(function(){
                $("#sidebar").toggleClass("-translate-x-full");
            });
            // Search functionality
            $("#search").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                var hasResults = false;

                $("#ownerList > div").each(function () {
                    if ($(this).text().toLowerCase().indexOf(value) > -1) {
                        $(this).show();
                        hasResults = true;
                    } else {
                        $(this).hide();
                    }
                });

                // Show "No Vehicle found" message if no results are found
                if (!hasResults) {
                    $("#nodata").removeClass("hidden"); // Show the message
                    $("#ownerList").addClass("hidden"); // Hide the owner list
                } else {
                    $("#nodata").addClass("hidden"); // Hide the message
                    $("#ownerList").removeClass("hidden"); // Show the owner list
                }
            });


            // Open Add Owner Modal
            $("#openAddModal").click(function () {
                $("#addOwnerModal").removeClass("hidden");
            });

            // Close Add Owner Modal
            $("#closeAddModal").click(function () {
                $("#addOwnerModal").addClass("hidden");
            });

            // Open Edit Modal
            $(".edit-btn").click(function () {
                $("#editName").val($(this).data("name"));
                $("#editPhone").val($(this).data("phone"));
                $("#editVehicles").val($(this).data("vehicles"));
                $("#editOwnerModal").removeClass("hidden");
                $("#editOwnerModal").addClass("fade-in");
            });

            // Close Edit Modal
            $("#closeEditModal").click(function () {
                $("#editOwnerModal").addClass("hidden");
            });

            // Open View Details Modal
            $(".view-btn").click(function () {
                $("#viewName").text($(this).data("name"));
                $("#viewPhone").text("📞 " + $(this).data("phone"));
                $("#viewVehicles").text("🚗 " + $(this).data("vehicles"));
                $("#viewDetailsModal").removeClass("hidden");
            });

            // Close View Details Modal
            $("#closeViewModal").click(function () {
                $("#viewDetailsModal").addClass("hidden");
            });
        });

        
    </script>
</body>
</html>