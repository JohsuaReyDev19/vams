<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 1s ease-out; }
    </style>
</head>
<body class="font-sans scroll-smooth bg-fixed bg-cover bg-center block justify-center" style="background-image: url('./480156258_1036463918529354_3266044399125512979_n.jpg');">
    <div class="block justify-center min-h-screen bg-gray-200 bg-opacity-90 w-full">
        <div class="container mx-auto p-4  w-full max-w-3xl bg-white">
            <img src="../prmsu.png" class="w-32 m-auto items-center mb-4">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-center text-yellow-800 mb-4">Vehicle Registration</h1>
                <p class="text-gray-600">Good day User! Ensure smooth and secure access by registering your vehicle in our system.</p>
            </div>
            <div class="mt-4 mb-3 px-4">
                <i class="text-gray-700">Enter the vehicle details to register it in the system</i>
            </div>
            <form id="vehicleForm" class="bg-white p-4">
                <input type="text" name="plate_number" placeholder="Plate Number/Mv file" required class="border p-2 w-full mb-2">
                <div id="plate_status"></div>

                <select name="vehicle_type" class="border p-2 w-full mb-2" required>
                    <option value="">Select Vehicle Type</option>
                    <option value="Sedan">Sedan</option>
                    <option value="SUV">SUV</option>
                    <option value="Truck">Truck</option>
                    <option value="Van">Van</option>
                    <option value="Motorcycle">Motorcycle</option>
                </select>

                <select name="owner_type" id="owner_type" class="border p-2 w-full mb-2" required>
                    <option value="">Select Owner Type</option>
                    <option value="student">Student</option>
                    <option value="faculty">Faculty</option>
                    <option value="ojt">OJT</option>
                </select>

                <div id="extraFields"></div>

                <div class="my-3">
                    <label class="text-gray-600 my-2">Submit Your OR/CR for verification</label><br>
                    <input type="file" id="imageInput" name="image_name" accept="image/*" required class="border p-2 w-full mb-2">
                    <div id="previewContainer" class="my-2"></div>
                </div>

                <div class="my-3 flex items-center justify-center">
                    <button type="submit" id="registerVehicleBtn" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">
                        Register Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
document.getElementById('owner_type').addEventListener('change', function () {
    const ownerType = this.value;
    const extraFields = document.getElementById('extraFields');
    extraFields.innerHTML = '';

    if (ownerType === 'student') {
        extraFields.innerHTML = `
            <input type="text" name="student_name" placeholder="Student Name" required class="border p-2 w-full mb-2">
            <input type="text" name="student_id_number" placeholder="Student ID" required class="border p-2 w-full mb-2">
            <div id="student_status"></div>
            <select name="course" class="border p-2 w-full mb-2" required>
                <option value="">Select Course</option>
                <option value="BSIT">BS InfoTech</option>
                <option value="BSF">BS Fisheries</option>
            </select>
            <select name="year_level" class="border p-2 w-full mb-2" required>
                <option value="">Select Year level</option>
                <option value="First year">First year</option>
                <option value="2nd year">Second year</option>
                <option value="3rd year">Third year</option>
                <option value="Fourth year">Fourth year</option>
            </select>
            <input type="email" name="email" placeholder="Enter email" required class="border p-2 w-full mb-2">
        `;
    } else if (ownerType === 'faculty') {
        extraFields.innerHTML = `
            <input type="text" name="faculty_name" placeholder="Faculty Name" required class="border p-2 w-full mb-2">
            <input type="text" name="department" placeholder="Department" required class="border p-2 w-full mb-2">
            <input type="text" name="position" placeholder="Position" required class="border p-2 w-full mb-2">
            <input type="email" name="email" placeholder="Enter email" required class="border p-2 w-full mb-2">
        `;
    } else if (ownerType === 'ojt') {
        extraFields.innerHTML = `
            <input type="text" name="ojt_name" placeholder="OJT Name" required class="border p-2 w-full mb-2">
            <input type="text" name="company_name" placeholder="Company Name" required class="border p-2 w-full mb-2">
            <input type="text" name="supervisor_name" placeholder="Supervisor Name" required class="border p-2 w-full mb-2">
            <input type="email" name="email" placeholder="Enter email" required class="border p-2 w-full mb-2">
        `;
    }
});

// Form Submission
document.getElementById('vehicleForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const registerBtn = document.getElementById('registerVehicleBtn');
    registerBtn.disabled = true;
    registerBtn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
    </svg> Registering...`;

    const formData = new FormData(this);

    fetch('./backend/back.php', {
        method: 'POST',
        body: formData,
    })
    .then(async response => {
        if (!response.ok) throw new Error("Server error.");
        const data = await response.json();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Pre-Registration Successful',
                text: data.message,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then(() => {
                document.getElementById('vehicleForm').reset();
                document.getElementById('extraFields').innerHTML = '';
                document.getElementById('previewContainer').innerHTML = '';
                document.getElementById('student_status').innerHTML = '';
                document.getElementById('plate_status').innerHTML = '';
                window.location.href = './success.php';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Try Again'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Server Error!',
            text: 'An error occurred. Please try again later.',
            confirmButtonColor: '#d33'
        });
    })
    .finally(() => {
        registerBtn.disabled = false;
        registerBtn.innerHTML = "Register Vehicle";
    });
});

// Image Preview + File Size Validation
const imageInput = document.getElementById('imageInput');
const previewContainer = document.getElementById('previewContainer');

imageInput.addEventListener('change', function(event) {
    const file = event.target.files[0];
    previewContainer.innerHTML = '';

    if (file) {
        if (!file.type.startsWith('image/')) {
            alert('Invalid file type. Please upload an image.');
            imageInput.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Image size exceeds 2MB limit.');
            imageInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = "w-full md:w-64 h-auto mt-2 rounded-lg shadow-lg";

            const removeButton = document.createElement('button');
            removeButton.textContent = 'Remove Image';
            removeButton.className = "px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition mt-2";
            removeButton.type = "button";

            removeButton.addEventListener('click', function() {
                previewContainer.innerHTML = '';
                imageInput.value = '';
            });

            previewContainer.appendChild(img);
            previewContainer.appendChild(removeButton);
        };
        reader.readAsDataURL(file);
    }
});
</script>

<!-- AJAX Check Availability -->
<script>
$(document).ready(function () {
    $(document).on("keyup", "input[name='plate_number']", function () {
        let plateNumber = $(this).val();
        if (plateNumber.length > 2) {
            $.ajax({
                type: "POST",
                url: "./backend/check_availability.php",
                data: { plate_number: plateNumber },
                dataType: "json",
                success: function (response) {
                    if (response.exists) {
                        $("#plate_status").html('<span style="color: red;">' + response.message + '</span>');
                        $("#registerVehicleBtn").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
                    } else {
                        $("#plate_status").html('<span style="color: green;">Plate number available.</span>');
                        $("#registerVehicleBtn").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
                    }
                },
                error: function () {
                    $("#plate_status").html('<span style="color: red;">Error checking plate number.</span>');
                    $("#registerVehicleBtn").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
                }
            });
        } else {
            $("#plate_status").html("");
            $("#registerVehicleBtn").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
        }
    });

    $(document).on("keyup", "input[name='student_id_number']", function () {
        let studentID = $(this).val();
        if (studentID.length > 3) {
            $.ajax({
                type: "POST",
                url: "./backend/check_availability.php",
                data: { student_id_number: studentID },
                dataType: "json",
                success: function (response) {
                    if (response.exists) {
                        $("#student_status").html('<span style="color: red;">' + response.message + '</span>');
                        $("#registerVehicleBtn").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
                    } else {
                        $("#student_status").html('<span style="color: green;">' + response.message + '</span>');
                        $("#registerVehicleBtn").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
                    }
                },
                error: function () {
                    $("#student_status").html('<span style="color: red;">Error checking student ID.</span>');
                    $("#registerVehicleBtn").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
                }
            });
        } else {
            $("#student_status").html("");
            $("#registerVehicleBtn").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
        }
    });
});
</script>
</body>
</html>
