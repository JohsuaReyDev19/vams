<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRMSU Candelaria Vehicle Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 CDN -->
</head>
<body class="min-h-screen bg-gray-200 flex items-center justify-center p-2">
    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-2xl">
        <div>
            <div class="flex justify-end">
                <a href="./index.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </a>
            </div>
        </div>
            <img src="prmsu.png" class="w-32 m-auto items-center mb-4 mt-[-100px]">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-center text-yellow-800 mb-4"> Vehicle  Student Registration</h1>
            <p class="text-gray-600">
                Good day Student! Ensure smooth and secure access by registering your vehicle in our system. 
            </p>
        </div>
        <div class="mt-4 mb-3">
            <i class="text-gray-700">Enter the vehicle details to register it in the system</i>
        </div>
        <!-- Notification Container -->
        <form id="registrationForm" class="space-y-4">
            <div>
                <div class="flex items-center gap-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-round"><path d="M18 20a6 6 0 0 0-12 0"/><circle cx="12" cy="10" r="4"/><circle cx="12" cy="12" r="10"/></svg>
                <label class="block text-gray-700">Owner Name</label>
                </div>
                <input type="text" id="ownerName" name="owner_name" class="w-full border p-2 rounded" placeholder="Enter full name">
                <input type="text" id="student" name="entry_type" class="w-full border p-2 rounded hidden" value="Student">
                <span class="text-red-500 text-sm hidden" id="nameError">Name must be at least 2 characters</span>
            </div>
            <div>
                <div class="flex items-center gap-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                    <label class="block text-gray-700">Contact Number</label>
                </div>
                <input type="number" id="contactNumber" name="contact_number" class="w-full border p-2 rounded" placeholder="Enter contact number">
                <span class="text-red-500 text-sm hidden" id="contactError">Contact number must be 11 digits</span>
            </div>
            <div>
                <div class="flex items-center gap-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-qr-code"><path d="M17 12v4a1 1 0 0 1-1 1h-4"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M17 8V7"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M7 17h.01"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><rect x="7" y="7" width="5" height="5" rx="1"/></svg>
                    <label class="block text-gray-700">RFID Number</label>
                </div>
                <input type="text" id="rfidNumber" name="rfid_number" class="w-full border p-2 rounded" placeholder="Enter RFID number">
                <span class="text-red-500 text-sm hidden" id="rfidError">RFID number must be at least 8 characters</span>
            </div>
            <div>
                <div class="flex items-center gap-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                    <label class="block text-gray-700">Vehicle Type</label>
                </div>
                <select id="vehicleType" name="vehicle_type" class="w-full border p-2 rounded">
                    <option value="car">Car</option>
                    <option value="motorcycle">Motorcycle</option>
                </select>
            </div>
            <div>
                <div class="flex items-center gap-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bandage"><path d="M10 10.01h.01"/><path d="M10 14.01h.01"/><path d="M14 10.01h.01"/><path d="M14 14.01h.01"/><path d="M18 6v11.5"/><path d="M6 6v12"/><rect x="2" y="6" width="20" height="12" rx="2"/></svg>
                    <label class="block text-gray-700">Plate Number/MV File</label>
                </div>
                <input type="text" id="plateNumber" name="plate_number" class="w-full border p-2 rounded" placeholder="Enter plate number">
                <span class="text-red-500 text-sm hidden" id="plateError">Plate number must be at least 6 characters</span>
            </div>
            
            <div>
                <input type="checkbox" id="agreement" class="mr-2">
                <label for="agreement" class="text-gray-700">By clicking "I Agree" or using the System, you accept these Terms and Conditions.</label>
            </div>
            <button type="submit" id="submitBtn" class="w-full bg-yellow-600 text-white py-2 rounded hidden" disabled>Submit Registration</button>
            <div type="submit" id="unsubmitBtn" class="w-full bg-gray-300 text-white py-2 rounded text-center" disabled>Submit Registration</div>
        </form>
    </div>

    <script src="./javascript/script.js"></script>
    <script>
        $("#registrationForm").submit(function (e) {
            e.preventDefault();
            
            $.post("./database/register_vehicle.php", $(this).serialize(), function (response) {
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
</body>
</html>
