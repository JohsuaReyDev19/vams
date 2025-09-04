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
<body class="scroll-smooth min-h-screen bg-fixed bg-cover bg-center flex justify-center items-center" style="background-image: url('../../480156258_1036463918529354_3266044399125512979_n.jpg');">
  
  <!-- 🚫 Mobile Warning Overlay -->
  <div id="mobile-warning" class="fixed inset-0 bg-white z-50 flex flex-col items-center justify-center text-center p-6 hidden">
    <h2 class="text-2xl font-bold text-red-600 mb-4">Desktop Only</h2>
    <p class="text-gray-700">This RFID Scanner System is designed for desktop view only. Please access it on a pc/laptop.</p>
  </div>

  <!-- ✅ Main App Content -->
  <div class="w-full h-[100vh] flex items-center justify-center bg-gray-200 bg-opacity-80">
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
      </div>

      <div class="flex justify-between items-center mb-4">
        <input type="text" id="searchInput" placeholder="Search by plate..." class="border px-3 py-2 rounded w-1/3">
        <select id="limitSelect" class="border px-3 py-2 rounded">
          <option value="5" selected>5 per page</option>
          <option value="10">10 per page</option>
          <option value="20">20 per page</option>
        </select>
      </div>

      <div class="overflow-y-auto h-92 rounded-lg shadow">
        <table class="w-full text-sm text-center text-gray-600">
          <thead class="bg-gray-600 text-white sticky top-0">
            <tr>
              <th class="p-3">Date</th>
              <th class="p-3">Owner Name</th>
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
      <div id="pagination_controls" class="text-center mt-4"></div>
    </div>
  </div>

  <script>
    let lastScannedTag = "";
    let currentPage = 1;
    let limit = 5;
    let lastLogHash = '';

    function showNotification(type, message) {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 1000,
        timerProgressBar: true
      }).then(() => {
        // Wait 2 seconds after toast disappears, then reload
        setTimeout(() => {
          location.reload();
        }, 10000);
      });
    }

    function scanRFID(rfid) {
      $.ajax({
        url: 'scan_rfid.php',
        method: 'POST',
        dataType: 'json',
        data: { rfid_tag: rfid },
        success: function(response) {
          $('#rfid_input').val('');
          fetchLogs();
          if (response.success) {
            showNotification('success', response.message);
          } else {
            showNotification('error', response.message);
          }
        },
        error: function() {
          showNotification('error', 'Error connecting to server.');
        }
      });
    }

    function fetchLogs(silent = false) {
      const search = $('#searchInput').val();
      $.ajax({
        url: 'fetch_logs.php',
        method: 'GET',
        dataType: 'json',
        data: {
          page: currentPage,
          limit: limit,
          search: search
        },
        success: function(response) {
          if (response.success) {
            const currentHash = JSON.stringify(response.logs);
            if (currentHash !== lastLogHash) {
              lastLogHash = currentHash;
              renderTable(response.logs);
              renderPagination(response.total, limit, currentPage);
            } else if (!silent) {
              renderPagination(response.total, limit, currentPage);
            }
          } else {
            $('#logs_table_body').html(`<tr><td colspan="6" class="p-3 text-center text-red-500">${response.message}</td></tr>`);
          }
        },
        error: function() {
          $('#logs_table_body').html(`<tr><td colspan="6" class="p-3 text-center text-red-500">Failed to fetch logs.</td></tr>`);
        }
      });
    }

    function renderTable(logs) {
      let tbody = '';
      if (logs.length > 0) {
        logs.forEach(log => {
          tbody += `
            <tr class="border-b hover:bg-gray-100 transition">
              <td class="p-3">${formatDate(log.time_in)}</td>
              <td class="p-3">${log.owner_name}</td>
              <td class="p-3">${log.plate_number}</td>
              <td class="p-3">${log.vehicle_type}</td>
              <td class="p-3">${formatTime(log.time_in)}</td>
              <td class="p-3">${formatTime(log.time_out)}</td>
            </tr>`;
        });
      } else {
        tbody = `<tr><td colspan="6" class="p-3 text-center text-gray-500">No logs found.</td></tr>`;
      }
      $('#logs_table_body').html(tbody);
    }

    function renderPagination(total, limit, page) {
      const totalPages = Math.ceil(total / limit);
      let html = '';
      for (let i = 1; i <= totalPages; i++) {
        html += `<button onclick="changePage(${i})" class="mx-1 px-3 py-1 rounded ${i === page ? 'bg-blue-600 text-white' : 'bg-gray-200'}">${i}</button>`;
      }
      $('#pagination_controls').html(html);
    }

    function changePage(page) {
      currentPage = page;
      fetchLogs();
    }

    function formatDate(datetime) {
      if (!datetime) return '—';
      return new Date(datetime).toLocaleDateString('en-CA');
    }

    function formatTime(datetime) {
      if (!datetime) return '—';
      return new Date(datetime).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    }

    function pollRFIDTag() {
      fetch('read_tag.php')
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
            scanRFID(tag);
          }
        });
    }

    // ✅ Detect Mobile
    function checkIfMobile() {
      if (window.innerWidth < 768) {
        document.getElementById("mobile-warning").classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
      } else {
        document.getElementById("mobile-warning").classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
      }
    }

    $(document).ready(function() {
      limit = parseInt($('#limitSelect').val());
      $('#rfid_input').focus();
      fetchLogs();

      setInterval(pollRFIDTag, 500);
      setInterval(() => {
        fetchLogs(true);
      }, 3000);

      $('#searchInput, #limitSelect').on('input change', function() {
        limit = parseInt($('#limitSelect').val());
        currentPage = 1;
        fetchLogs();
      });

      checkIfMobile();
      window.addEventListener('resize', checkIfMobile);
    });
  </script>
</body>
</html>
