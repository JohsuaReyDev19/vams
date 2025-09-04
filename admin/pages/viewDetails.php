<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../../index.php"); // Redirect users who are not logged in
    exit();
 }
?>

<?php
// Get the values from URL
$id = $_GET['id'] ?? null;
$name = $_GET['name'] ?? null;
$purpose = $_GET['purpose'] ?? null;

// Optional: Sanitize for security
$id = htmlspecialchars($id);
$name = htmlspecialchars(urldecode($name));
$purpose = htmlspecialchars(urldecode($purpose));
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

  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <title>Vehicles</title>
  <style>
    #ownerList {
      scrollbar-width: none;
      -ms-overflow-style: none;
    }
    #ownerList::-webkit-scrollbar { display: none; }
    .fade-in {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.3s ease-out forwards;
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
<?php include './sidebar.php'?>
<div class="p-6 sm:ml-64">
  <div class="w-full mx-auto" id="vehicleshow">
    <div class="w-full mt-12">
      <h1 class="font-bold text-3xl mb-2">(<?= $name ?>) vehicle's</h1>
      <p class="text-gray-500">Registered vehicles, edit and manage vehicles with RFID access</p>
    </div>
    <div class="divider"></div>
    <div id="vehicleList" class="bg-white p-4 rounded-lg"></div>
  </div>

  <!-- image modal -->
  <div id="imageModal" class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-75 flex justify-center items-center z-50">
    <div class="relative">
      <button onclick="closeModal()" class="bg-gray-700 rounded-full absolute top-0 right-0 p-2 text-white text-xl">×</button>
      <img id="modalImage" class="max-w-full max-h-screen object-contain" alt="Modal Image" />
    </div>
  </div>
</div>

<!-- Modal for Edit -->
<div id="editModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50 fade-in">
  <div class="bg-white rounded-lg shadow-lg p-6 w-96">
    <h2 class="text-lg font-semibold mb-4">Edit Vehicle</h2>
    <form id="editForm" class="space-y-3">
      <input type="hidden" id="edit_id">

      <div>
        <label class="block text-sm">Owner Name</label>
        <input type="text" id="edit_owner_name" class="w-full border px-2 py-1 rounded" required>
      </div>
      <div>
        <label class="block text-sm">License Plate</label>
        <input type="text" id="edit_license_plate" class="w-full border px-2 py-1 rounded" required>
      </div>
      <div>
        <label class="block text-sm">Vehicle Type</label>
        <input type="text" id="edit_vehicle_type" class="w-full border px-2 py-1 rounded" required>
      </div>
      <div>
        <label class="block text-sm">Purpose</label>
        <input type="text" id="edit_purpose" class="w-full border px-2 py-1 rounded">
      </div>
      <div>
        <label class="block text-sm">RFID</label>
        <div class="flex items-center gap-1">
          <input type="text" id="edit_rfid_tag" class="w-full border px-2 py-1 rounded">
          <span id="scanRfid" class="bg-green-500 text-white p-1 rounded-md" title="change rfid">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw-icon lucide-refresh-cw"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
          </span>
        </div>
      </div>

      <div class="flex justify-end space-x-2 mt-4">
        <button type="button" onclick="closeEditModal()" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Save</button>
      </div>
    </form>
  </div>
</div>


<!-- RFID Input Modal -->
    <div id="rfidModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md fade-in">
            <h2 class="text-2xl font-bold mb-4">Scan the RFID Sticker <br>
                <p class="text-sm text-gray-400">Place the RFID Sticker on reader device</p>
            </h2>
            <div id="scanbar" class="flex justify-center items-center m-3">
                 <div class="scanner-container">
                    <div class="scan-line"></div>
                    <div class="glass-glow"></div>
                    <div class="center-text">Scanning...</div>
                </div>
            </div>
            <input type="text" id="rfidInput" class="w-full px-4 py-2 border rounded-md hidden" placeholder="Scanning..." required readonly>
            <div id="plate_status"></div>
            <br>
            <div class="flex justify-end gap-2">
                <span id="cancelModalBtn" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 cursor-pointer">Cancel</span>
                <button id="submitModalBtn" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 hidden" disabled>Done</button>
            </div>
        </div>
    </div>

<script>

  /** 📡 RFID Scan Loop **/
    function startRFIDScan() {
        const intervalId = setInterval(() => {
            fetch('../../scanprocess/read_tag.php')
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
        $('#edit_rfid_tag').val(tagValue);
        $('#rfidModal').addClass('hidden');
        $('#clear').removeClass('hidden');
    });

  // ✅ Load vehicles on page ready
  document.addEventListener("DOMContentLoaded", loadVehicles);

  function loadVehicles() {
    const params = new URLSearchParams(window.location.search);
    const ownerId = params.get('id');

    if (!ownerId) {
      document.body.innerHTML = '<p class="text-red-500">Owner ID not provided.</p>';
      return;
    }

    fetch(`../backend/viewOwnerVehicles.php?owner_id=${ownerId}`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          renderVehicleList(data.vehicles);
        } else {
          document.getElementById('vehicleList').innerText = data.message;
        }
      })
      .catch(() => {
        document.getElementById('vehicleList').innerText = 'An error occurred.';
      });
  }

  function renderVehicleList(vehicles) {
    const vehicleListDiv = document.getElementById('vehicleList');
    vehicleListDiv.innerHTML = '';

    let tableHTML = `
      <div class="overflow-x-auto w-full">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md text-xs sm:text-xs">
          <thead class="bg-gray-100">
            <tr class="text-center">
              <th class="px-2 py-1.5">Name</th>
              <th class="px-2 py-1.5 whitespace-nowrap">License Plate</th>
              <th class="px-2 py-1.5">Vehicle Type</th>
              <th class="px-2 py-1.5">Purpose</th>
              <th class="px-2 py-1.5">RFID</th>
              <th class="px-2 py-1.5">Registered at</th>
              <th class="px-2 py-1.5 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
    `;

    vehicles.forEach(vehicle => {
      const registeredDate = vehicle.created_at
        ? new Date(vehicle.created_at).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
          })
        : 'N/A';

      tableHTML += `
        <tr class="text-center border-b border-gray-200 hover:bg-gray-50">
          <td class="px-2 py-1.5">${vehicle.owner_name}</td>
          <td class="px-2 py-1.5 truncate max-w-[80px]">${vehicle.license_plate}</td>
          <td class="px-2 py-1.5">${vehicle.vehicle_type}</td>
          <td class="px-2 py-1.5">${vehicle.purpose}</td>
          <td class="px-2 py-1.5">${vehicle.rfid_tag || 'N/A'}</td>
          <td class="px-2 py-1.5 text-blue-500">${registeredDate}</td>
          <td class="px-2 py-1.5">
            <div class="flex justify-center space-x-2">
              <button onclick='openEditModal(${JSON.stringify(vehicle)})'
                class="bg-blue-100 text-blue-600 rounded p-1.5">Edit</button>
              <button onclick="deleteVehicle(${vehicle.id})"
                class="bg-red-100 text-red-600 rounded p-1.5">Remove</button>
            </div>
          </td>
        </tr>
      `;
    });

    tableHTML += '</tbody></table></div>';
    vehicleListDiv.innerHTML = tableHTML;
  }

  // ✅ Delete
  function deleteVehicle(id) {
    Swal.fire({
      title: "Are you sure?",
      text: "This vehicle will be removed permanently!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      if (result.isConfirmed) {
        fetch("../database/remove_vehicle.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            Swal.fire("Deleted!", "Vehicle has been removed.", "success");
            loadVehicles();
          } else {
            Swal.fire("Error", data.message || "Failed to delete.", "error");
          }
        });
      }
    });
  }

  // ✅ Edit
  function openEditModal(vehicle) {
    document.getElementById('edit_id').value = vehicle.id;
    document.getElementById('edit_owner_name').value = vehicle.owner_name || '';
    document.getElementById('edit_license_plate').value = vehicle.license_plate || '';
    document.getElementById('edit_vehicle_type').value = vehicle.vehicle_type || '';
    document.getElementById('edit_purpose').value = vehicle.purpose || '';
    document.getElementById('edit_rfid_tag').value = vehicle.rfid_tag || '';

    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
  }

  function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
  }

  document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const updatedData = {
      id: document.getElementById('edit_id').value,
      owner_name: document.getElementById('edit_owner_name').value,
      license_plate: document.getElementById('edit_license_plate').value,
      vehicle_type: document.getElementById('edit_vehicle_type').value,
      purpose: document.getElementById('edit_purpose').value,
      rfid_tag: document.getElementById('edit_rfid_tag').value
    };

    fetch("../database/updateVehicleDetails.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(updatedData)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Swal.fire("Updated!", "Vehicle details updated successfully.", "success");
        closeEditModal();
        loadVehicles();
      } else {
        Swal.fire("warning", data.message || "Failed to update.", "warning");
      }
    });
  });
</script>
</body>
</html>
