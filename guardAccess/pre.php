
        <div class="container mx-auto p-4  w-full max-w-3xl bg-white rounded">
           <div class="w-full flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car-front-icon lucide-car-front"><path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8"/><path d="M7 14h.01"/><path d="M17 14h.01"/><rect width="18" height="8" x="3" y="10" rx="2"/><path d="M5 18v2"/><path d="M19 18v2"/></svg>
           </div>
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-center text-yellow-800 mb-4">Vehicle Registration</h1>
                <p class="text-gray-600">Good day User! Ensure smooth and secure access by registering your vehicle in our system.</p>
            </div>
            <div class="mt-4 mb-3 px-4">
                <i class="text-gray-700">Enter the vehicle details to register it in the system</i>
            </div>
            <form id="vehicleForm" class="bg-white p-4 space-y-2">
                <input type="text" name="plate_number" placeholder="Plate Number/Mv file" required class="scanner-input">
                <div id="plate_status"></div>

                <select name="vehicle_type" class="scanner-input" required>
                    <option value="">Select Vehicle Type</option>
                    <option value="Sedan">Sedan</option>
                    <option value="SUV">SUV</option>
                    <option value="Truck">Truck</option>
                    <option value="Van">Van</option>
                    <option value="Motorcycle">Motorcycle</option>
                </select>

                <select name="owner_type" id="owner_type" class="scanner-input" required>
                    <option value="">Select Owner Type</option>
                    <option value="student">Student</option>
                    <option value="faculty">Faculty</option>
                    <option value="ojt">OJT</option>
                </select>

                <div id="extraFields">
                    
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Scan RFID</label>
                    <input type="text" name="rfidtag" id="rfidtag" 
                        class="scanner-input hidden" required>
                </div>

                <div class="flex gap-1">
                    <span id="scan_rfid" 
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 cursor-pointer">
                        Scan RFID
                    </span>
                    <span id="clear" 
                        class="hidden bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Clear
                    </span>
                </div>
                <div class="my-3">
                    <label class="text-gray-600 my-2">Upload OR/CR for Verification</label><br>
                    <input type="file" id="imageInput" name="image_name" accept="image/*" required class="scanner-input">
                    <div id="previewContainer" class="my-2"></div>
                </div>
                <div class="my-3 flex items-center justify-center">
                    <button type="submit" id="registerVehicleBtn" class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-2">
                        Register Vehicle
                    </button>
                </div>
            </form>
        </div>


<script>
document.getElementById('owner_type').addEventListener('change', function () {
    const ownerType = this.value;
    const extraFields = document.getElementById('extraFields');
    extraFields.innerHTML = '';

    if (ownerType === 'student') {
        extraFields.innerHTML = `
            <label class="text-sm">Find Student Id:</label>
            <input type="text" id="code" name="student_id_number" placeholder="Search Student ID" required class="scanner-input">
            <label class="text-sm">Name:</label>
            <input type="text" id="student_name" name="student_name" placeholder="Student Name" required class="scanner-input" readonly>
            <label class="text-sm">Course:</label>
            <input type="text" id="course" name="course" placeholder="course" required class="scanner-input" readonly>
            <label class="text-sm">Year level:</label>
            <input type="text" id="year" name="year_level" placeholder="year_level" required class="scanner-input" readonly>
            <label class="text-sm">Contact</label>
            <input type="tel" id="contact" name="email" placeholder="contact" required class="scanner-input" readonly>
        `;
    } else if (ownerType === 'faculty') {
        extraFields.innerHTML = `
            <input type="text" name="faculty_name" placeholder="Faculty Name" required class="scanner-input">
            <input type="text" name="department" placeholder="Department" required class="scanner-input">
            <input type="text" name="position" placeholder="Position" required class="scanner-input" value="Nan" hidden>
            <input type="tel" name="email" placeholder="contact" required class="scanner-input">
        `;
    } else if (ownerType === 'ojt') {
        extraFields.innerHTML = `
            <input type="text" name="ojt_name" placeholder="OJT Name" required class="scanner-input">
            <input type="text" name="company_name" placeholder="Department" required class="scanner-input">
            <input type="text" name="supervisor_name" placeholder="Supervisor Name" required class="scanner-input" value="Nan" hidden>
            <input type="tel" name="email" placeholder="Contact" required class="scanner-input">
        `;
    }
});

/** 🔍 Search Student by Code (delegated, works after injection) **/
$(document).on('keyup', '#code', function () {
    let code = $(this).val().trim();

    if (code.length > 1) {
        $.ajax({
            url: '../database/search_student.php', // backend file
            method: 'POST',
            data: { code: code },
            dataType: 'json',
            success: function (data) {
                if (data.status === 'success') {
                    let fullName = data.student.first_name +
                                   (data.student.middle_name ? ' ' + data.student.middle_name : '') +
                                   ' ' + data.student.last_name;

                    $('#student_name').val(fullName);
                    $('#course').val(data.student.course);
                    $('#year').val(data.student.year);
                    $('#contact').val(data.student.contact_no);
                    $('#student_status').html('<span class="text-green-600">Student found</span>');

                    // Enable register button
                    $("#registerVehicleBtn").prop("disabled", false)
                        .removeClass("opacity-50 cursor-not-allowed");

                } else {
                    $('#student_name, #course, #year, #contact').val('');
                    $('#student_status').html('<span class="text-red-600">Student not found</span>');

                    // Disable register button
                    $("#registerVehicleBtn").prop("disabled", true)
                        .addClass("opacity-50 cursor-not-allowed");
                }
            },
            error: function () {
                $('#student_status').html('<span class="text-red-600">Error fetching student data</span>');
                $("#registerVehicleBtn").prop("disabled", true)
                    .addClass("opacity-50 cursor-not-allowed");
            }
        });
    } else {
        $('#student_name, #course, #year, #contact').val('');
        $('#student_status').html('');
        $("#registerVehicleBtn").prop("disabled", false)
            .removeClass("opacity-50 cursor-not-allowed");
    }
});

// Plate number check
$(document).on("keyup", "input[name='plate_number']", function () {
    let plateNumber = $(this).val();
    if (plateNumber.length > 2) {
        $.ajax({
            type: "POST",
            url: "../backend/check_availability.php",
            data: { plate_number: plateNumber },
            dataType: "json",
            success: function (response) {
                if (response.exists) {
                    $("#plate_status").html('<span class="text-red-600">' + response.message + '</span>');
                } else {
                    $("#plate_status").html('<span class="text-green-600">' + response.message + '</span>');
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

// Form Submission with validation
document.getElementById('vehicleForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const ownerType = document.getElementById('owner_type').value;

    // 🚨 Prevent submit if student fields are empty
    if (ownerType === 'student') {
        let studentName = document.getElementById('student_name').value.trim();
        let course = document.getElementById('course').value.trim();
        let year = document.getElementById('year').value.trim();
        let contact = document.getElementById('contact').value.trim();

        if (studentName === "" || course === "" || year === "" || contact === "") {
            Swal.fire({
                icon: 'error',
                title: 'Student Not Found',
                text: 'Please search a valid Student ID. Student information is required!',
                confirmButtonColor: '#d33'
            });
            return; // ❌ STOP submission
        }
    }

    Swal.fire({
        title: 'Confirm Registration',
        text: "Are you sure you want to register this vehicle?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Register'
    }).then((result) => {
        if (result.isConfirmed) {
            const registerBtn = document.getElementById('registerVehicleBtn');
            registerBtn.disabled = true;
            registerBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg> Registering...`;

            const formData = new FormData(document.getElementById('vehicleForm'));

            fetch('../backend/vehicle_registration.php', {
                method: 'POST',
                body: formData,
            })
            .then(async response => {
                if (!response.ok) throw new Error("Server error.");
                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Your Vehicle is Now Registered',
                        text: data.message,
                        confirmButtonColor: '#3085d6',
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
                        icon: 'warning',
                        title: 'Warning!',
                        text: data.message,
                        confirmButtonColor: '#d33',
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error!',
                    text: 'An error occurred. Please try again later.',
                    confirmButtonColor: '#d33',
                });
            })
            .finally(() => {
                registerBtn.disabled = false;
                registerBtn.innerHTML = "Register Vehicle";
            });
        }
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



<script>
$(document).ready(function () {
    $(document).on("keyup", "input[name='plate_number']", function () {
        let plateNumber = $(this).val();
        if (plateNumber.length > 2) {
            $.ajax({
                type: "POST",
                url: "../backend/check_availability.php",
                data: { plate_number: plateNumber },
                dataType: "json",
                success: function (response) {
                    if (response.exists) {
                        $("#plate_status").html('<span class="text-red-600">' + response.message + '</span>');
                    } else {
                        $("#plate_status").html('<span class="text-green-600">' + response.message + '</span>');
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
            url: "../backend/check_availability.php",
            data: { student_id_number: studentID },
            dataType: "json",

            success: function (response) {

                if (response.message === "Student not found.") {
                    $("#student_status").html('<span style="color: red;">' + response.message + '</span>');
                    $("#registerVehicleBtn").prop("disabled", true)
                        .addClass("opacity-50 cursor-not-allowed");
                } else {
                    $("#student_status").html('<span style="color: green;">' + response.message + '</span>');
                    $("#registerVehicleBtn").prop("disabled", false)
                        .removeClass("opacity-50 cursor-not-allowed");
                }
            },

            error: function () {
                $("#student_status").html('<span style="color: red;">Error checking student ID.</span>');
                $("#registerVehicleBtn").prop("disabled", true)
                    .addClass("opacity-50 cursor-not-allowed");
            }
        });
    } else {
        $("#student_status").html("");
        $("#registerVehicleBtn").prop("disabled", false)
            .removeClass("opacity-50 cursor-not-allowed");
    }
});

});
</script>