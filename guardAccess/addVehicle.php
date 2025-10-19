<div class="max-w-6xl mx-auto space-y-8">
  <div class="bg-white shadow p-6 mb-4">
    <h2 class="text-xl font-semibold text-gray-900">Add Vehicle to registered Owner</h2>
    <form id="addVehicle" method="POST" class="space-y-4">

      <input type="hidden" id="owner_id" name="owner_id">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Search Name</label>
        <input type="text" id="searchName" name="searchName" placeholder="search name here..." autocomplete="off"
               class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-200" required>
        <div id="searchResult" class="bg-white border rounded-md mt-1 hidden fade-in"></div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Plate/Mv file</label>
        <input type="text" name="plate" id="plate" class="w-full px-4 py-2 border rounded-md" placeholder="enter plate/mv file" required>
        <div id="plateStatus"></div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
        <input type="text" name="purpose" id="purpose" class="w-full px-4 py-2 border rounded-md"required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
        <input type="text" name="vehicle_type" id="vehicle_type" class="w-full px-4 py-2 border rounded-md" placeholder="enter type of vehicle" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Scan RFID</label>
        <input type="text" name="rfid_tag" id="rfid_tag" class="hidden w-full px-4 py-2 border rounded-md" required>
      </div>
      <div class="flex gap-1">
        <span id="scanRfid" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 cursor-pointer">Scan RFID</span>
        <span id="clear" class="hidden bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-600">Clear</span>
      </div>
      <div id="success" class="hidden">
        <div role="alert" class="alert alert-success alert-soft">
          <span>Vehicle successfully added.</span>
        </div>
      </div>
      <button type="submit" id="btnAdd" class="w-full bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">Add Vehicle</button>
    </form>
  </div>
</div>



<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    /** 🔍 Search while typing **/
    $('#searchName').on('keyup', function () {
        let query = $(this).val().trim();

        if (query.length > 1) {
            $.ajax({
                url: '../database/search_name.php',
                method: 'POST',
                data: { query },
                success: function (data) {
                    $('#searchResult').html(data).removeClass('hidden');
                }
            });
        } else {
            $('#searchResult').addClass('hidden').html('');
        }
    });

    /** 📌 Click on search result **/
    $(document).on('click', '.search-item', function () {
        $('#owner_id').val($(this).data('id'));
        $('#searchName').val($(this).data('name'));
        $('#purpose').val($(this).data('purpose'));

        $('#searchResult').addClass('hidden').html('');
    });

    /** 📡 RFID Scan Loop **/
    function startRFIDScan() {
        const intervalId = setInterval(() => {
            fetch('../scanprocess/read_tag.php')
                .then(response => response.text())
                .then(tag => {
                    const cleanTag = tag.trim();
                    const input = $('#rfidInput');

                    if (cleanTag && cleanTag !== "Waiting for tag...") {
                        input.val(cleanTag);
                        $('#scanbar').addClass('hidden');
                        $('#submitModalBtn').removeClass('hidden').prop('disabled', false);
                        input.removeClass('hidden');

                        input.trigger('input');
                        clearInterval(intervalId); // stop scanning
                    }
                })
                .catch(err => console.error("RFID Scan Error:", err));
        }, 500);
    }

    /** 📡 Open RFID Modal **/
    $('#scanRfid').on('click', function () {
        $('#rfidModal').removeClass('hidden');
        $('#rfidInput').val('').removeClass('hidden');
        $('#submitModalBtn').addClass('hidden').prop('disabled', true);
        startRFIDScan(); // Start scanning on modal open
    });

    /** ❌ Cancel RFID Modal **/
    $('#cancelModalBtn').on('click', function () {
        $('#rfidModal').addClass('hidden');
    });

    /** 🎯 RFID Input Change **/
    $('#rfidInput').on('input', function () {
        let tagValue = $(this).val().trim();
        $('#submitModalBtn').toggleClass('hidden', tagValue.length === 0).prop('disabled', tagValue.length === 0);
    });

    /** ✅ Submit RFID from Modal to Form **/
    $('#submitModalBtn').on('click', function () {
        let tagValue = $('#rfidInput').val().trim();
        $('#rfid_tag').val(tagValue).removeClass('hidden');
        $('#rfidModal').addClass('hidden');
        $('#clear').removeClass('hidden');
    });

    
    $('#clear').on('click', function () {
        $('#clear').addClass('hidden');
        $('#rfid_tag').val('').addClass('hidden');
    });

    /** 🚗 Submit Add Vehicle Form **/
    $('#addVehicle').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Confirm Add Vehicle?',
            text: "Make sure the details are correct before saving.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1E3A8A',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, Add it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../admin/backend/add_vehicle.php',
                    type: 'POST',
                    data: {
                        owner_id: $('#owner_id').val(),
                        purpose: $('#purpose').val(),
                        owner_name: $('#searchName').val(),
                        license_plate: $('#plate').val(),
                        vehicle_type: $('#vehicle_type').val(),
                        rfid_tag: $('#rfid_tag').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Added!',
                                text: 'Vehicle successfully added.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#addVehicle')[0].reset();
                            $('#rfid_tag').addClass('hidden').val('');
                        } else {
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message || 'Error adding vehicle.',
                                icon: 'warning'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while saving the vehicle.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

});
</script>


<!-- AJAX Check Availability -->
<script>
$(document).ready(function () {
    $(document).on("keyup", "input[name='plate']", function () {
        let plateNumber = $(this).val();
        if (plateNumber.length > 2) {
            $.ajax({
                type: "POST",
                url: "../backend/check_availability.php",
                data: { plate_number: plateNumber },
                dataType: "json",
                success: function (response) {
                    if (response.exists) {
                        $("#plateStatus").html('<span style="color: red;">' + response.message + '</span>');
                        $("#btnAdd").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
                    } else {
                        $("#plateStatus").html('<span style="color: green;">Plate number is not Registered.</span>');
                        $("#btnAdd").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
                    }
                },
                error: function () {
                    $("#plateStatus").html('<span style="color: red;">Error checking plate number.</span>');
                    $("#btnAdd").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
                }
            });
        } else {
            $("#plateStatus").html("");
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

