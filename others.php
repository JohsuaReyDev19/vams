<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRMSU Candelaria Vehicle Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="min-h-screen bg-gray-200 flex items-center justify-center p-2">
    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-2xl">
        <div>
            <div class="flex justify-end">
                <a href="index.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </a>
            </div>
        </div>
            <img src="prmsu.png" class="w-32 m-auto items-center mb-4 mt-[-100px]">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-center text-yellow-800 mb-4">Vehicle Visitors/Others Entry</h1>
            <p class="text-gray-600">
                Good day, Drivers! Ensure smooth and secure access by checking in your vehicle through our system.
            </p>
        </div>
        <div class="mt-4 mb-3">
            <i class="text-gray-700">Enter the vehicle details to register it in the system</i>
        </div>
        <!-- Notification Container -->
        <div id="notification" class="hidden p-4 mb-4 text-white rounded-lg"></div>

        <form id="registrationForm" class="space-y-4">
            <div>
                <div class="flex items-center gap-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-round"><path d="M18 20a6 6 0 0 0-12 0"/><circle cx="12" cy="10" r="4"/><circle cx="12" cy="12" r="10"/></svg>
                <label class="block text-gray-700">Owner Name</label>
                </div>
                <input type="text" id="ownerName" name="ownername" class="w-full border p-2 rounded" placeholder="Enter full name">
                <span class="text-red-500 text-sm hidden" id="nameError">Name must be at least 2 characters</span>
            </div>
            
            <div>
                <div class="flex items-center gap-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                    <label class="block text-gray-700">Vehicle Type</label>
                </div>
                <select id="vehicleType" name="vehicletype" class="w-full border p-2 rounded">
                    <option value="car">Car</option>
                    <option value="motorcycle">Motorcycle</option>
                </select>
            </div>
            <div>
                <div class="flex items-center gap-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bandage"><path d="M10 10.01h.01"/><path d="M10 14.01h.01"/><path d="M14 10.01h.01"/><path d="M14 14.01h.01"/><path d="M18 6v11.5"/><path d="M6 6v12"/><rect x="2" y="6" width="20" height="12" rx="2"/></svg>
                    <label class="block text-gray-700">Plate Number/MV File</label>
                </div>
                <input type="text" id="plateNumber" name="platenum" class="w-full border p-2 rounded" placeholder="Enter plate number">
                <span class="text-red-500 text-sm hidden" id="plateError">Plate number must be at least 6 characters</span>
            </div>
            <div>
                <div class="flex items-center gap-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                    <label class="block text-gray-700">Contact Number</label>
                </div>
                <input type="tel" id="contactNumber" name="contactnum" class="w-full border p-2 rounded" placeholder="Enter contact number">
                <span class="text-red-500 text-sm hidden" id="contactError">Contact number must be 11 digits</span>
            </div>
            <div>
                <input type="checkbox" id="agreement" class="mr-2">
                <label for="agreement" class="text-gray-700">By clicking "I Agree" or using the System, you accept these Terms and Conditions.</label>
                <div>
                    <p id="term" class="pl-6 hidden text-gray-500"> <br>&nbsp;&nbsp;&nbsp;Terms and ConditionsWeb-based RFID Vehicle Access Monitoring System <br>

                        Last Updated: [Insert Date] <br>
                        
                        1. Acceptance of TermsBy using the System, you agree to these Terms and Conditions. If you disagree, do not use the System. <br>
                        
                        2. User Responsibilities <br>
                        
                        Use the System only for authorized vehicle access. 
                        
                        Provide accurate information and protect your login credentials. <br>
                        
                        Unauthorized access or tampering is prohibited.  <br>
                        
                        3. RFID Access and Data Usage  <br>
                        
                        The System collects and stores vehicle access data for security purposes.
                        
                        Misuse of RFID access may result in suspension.  <br>
                        
                        4. System Availability  <br>
                        
                        We strive for uninterrupted service but do not guarantee uptime.
                        
                        Features may be modified or discontinued without notice.  <br>
                        
                        5. Liability & Termination  <br>
                        
                        We are not liable for unauthorized access or technical failures.
                        
                        Violating these terms may result in access termination.  <br>
                        
                        6. Changes & Contact  <br>
                        
                        Terms may be updated anytime. Continued use implies acceptance.
                        
                        For inquiries, contact [Insert Contact Information].</p>
                </div>
            </div>
            <button type="submit" id="submitBtn" class="w-full bg-yellow-600 text-white py-2 rounded hidden" disabled>Submit</button>
            <div type="submit" id="unsubmitBtn" class="w-full bg-gray-300 text-white py-2 rounded text-center" disabled>Submit</div>
        </form>
    </div>

    <script>
         $(document).ready(function () {
            function validateField(input, errorElement, condition) {
                if (condition) {
                    $(errorElement).removeClass('hidden');
                    return false;
                } else {
                    $(errorElement).addClass('hidden');
                    return true;
                }
            }

            // Real-time validation
            $('#ownerName').on('input', function () {
                validateField(this, '#nameError', $(this).val().length < 2);
            });

            $('#rfidNumber').on('input', function () {
                validateField(this, '#rfidError', $(this).val().length < 8);
            });

            $('#course').on('change', function () {
                validateField(this, '#courseError', $(this).val() === "");
            });

            $('#contactNumber').on('input', function () {
                validateField(this, '#contactError', $(this).val().length !== 11 || isNaN($(this).val()));
            });

            $('#plateNumber').on('input', function () {
                validateField(this, '#plateError', $(this).val().length < 6);
            });

            $('#registrationForm').submit(function (event) {
                event.preventDefault();
                let isValid = true;

                if (!validateField('#ownerName', '#nameError', $('#ownerName').val().length < 2)) isValid = false;
                if (!validateField('#rfidNumber', '#rfidError', $('#rfidNumber').val().length < 8)) isValid = false;
                if (!validateField('#course', '#courseError', $('#course').val() === "")) isValid = false;
                if (!validateField('#contactNumber', '#contactError', $('#contactNumber').val().length !== 11 || isNaN($('#contactNumber').val()))) isValid = false;
                if (!validateField('#plateNumber', '#plateError', $('#plateNumber').val().length < 6)) isValid = false;

                if (isValid) {
                    showNotification("Registration submitted successfully!", "bg-green-400");
                    $('#registrationForm')[0].reset();
                } else {
                    showNotification("Please fill up the all input form before submitting!", "bg-red-400");
                }
            });

            // Notification function
            function showNotification(message, bgColor) {
                let notification = $('#notification');
                notification.text(message);
                notification.removeClass("hidden bg-red-300 bg-green-500").addClass(bgColor);
                setTimeout(() => notification.addClass("hidden"), 3000);
            }

            //agreement button
            function validateField(input, errorElement, condition) {
                if (condition) {
                    $(errorElement).removeClass('hidden');
                    return false;
                } else {
                    $(errorElement).addClass('hidden');
                    return true;
                }
            }

            $('#agreement').change(function () {
                let isChecked = this.checked;

                // Enable/Disable the submit button
                $('#submitBtn').prop('disabled', !isChecked);

                // Show/hide buttons based on agreement checkbox
                if (isChecked) {
                    $('#submitBtn').show();
                    $('#term').show();
                    $('#unsubmitBtn').hide();
                } else {
                    $('#submitBtn').hide();
                    $('#term').hide();
                    $('#unsubmitBtn').show();
                }
            });

        });
    </script>
</body>
</html>
