<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>RFID Live Scanner</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .scanner-container {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: #ffffff;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
      border: 1px solid #e2e8f0;
      position: relative;
      overflow: hidden;
    }

    .scan-line {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(to right, transparent, #0ea5e9, transparent);
      animation: scanDown 2.2s ease-in-out infinite;
      opacity: 0.9;
    }

    @keyframes scanDown {
      0% { top: 0; opacity: 0; }
      10% { opacity: 1; }
      50% { top: 100%; opacity: 1; }
      90% { opacity: 1; }
      100% { top: 0; opacity: 0; }
    }

    .glass-glow {
      position: absolute;
      inset: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(6px);
      pointer-events: none;
      z-index: 1;
    }

    .center-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #1e293b;
      font-size: 10px;
      z-index: 2;
      font-weight: 500;
    }
  </style>
</head>
<body>
  
  <!-- 🚫 Mobile Warning Overlay -->

  <!-- ✅ Main App Content -->
  <div class="flex items-center justify-center bg-gray-200 bg-opacity-80">
    <div class="bg-white rounded-2xl p-6 w-[90vw] max-w-[1200px]">
      <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">RFID Sticker Live Scanner</h2>

      <div class="flex flex-col items-center mb-4">
        <input 
          type="text" 
          id="rfid_input" 
          placeholder="Scan RFID..." 
          class="w-1/2 p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 text-center text-lg hidden"
          autofocus 
          autocomplete="off"
        >
        <input type="text" id="fullName" value="<?php echo $_SESSION['full_name']; ?>" class="hidden">
        <div id="scanbar" class="flex justify-center items-center m-1">
          <div class="scanner-container">
            <div class="scan-line"></div>
            <div class="glass-glow"></div>
            <div class="center-text">Scanning...</div>
          </div>
        </div>
        <p class="text-gray-600 mt-2 text-center">
          Last Scanned Tag: <span id="tagDisplay" class="font-mono text-green-700 text-lg"></span>
        </p>
        <span id="scan_message" class="text-sm"></span>
      </div>
<!-- 📊 Rows per page -->
                        <div class="flex items-center gap-2 justify-between mb-2">
                            <div>
                              <label for="rowsPerPage">Show</label>
                            <select id="rowsPerPage" class="border rounded px-2 py-1">
                            <option value="5" selected>5</option>
                            <option value="10" >10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="all">All</option>
                            </select>
                            <span>entries</span>
                            </div>
                            <div class="flex items-center gap-2">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search text-gray-400"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                              <div class="flex-shrink-0">
                              <input type="text" id="search" onkeyup="liveSearch()" 
                                  class="p-2 border rounded w-60" placeholder="Search...">
                              </div>
                            </div>
                        </div>
      <div class="overflow-y-auto h-92 rounded-lg shadow">
        <table class="w-full text-sm text-center text-gray-600">
          <thead class="bg-gray-600 text-white sticky top-0">
            <tr>
              <th class="p-3">Date</th>
              <th class="p-3">Owner Name</th>
              <th class="p-3">Purpose</th>
              <th class="p-3">Plate Number</th>
              <th class="p-3">Vehicle Type</th>
              <th class="p-3">Time In</th>
              <th class="p-3">Time Out</th>
            </tr>
          </thead>
          <tbody id="logs_table_body" class="bg-white">
            <!-- Dynamic rows -->
          </tbody>
        </table>
      </div>
      <div id="pagination" class="flex justify-center mt-1"></div>
    </div>
  </div>

  <script>

let allLogs = [];
let filteredLogs = [];
let currentPage = 1;
let rowsPerPage = 5; // default

// ✅ Fetch and update logs
function loadLogs() {
    $.ajax({
        url: "./fetch_access_log.php", // adjust path
        method: "GET",
        dataType: "json",
        success: function(res) {
            if (res.success) {
                allLogs = res.data;
                filteredLogs = [...allLogs]; // show all logs initially
                renderTable();
            }
        },
        error: function(err) {
            console.error("Error fetching logs:", err);
        }
    });
}

// ✅ Apply Date Filter
function applyDateFilter() {
    let startDate = document.getElementById("startDate").value;
    let endDate = document.getElementById("endDate").value;

    if (!startDate && !endDate) {
        filteredLogs = [...allLogs]; // reset
    } else {
        let start = startDate ? new Date(startDate + "T00:00:00") : null;
        let end = endDate ? new Date(endDate + "T23:59:59") : null;

        filteredLogs = allLogs.filter(log => {
            let logDate = new Date(log.timestamp + " UTC"); // parse in UTC
            return (!start || logDate >= start) && (!end || logDate <= end);
        });
    }

    currentPage = 1;
    renderTable();
}

// ✅ Render Table with Pagination
function renderTable() {
    let start = (currentPage - 1) * rowsPerPage;
    let end = rowsPerPage === "all" ? filteredLogs.length : start + rowsPerPage;

    let logsToDisplay = rowsPerPage === "all"
        ? filteredLogs
        : filteredLogs.slice(start, end);

    let rows = "";
    logsToDisplay.forEach(log => {
        rows += `
            <tr class="border-b hover:bg-gray-100 transition">
                <td class="p-3">${log.timestamp}</td>
                <td class="p-3">${log.owner_name}</td>
                <td class="p-3">${log.purpose || ""}</td>
                <td class="p-3">${log.plate_number}</td>
                <td class="p-3">${log.vehicle_type}</td>
                <td class="p-3">${log.time_in}</td>
                <td class="p-3">${log.time_out ? log.time_out : "-- --"}</td>
            </tr>
        `;
    });

    $("#logs_table_body").html(rows);
    renderPagination();
}

function renderPagination() {
    if (rowsPerPage === "all") {
        $("#pagination").html(""); 
        return;
    }

    let totalPages = Math.ceil(filteredLogs.length / rowsPerPage);
    if (totalPages <= 1) {
        $("#pagination").html("");
        return;
    }

    let html = `<div class="flex items-center justify-center gap-1 mt-3 text-sm">`;

    // Previous Button
    if (currentPage > 1) {
        html += `
            <button onclick="changePage(${currentPage - 1})" 
                class="px-2 py-1 rounded border bg-white hover:bg-gray-100 text-gray-700 shadow-sm text-xs">
                «
            </button>`;
    } else {
        html += `
            <button disabled
                class="px-2 py-1 rounded border bg-gray-200 text-gray-400 cursor-not-allowed text-xs">
                «
            </button>`;
    }

    // Page Info
    html += `
        <span class="px-2 py-1 rounded bg-gray-500 text-white font-medium shadow-sm text-xs">
            Page ${currentPage} of ${totalPages}
        </span>`;

    // Next Button
    if (currentPage < totalPages) {
        html += `
            <button onclick="changePage(${currentPage + 1})" 
                class="px-2 py-1 rounded border bg-white hover:bg-gray-100 text-gray-700 shadow-sm text-xs">
                »
            </button>`;
    } else {
        html += `
            <button disabled
                class="px-2 py-1 rounded border bg-gray-200 text-gray-400 cursor-not-allowed text-xs">
                »
            </button>`;
    }

    html += `</div>`;
    $("#pagination").html(html);
}



function changePage(page) {
    currentPage = page;
    renderTable();
}

// ✅ Rows per page dropdown
$("#rowsPerPage").on("change", function () {
    let val = $(this).val();
    rowsPerPage = val === "all" ? "all" : parseInt(val);
    currentPage = 1;
    renderTable();
});

// ✅ Live Search
$("#search").on("keyup", function() {
    let input = $(this).val().toLowerCase();
    filteredLogs = allLogs.filter(log =>
        (log.owner_name + " " + log.purpose + " " + log.plate_number + " " + log.vehicle_type + " " + (log.access_by || ""))
        .toLowerCase().includes(input)
    );
    currentPage = 1;
    renderTable();
});

// ✅ Load logs immediately + refresh every 5s
loadLogs();

let lastScannedTag = "";
    function scanRFID(rfid) {
        const accessName = document.getElementById('fullName').value;

        $.ajax({
            url: '../scanprocess/scan_rfid.php',
            method: 'POST',
            dataType: 'json',
            data: { rfid_tag: rfid, access: accessName },
            success: function(response) {
                $('#rfid_input').val('');
                loadLogs();

                const scanMessage = document.getElementById('scan_message');

                // Clear old classes
                scanMessage.classList.remove('text-green-500', 'text-red-500');

                if (response.success) {
                    scanMessage.innerText = response.message;
                    scanMessage.classList.add('text-green-500'); // Success = green

                      setTimeout(() => {
                        location.reload();
                    }, 10000);
                    
                } else {
                    scanMessage.innerText = response.message || 'Scan failed.';
                    scanMessage.classList.add('text-red-500'); // Error = red
                    
                    setTimeout(() => {
                        location.reload();
                    }, 10000);
                }
            },
            error: function() {
                const scanMessage = document.getElementById('scan_message');
                scanMessage.classList.remove('text-green-500');
                scanMessage.classList.add('text-red-500');
                scanMessage.innerText = 'Error connecting to server.';
            }
        });
    }

    function pollRFIDTag() {
      fetch('../scanprocess/read_tag.php')
        .then(res => res.text())
        .then(raw => {
          let tag = "";
          try {
            const data = JSON.parse(raw);
            tag = data.tag || "";
          } catch {
            tag = raw.trim();
          }

          if (tag && tag !== lastScannedTag) {
            lastScannedTag = tag;
            $('#rfid_input').val(tag);
            document.getElementById("tagDisplay").textContent = tag;

            // // ✅ Clear after 10 seconds (one-time)
            // setTimeout(() => {
            //   document.getElementById("tagDisplay").textContent = '';
            //   $('#rfid_input').val('');
            // }, 10000);

            scanRFID(tag);
          }
        })
        .catch(err => console.error("RFID Poll Error:", err));
    }


    $(document).ready(function() {
      limit = parseInt($('#limitSelect').val());
      $('#rfid_input').focus();
      loadLogs();

      setInterval(pollRFIDTag, 500);
      setInterval(() => {
        loadLogs(true);
      }, 3000);

      $('#searchInput, #limitSelect').on('input change', function() {
        limit = parseInt($('#limitSelect').val());
        currentPage = 1;
        loadLogs();
      });

      checkIfMobile();
      window.addEventListener('resize', checkIfMobile);
    });
  </script>
</body>
</html>