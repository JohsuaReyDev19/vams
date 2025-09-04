<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Vehicle Registration</h1>
        
        <form id="vehicleForm" class="bg-white p-4 shadow-md rounded">
            <input type="text" name="plate_number" placeholder="Plate Number" required class="border p-2 w-full mb-2">
            <input type="text" name="vehicle_type" placeholder="Vehicle Type" required class="border p-2 w-full mb-2">
            <input type="text" name="rfid_tag" placeholder="RFID Tag" required class="border p-2 w-full mb-2">
            <input type="file" name="image_name" required class="border p-2 w-full mb-2">
            
            <select name="owner_type" id="owner_type" class="border p-2 w-full mb-2" required>
                <option value="">Select Owner Type</option>
                <option value="student">Student</option>
                <option value="faculty">Faculty</option>
                <option value="ojt">OJT</option>
            </select>

            <!-- Additional fields (hidden initially) -->
            <div id="extraFields"></div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">
                Register Vehicle
            </button>
        </form>

        <div id="message" class="mt-2"></div>
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
                    <input type="text" name="course" placeholder="Course" required class="border p-2 w-full mb-2">
                    <input type="text" name="year_level" placeholder="Year Level" required class="border p-2 w-full mb-2">
                `;
            } else if (ownerType === 'faculty') {
                extraFields.innerHTML = `
                    <input type="text" name="faculty_name" placeholder="Faculty Name" required class="border p-2 w-full mb-2">
                    <input type="text" name="department" placeholder="Department" required class="border p-2 w-full mb-2">
                    <input type="text" name="position" placeholder="Position" required class="border p-2 w-full mb-2">
                `;
            } else if (ownerType === 'ojt') {
                extraFields.innerHTML = `
                    <input type="text" name="ojt_name" placeholder="OJT Name" required class="border p-2 w-full mb-2">
                    <input type="text" name="company_name" placeholder="Company Name" required class="border p-2 w-full mb-2">
                    <input type="text" name="supervisor_name" placeholder="Supervisor Name" required class="border p-2 w-full mb-2">
                `;
            }
        });

        document.getElementById('vehicleForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('./back.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('message').textContent = data.message;
                if (data.success) {
                    document.getElementById('vehicleForm').reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('message').textContent = 'An error occurred.';
            });
        });
    </script>

</body>
</html>
