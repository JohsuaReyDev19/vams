<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Toastify CSS & JS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <title>RFID Vehicle Access System</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: url('../../480156258_1036463918529354_3266044399125512979_n.jpg') no-repeat center center fixed;
        background-size: cover;
        position: relative;
        min-height: 100vh;
        color: hsl(40, 50%, 20%);
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(229, 231, 235, 0.86); /* Tailwind: bg-gray-200 with bg-opacity-80 */
        z-index: -1;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }

    .header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .header h1 {
        font-size: 2.5rem;
        font-weight: bold;
        background: linear-gradient(135deg, hsl(40, 80%, 50%), hsl(35, 100%, 45%));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .header p {
        font-size: 1.1rem;
        color: hsl(40, 20%, 40%);
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 1024px) {
        .grid {
            grid-template-columns: 1fr;
        }
    }

    .card {
        background: hsl(40, 50%, 98%);
        border: 1px solid hsl(40, 40%, 85%);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(180, 83, 9, 0.05);
    }

    .scanner-card {
        text-align: center;
    }

    .scanner-icon {
        width: 4rem;
        height: 4rem;
        background: linear-gradient(135deg, hsl(40, 80%, 55%), hsl(35, 100%, 60%));
        border-radius: 50%;
        margin: 0 auto 1rem auto;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .scanner-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid hsl(40, 40%, 75%);
        border-radius: 8px;
        background: hsl(40, 60%, 98%);
        color: hsl(40, 20%, 20%);
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .scanner-input:focus {
        outline: none;
        border-color: hsl(40, 80%, 60%);
        box-shadow: 0 0 0 2px hsla(40, 80%, 60%, 0.2);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, hsl(40, 80%, 55%), hsl(35, 100%, 60%));
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px hsla(40, 80%, 60%, 0.3);
    }

    .btn-secondary {
        background: hsl(40, 30%, 90%);
        color: hsl(40, 20%, 30%);
        border: 1px solid hsl(40, 40%, 75%);
    }

    .btn-secondary:hover {
        background: hsl(40, 30%, 85%);
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .loading {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 0.5rem;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: hsl(140, 60%, 95%);
        border: 1px solid hsl(140, 60%, 75%);
        color: hsl(140, 40%, 30%);
    }

    .alert-error {
        background: hsl(0, 80%, 95%);
        border: 1px solid hsl(0, 80%, 75%);
        color: hsl(0, 60%, 35%);
    }

    .alert-warning {
        background: hsl(40, 90%, 95%);
        border: 1px solid hsl(40, 90%, 75%);
        color: hsl(40, 70%, 30%);
    }

    .vehicle-info h3,
    .access-log h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: hsl(40, 80%, 45%);
    }

    .vehicle-detail {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid hsl(40, 40%, 85%);
    }

    .vehicle-detail:last-child {
        border-bottom: none;
    }

    .vehicle-detail strong {
        color: hsl(40, 20%, 45%);
    }

    .log-entry {
        padding: 0.75rem;
        border: 1px solid hsl(40, 40%, 80%);
        border-radius: 8px;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .log-entry.allowed {
        border-color: hsl(140, 60%, 50%);
        background: hsl(140, 60%, 97%);
    }

    .log-entry.denied {
        border-color: hsl(0, 60%, 50%);
        background: hsl(0, 60%, 97%);
    }

    .log-entry.pending {
        border-color: hsl(40, 80%, 50%);
        background: hsl(40, 80%, 97%);
    }

    .log-time {
        color: hsl(40, 20%, 50%);
    }

    .placeholder {
        text-align: center;
        padding: 2rem;
        color: hsl(40, 20%, 50%);
    }

    .placeholder-icon {
        width: 4rem;
        height: 4rem;
        margin: 0 auto 1rem auto;
        background: hsl(40, 30%, 90%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .hidden {
        display: none;
    }

    .mode-btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        background: transparent;
        color: hsl(40, 30%, 40%);
    }

    .mode-btn.active {
        background: hsl(40, 80%, 55%);
        color: white;
    }

    .mode-btn:hover:not(.active) {
        background: hsl(40, 30%, 85%);
        color: hsl(40, 20%, 30%);
    }
</style>

</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <h1>Create visitor vehicle entry/exit's</h1>
      <p>Secure visitor entry monitoring</p>
    </div>

    <div class="grid">
      <!-- Left Column - Scanner -->
      <div class="card scanner-card">
        <!-- Mode Toggle Buttons -->
        <div style="display: flex; margin-bottom: 1rem; border-radius: 8px; background: hsl(210, 60%, 96%); padding: 4px; border: 1px solid hsl(210, 40%, 85%);">
          <button id="rfidModeBtn" class="mode-btn active" style="flex: 1;">
            🏷️ Search
          </button>
          <button id="visitorModeBtn" class="mode-btn" style="flex: 1;">
            👤 Visitor Entry
          </button>
        </div>
        <!-- Search Mode -->
        <div id="rfidMode">
          <div class="scanner-icon">🏷️</div>
          <h3 style="margin-bottom: 1rem; color: hsl(200, 100%, 60%);">Search Plate No.</h3>
          <input 
            type="text" 
            id="plateInput" 
            class="scanner-input" 
            placeholder="Enter Plate No. (e.g., SBV-0827)"
            maxlength="20"
          >
          <button onclick="searchVisitor()" id="scanBtn" class="btn btn-primary">
            <span id="scanBtnText">Search Plate</span>
          </button>
        </div>

        <!-- Visitor Mode -->
        <div id="visitorMode" class="hidden">
          <div class="scanner-icon">👤</div>
          <h3 style="margin-bottom: 1rem; color: hsl(200, 100%, 60%);">Visitor Entry</h3>
          <form id="visitorForm" style="display: flex; flex-direction: column; gap: 0.75rem;">
            <input type="text" id="visitorLicense" class="scanner-input" placeholder="License Plate" required maxlength="10">
            <input type="text" id="visitorName" class="scanner-input" placeholder="Driver's Name" required maxlength="50">
            <input type="text" id="visitorPhone" class="scanner-input" placeholder="Phone Number" required maxlength="11">
            <div id="vehicleTypeWrapper" class="w-full">
          <select id="vehicleTypeSelect" class="scanner-input w-full" required>
            <option value="">Select Vehicle Type</option>
            <option value="Sedan">Sedan</option>
            <option value="SUV">SUV</option>
            <option value="Truck">Truck</option>
            <option value="Van">Van</option>
            <option value="Motorcycle">Motorcycle</option>
            <option value="">Others</option>
          </select>
        </div>

        <div id="otherVehicleInputWrapper" class="w-full hidden">
          <input
            type="text"
            id="customVehicleTypeInput"
            class="customVehicleType scanner-input w-full"
            placeholder="Enter vehicle type"
            maxlength="15"
          />
        </div>
            <input type="text" id="visitorColor" class="scanner-input" placeholder="Address" required maxlength="100">
            <textarea id="visitorPurpose" class="scanner-input" placeholder="Purpose of visit" rows="2" maxlength="100"></textarea>
            <button type="submit" class="btn btn-primary">Register Visitor</button>
          </form>
        </div>
      </div>

      <!-- Middle Column - Vehicle Information -->
      <div class="card">
        <div id="alertContainer"></div>
        <!-- Vehicle Info -->
        <div id="vehicleInfo">
          <div class="vehicle-info">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <h3>Vehicle Information</h3>
            </div>
            <div id="vehicleDetails" class="placeholder">
              <p>Fill out the form to display visitor vehicle information</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column - Access Log -->
      <div class="card">
          <div class="access-log">
            <h3>Access Log</h3>
            <!-- Scrollable container -->
            <div id="accessLogWrapper" style="max-height: 80vh; overflow-y: auto; padding-right: 0.5rem;">
              <div id="accessLogEntries">
                <div class="log-entry">
                  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                    <strong>ABC-1234</strong>
                    <span class="log-time">2:20:53 PM</span>
                  </div>
                  <div style="color: hsl(220, 15%, 65%); font-size: 0.8rem;">
                    Johsua Burce • Motor • entry
                  </div>
                </div>
                <!-- Additional entries will be scrollable inside this container -->
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>



  <!-- Modal -->
<div id="plateSearchModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center;">
  <div style="background:white; padding:2rem; border-radius:10px; width:90%; max-width:400px; position:relative;">
    <h3 style="margin-bottom:1rem;">Search Visitor by Plate</h3>
    
    <input type="text" id="plateInput" placeholder="Enter Plate Number" style="width:100%; padding:0.5rem; margin-bottom:1rem; border:1px solid #ccc; border-radius:5px;">
    
    <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
      <button onclick="closeModal()" style="background-color:#ccc; color:black; padding:0.4rem 1.2rem; border:none; border-radius:6px; cursor: pointer;">Close</button>
      <button onclick="searchVisitor()" style="background-color:#3aa608ff; color:white; padding:0.4rem 1.2rem; border:none; border-radius:6px; cursor: pointer;">Search</button>
    </div>
  </div>
</div>


  <!-- jQuery + AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  // const vehicleSelect = document.getElementById('visitorVehicleType');
  // const selectWrapper = document.getElementById('vehicleTypeWrapper');
  // const otherInputWrapper = document.getElementById('otherVehicleInputWrapper');
  // const customVehicleInput = document.getElementById('customVehicleType');

  // vehicleSelect.addEventListener('change', function () {
  //   if (document.getElementById('Others').value === 'Others') {
  //     selectWrapper.classList.add('hidden');
  //     otherInputWrapper.classList.remove('hidden');
  //     customVehicleInput.focus();
  //   }
  // });

  $(document).on("change", "#vehicleTypeSelect", function () {
  const selected = $(this).val();
  
  if (selected === "") {
    $("#vehicleTypeWrapper").addClass("hidden");
    $("#otherVehicleInputWrapper").removeClass("hidden");
    $("#customVehicleTypeInput").focus();
  } else {
    $("#vehicleTypeWrapper").removeClass("hidden");
    $("#otherVehicleInputWrapper").addClass("hidden");
  }
});

</script>

<script>
  $(document).on("click", "#rfidModeBtn", function () {
    $("#visitorMode").addClass('hidden');
    $("#rfidMode").removeClass('hidden');
    $("#rfidModeBtn").addClass("active");
    $("#visitorModeBtn").removeClass("active");
  });

  $(document).on("click", "#visitorModeBtn", function () {
    $("#visitorMode").removeClass('hidden');
    $("#rfidMode").addClass('hidden');
    $("#visitorModeBtn").addClass("active");
    $("#rfidModeBtn").removeClass("active");
  });

  let currentVisitor = {};

  $('#visitorForm').on('submit', function(e) {
    e.preventDefault();

    let vehicleType = "";

    if ($('#customVehicleTypeInput').val()) {
        vehicleType = $('#customVehicleTypeInput').val();
    } else if ($('#vehicleTypeSelect').val()) {
        vehicleType = $('#vehicleTypeSelect').val();
    }

    const data = {
      license_plate: $('#visitorLicense').val(),
      owner_name: $('#visitorName').val(),
      phone: $('#visitorPhone').val(),
      vehicle_type: vehicleType,
      vehicle_color: $('#visitorColor').val(),
      purpose: $('#visitorPurpose').val()
    };

    console.log(data.vehicle_type);

    $.post('register_visitor.php', data, function(response) {
      const info = JSON.parse(response);

      if (info.error) {
        showToast(info.error, "error");
        return;
      }

      currentVisitor = info;

      $('#vehicleDetails').html(`
        <div class="vehicle-detail"><strong>License Plate:</strong><span>${info.plate}</span></div>
        <div class="vehicle-detail"><strong>Owner:</strong><span>${info.name}</span></div>
        <div class="vehicle-detail"><strong>Vehicle Type:</strong><span>${info.type}</span></div>
        <div class="vehicle-detail"><strong>Address:</strong><span>${info.color}</span></div>
        <div class="vehicle-detail"><strong>Phone:</strong><span>${info.phone}</span></div>
        <div class="vehicle-detail"><strong>Purpose of Visit:</strong><span>${info.purpose}</span></div>
        <br><br>
        <div class="vehicle-detail"><strong>Record this to Access logs?</strong><span>action</span></div>
        <div class="vehicle-detail mt-3">
          <strong></strong>
          <span>
            <button onclick="cancelVisitor()" style="background-color:#c23e0aff; color:white; padding:0.4rem 1.2rem; border:none; border-radius:6px; cursor: pointer;">Cancel</button>
            <button onclick="submitEntry()" style="background-color:#3aa608ff; color:white; padding:0.4rem 1.2rem; border:none; border-radius:6px; cursor: pointer;">Entry</button>
          </span>
        </div>
      `);

      showToast("Visitor registered successfully!", "success");
    });
  });

  function cancelVisitor() {
    $('#visitorForm')[0].reset();
    $('#vehicleDetails').html(`<p class="placeholder">Vehicle entry cancelled.</p>`);
    currentVisitor = {};
    showToast("Visitor registration canceled.", "info");
  }


  function loadAccessLogs() {
  $.get('get_access_logs.php', function(data) {
    const logs = JSON.parse(data);
    const $container = $('#accessLogEntries');

    // Check if we already have this data to avoid unnecessary DOM updates
    const currentHTML = $container.html();
    let newHTML = '';

    if (!logs.length) {
      newHTML = '<p>No logs yet.</p>';
    } else {
      logs.forEach(log => {
        const time = new Date(log.timestamp).toLocaleTimeString();
        const type = log.vehicle_type;
        const label = log.time_out ? 'exit' : 'entry';

        newHTML += `
          <div class="log-entry">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
              <strong>${log.plate_number}</strong>
              <span class="log-time">${time}</span>
            </div>
            <div style="color: hsl(220, 15%, 65%); font-size: 0.8rem;">
              ${log.owner_name} • ${type} • ${label}
            </div>
          </div>
        `;
      });
    }

    // Only update if different
    if (currentHTML !== newHTML) {
      $container.html(newHTML);
    }
  });
}

// ⏱ Call every 3 seconds
setInterval(loadAccessLogs, 700);

// ⏳ Initial call on page load
loadAccessLogs();


function showLogDetails(log) {
  currentVisitor = {
    id: log.id,
    plate: log.plate_number,
    name: log.owner_name,
    type: log.vehicle_type,
    color: log.vehicle_color || '—',
    phone: log.phone || '—',
    purpose: log.visit_purpose || '—'
  };

  $('#vehicleDetails').html(`
    <div class="vehicle-detail"><strong>License Plate:</strong><span>${log.plate_number}</span></div>
    <div class="vehicle-detail"><strong>Owner:</strong><span>${log.owner_name}</span></div>
    <div class="vehicle-detail"><strong>Vehicle Type:</strong><span>${log.vehicle_type}</span></div>
    <br><br>
    <div class="vehicle-detail"><strong>Update exit time?</strong><span>action</span></div>
    <div class="vehicle-detail mt-3">
      <strong></strong>
      <span>
        <button onclick="cancelVisitor()" style="background-color:#c23e0aff; color:white; padding:0.4rem 1.2rem; border:none; border-radius:6px; cursor: pointer;">Cancel</button>
        <button onclick="submitExit(${log.id})" style="background-color:#3aa608ff; color:white; padding:0.4rem 1.2rem; border:none; border-radius:6px; cursor: pointer;">Exit</button>
      </span>
    </div>
  `);
}


  function openModal() {
    document.getElementById('plateSearchModal').style.display = 'flex';
  }

  function closeModal() {
    document.getElementById('plateSearchModal').style.display = 'none';
  }

  function searchVisitor() {
    const plate = document.getElementById('plateInput').value;

    if (!plate.trim()) {
        Toastify({
            text: "Please enter a plate number.",
            backgroundColor: "#f44336",
            close: true,
            gravity: "top",
            position: "right",
        }).showToast();
        return;
    }

    

    fetch(`search_visitor.php?plate=${encodeURIComponent(plate)}`)
        .then(res => res.json())
        .then(info => {
            if (info && info.plate) {
                const action = info.action === 'exit' ? 'Exit' : 'Entry';
                const buttonColor = action === 'Exit' ? '#f39c12' : '#3aa608ff';

                currentVisitor = info;

                console.log(currentVisitor.plate);

                document.getElementById("vehicleDetails").innerHTML = `
                    <div class="vehicle-detail"><strong>License Plate:</strong> <span>${info.plate}</span></div>
                    <div class="vehicle-detail"><strong>Owner:</strong> <span>${info.name}</span></div>
                    <div class="vehicle-detail"><strong>Vehicle Type:</strong> <span>${info.type}</span></div>
                    <div class="vehicle-detail"><strong>Address:</strong> <span>${info.color}</span></div>
                    <div class="vehicle-detail"><strong>Phone:</strong> <span>${info.phone}</span></div>
                    <div class="vehicle-detail"><strong>Purpose of Visit:</strong> <span>${info.purpose}</span></div>
                    <br><br>
                    <div class="vehicle-detail"><strong>Record this to Access logs?</strong> <span>action</span></div>
                    <div class="vehicle-detail mt-3">
                        <strong></strong>
                        <span>
                            <button onclick="cancelVisitor()" style="background-color:#c23e0aff; color:white; padding:0.4rem 1.2rem; border:none; border-radius:6px; cursor: pointer;">Cancel</button>

                            <button id="submitEntry" class="hidden" onclick="submitEntry()" style="background-color:${buttonColor}; color:white; padding:0.4rem 1.2rem; border:none; border-radius:6px; cursor: pointer;">${action}</button>

                            <button id="submitExit" class="hidden" onclick="submitExit(${info.log_id})" style="background-color:${buttonColor}; color:white; padding:0.4rem 1.2rem; border:none; border-radius:6px; cursor: pointer;">${action}</button>
                        </span>
                    </div>
                `;
                    // Show the correct button based on action
                if (info.action === 'entry') {
                    document.getElementById('submitEntry').style.display = 'inline-block';
                } else if (info.action === 'exit') {
                    document.getElementById('submitExit').style.display = 'inline-block';
                }
            } else {
                Toastify({
                    text: "No record found for this plate number.",
                    backgroundColor: "#f44336",
                    close: true,
                    gravity: "top",
                    position: "right",
                }).showToast();
            }
            closeModal();
        })
        .catch(error => {
            console.error("Error fetching visitor:", error);
            Toastify({
                text: "Something went wrong while fetching data.",
                backgroundColor: "#f44336",
                close: true,
                gravity: "top",
                position: "right",
            }).showToast();
            closeModal();
        });
}


function submitEntry() {
    if (!currentVisitor.plate || !currentVisitor.name) {
      showToast("No visitor info to log.", "error");
      return;
    }

    $.post('entry_access_log.php', {
      plate_number: currentVisitor.plate,
      owner_name: currentVisitor.name,
      vehicle_type: currentVisitor.type
    }, function(res) {
      if (res === 'success') {
        showToast("Visitor entry logged successfully!", "success");
        $('#visitorForm')[0].reset();
        $('#vehicleDetails').html(`<p class="placeholder">Vehicle has been recorded to access log.</p>`);
        currentVisitor = {};
      } else {
        showToast("Failed to log access. Try again.", "error");
      }
    });
  }

  function showToast(message, type = "info") {
    const colors = {
      success: "#28a745",
      error: "#dc3545",
      info: "#007bff"
    };

    Toastify({
      text: message,
      duration: 3000,
      gravity: "top",
      position: "right",
      backgroundColor: colors[type] || "#007bff",
      close: true
    }).showToast();
  }

function submitExit(logId) {
  $.post('update_time_out.php', { id: logId }, function(res) {
    if (res === 'success') {
      showToast("Exit time recorded successfully.", "success");
      $('#vehicleDetails').html(`<p class="placeholder">Exit time has been updated in access log.</p>`);
      loadAccessLogs(); // Refresh logs
    } else {
      showToast("Failed to update exit time.", "error");
    }
  });
}

$(document).ready(function() {
  loadAccessLogs();
});


</script>



</body>
</html>