<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../../index.php"); // Redirect users who are not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  <title>Access logs</title>
  <style>
    select { background-position: right 0.5rem center; padding-right: 1rem; }
  </style>
</head>
<body>
  <?php include './sidebar.php'?>

  <div class="p-6 sm:ml-64">
    <div class="w-full mx-auto mt-10">
      <div class="w-full">
        <h1 class="font-bold text-3xl mb-2">Access Logs</h1>
        <p class="text-gray-500">View and manage all vehicle access events in the system</p>
      </div>

      <div class="bg-white p-6 rounded-lg mt-6 border">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-2 overflow-x-auto">

  <!-- Search & Filters -->
  <div class="flex flex-nowrap items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 flex-shrink-0"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
    </svg>
    <input type="text" id="search" onkeyup="liveSearch()"
           class="p-2 border rounded w-40 sm:w-60"
           placeholder="Search...">

    <input type="date" id="startDate" class="p-2 border rounded w-36">
    <span class="text-sm">to</span>
    <input type="date" id="endDate" class="p-2 border rounded w-36">

    <button id="showclear" onclick="applyDateFilter()"
            class="bg-blue-500 text-white px-3 py-1 rounded text-sm"
            title="Filter between date">Filter</button>

    <span id="clearshow"
          class="hidden bg-gray-500 text-white px-3 py-1 rounded text-sm cursor-pointer"
          title="Clear date">Clear</span>
  </div>

  <!-- Buttons -->
  <div class="flex flex-nowrap items-center gap-2">
    <button onclick="exportToWord()"
            class="bg-blue-600 text-white px-3 py-2 rounded text-sm">
      Export to Word
    </button>

    <button onclick="printTable()"
            class="bg-green-500 text-white px-3 py-2 rounded text-sm flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
        <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/>
        <rect x="6" y="14" width="12" height="8" rx="1"/>
      </svg>
      Print
    </button>
  </div>

</div>

        <img src="./images-removebg-preview.png" alt="" class="hidden">

        <div class="flex items-center gap-2 mb-2">
          <label for="rowsPerPage">Show</label>
          <select id="rowsPerPage" class="border rounded px-2 py-1">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="all">All</option>
          </select>
          <span>entries per page</span>
        </div>

        <div id="printableArea" class="overflow-y-auto h-100 rounded-lg shadow">
          <table class="w-full text-sm text-center text-gray-600" id="logs_table">
            <thead class="bg-gray-600 text-white sticky top-0">
              <tr>
                <th class="p-3">Date</th>
                <th class="p-3">Owner Name</th>
                <th class="p-3">Purpose</th>
                <th class="p-3">Plate Number</th>
                <th class="p-3">Vehicle Type</th>
                <th class="p-3">Time In</th>
                <th class="p-3">Time Out</th>
                <th class="p-3">Assist By:</th>
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
  </div>

  <!-- Loader -->
  <div id="loader" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-50 hidden">
    <div class="loading loading-bars loading-lg text-yellow-600"></div>
  </div>

<!-- Docx + FileSaver -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/docx/8.0.0/docx.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script>
async function exportToWord() {
  const {
    Document, Packer, Paragraph, Table, TableRow, TableCell,
    AlignmentType, TextRun, WidthType, BorderStyle
  } = docx;

  // Letterhead (centered text only, matches printable format)
  const header = [
    new Paragraph({
      alignment: AlignmentType.CENTER,
      children: [ new TextRun({ text: "Republic of the Philippines", size: 20 }) ]
    }),
    new Paragraph({
      alignment: AlignmentType.CENTER,
      children: [ new TextRun({ text: "President Ramon Magsaysay State University", bold: true, size: 28 }) ]
    }),
    new Paragraph({
      alignment: AlignmentType.CENTER,
      children: [ new TextRun({ text: "(Formerly Ramon Magsaysay State University)", italics: true, size: 20 }) ]
    }),
    new Paragraph({
      alignment: AlignmentType.CENTER,
      children: [ new TextRun({ text: "Candelaria Campus", size: 22 }) ]
    }),
    new Paragraph({ text: "" }) // spacing
  ];

  // Title (extra spacing for separation)
  const title = new Paragraph({
    alignment: AlignmentType.CENTER,
    spacing: { before: 200, after: 400 },
    children: [ new TextRun({ text: "Access Log of Vehicles", bold: true, size: 30 }) ]
  });

  // Extract table headers & rows
  const headers = Array.from(document.querySelectorAll("table thead th")).map(th => th.innerText);
  const rows = Array.from(document.querySelectorAll("#logs_table_body tr")).map(tr =>
    Array.from(tr.querySelectorAll("td")).map(td => td.innerText)
  );

  // Build Word table
  const wordRows = [
    new TableRow({
      children: headers.map(h =>
        new TableCell({
          children: [ new Paragraph({ text: h, bold: true }) ],
          shading: { fill: "E5E7EB" },
          borders: {
            top: {style: BorderStyle.SINGLE}, bottom: {style: BorderStyle.SINGLE},
            left: {style: BorderStyle.SINGLE}, right: {style: BorderStyle.SINGLE}
          }
        })
      )
    }),
    ...rows.map(r => new TableRow({
      children: r.map(cell =>
        new TableCell({
          children: [ new Paragraph({ text: cell, size: 22 }) ],
          borders: {
            top: {style: BorderStyle.SINGLE}, bottom: {style: BorderStyle.SINGLE},
            left: {style: BorderStyle.SINGLE}, right: {style: BorderStyle.SINGLE}
          }
        })
      )
    }))
  ];

  const wordTable = new Table({
    width: { size: 100, type: WidthType.PERCENTAGE },
    rows: wordRows
  });

  // Build document
  const doc = new Document({
    sections: [{
      children: [...header, title, wordTable]
    }]
  });

  const blob = await Packer.toBlob(doc);
  saveAs(blob, "Access_Log.docx");
}
</script>


<script>
let allLogs = [];
let filteredLogs = [];
let currentPage = 1;
let rowsPerPage = 10; // default

// Fetch and update logs
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

// Apply Date Filter
function applyDateFilter() {
    let startDate = document.getElementById("startDate").value;
    let endDate = document.getElementById("endDate").value;

    if(!startDate && !endDate){
        $('#clearshow').addClass('hidden');
    }else{
        $('#clearshow').removeClass('hidden');
    }

    $('#clearshow').off('click').on('click',function(){
        $('#startDate').val('');  // clear input
        $('#endDate').val('');    // clear input
        $('#clearshow').addClass('hidden');
        loadLogs();
        currentPage = 1;
        renderTable();
    })

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

// Render Table with Pagination
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
                <td class="p-3">${log.access_by || ""}</td>
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

// Rows per page dropdown
$("#rowsPerPage").on("change", function () {
    let val = $(this).val();
    rowsPerPage = val === "all" ? "all" : parseInt(val);
    currentPage = 1;
    renderTable();
});

// Live Search (jQuery handler)
$("#search").on("keyup", function() {
    let input = $(this).val().toLowerCase();
    filteredLogs = allLogs.filter(log =>
        (log.owner_name + " " + (log.purpose||"") + " " + (log.plate_number||"") + " " + (log.vehicle_type||"") + " " + (log.access_by || ""))
        .toLowerCase().includes(input)
    );
    currentPage = 1;
    renderTable();
});

// make the inline onkeyup="liveSearch()" safe (simple shim)
function liveSearch(){
  // trigger keyup event on the #search input to reuse the jQuery handler
  $('#search').trigger('keyup');
}

// Load logs immediately
loadLogs();

/* ---------------------------
  Print function (unchanged)
----------------------------*/
function printTable() {
  const originalContent = document.body.innerHTML;
  const printableArea = document.getElementById('printableArea').cloneNode(true);
  printableArea.classList.remove('overflow-y-auto', 'h-96');

  document.body.innerHTML = `
    <div>
      <header class="text-black py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-center px-4">
          <div id="logo1" class="w-20 h-20 flex-shrink-0">
            <img src="../prmsu.png" class="w-[70px] h-[70px] object-contain">
          </div>
          <div class="text-center flex-1 px-4">
            <p class="text-sm">Republic of the Philippines</p>
            <h1 class="text-2xl font-bold" style="font-family: 'Old English Text MT', serif;">President Ramon Magsaysay State University</h1>
            <p class="text-sm italic">(Formerly Ramon Magsaysay State University)</p>
            <p class="text-sm">Candelaria Campus</p>
          </div>
          <div id="logo2" class="w-20 h-20 flex-shrink-0">
            <img src="../pages/images-removebg-preview.png" class="w-[70px] h-[70px] object-contain">
          </div>
        </div>
      </header>
      <p class="text-md text-center my-5">Access Log of Vehicles</p>
      ${printableArea.outerHTML}
    </div>
  `;
  window.print();
  document.body.innerHTML = originalContent;
  location.reload();
}

/* ---------------------------
   NEW: Export to Word (HTML-based)
   - Reason: browser-friendly, no external docx global needed
----------------------------*/
async function getBase64(url) {
  try {
    const res = await fetch(url, {cache: "no-store"});
    if (!res.ok) return null;
    const blob = await res.blob();
    return await new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = () => resolve(reader.result); // data:[...]
      reader.onerror = reject;
      reader.readAsDataURL(blob);
    });
  } catch (e) {
    console.warn('getBase64 error for', url, e);
    return null;
  }
}

async function exportToWord() {
  // Prepare embedded images (base64) so Word can display them
  const logoLeft = await getBase64('../prmsu.png');
  const logoRight = await getBase64('../pages/images-removebg-preview.png');

  // Clone printable area and remove overflow/height so HTML is clean
  const printable = document.getElementById('printableArea').cloneNode(true);
  printable.style.overflow = 'visible';
  printable.style.height = 'auto';

  // Inline CSS for the generated Word (keeps table borders & look)
  const css = `
    body { font-family: Arial, sans-serif; color: #111; }
    .letterhead { width:100%; display:flex; align-items:center; justify-content:center; }
    .lh-left, .lh-right { width:70px; }
    .lh-center { flex:1; text-align:center; }
    .lh-center h2 { margin:0; font-size:18px; font-weight:bold; }
    .lh-center p { margin:0; font-size:12px; }
    table { width:100%; border-collapse:collapse; margin-top:12px; }
    table, th, td { border:1px solid #ccc; }
    th { background:#374151; color:#fff; padding:6px; font-weight:700; }
    td { padding:6px; text-align:center; }
  `;

  const headerHTML = `
    <div class="letterhead">
      <div class="lh-center">
        <p style="margin:0;font-size:12px;">Republic of the Philippines</p>
        <h2 style="margin:0;font-family:'Old English Text MT', serif;">President Ramon Magsaysay State University</h2>
        <p style="margin:0;font-size:12px;font-style:italic;">(Formerly Ramon Magsaysay State University)</p>
        <p style="margin:0;font-size:12px;">Candelaria Campus</p>
      </div>
    </div>
    <h3 style="text-align:center;margin-top:12px;">Access Log of Vehicles</h3>
  `;

  // Only include the table HTML from printable (so we don't bring scripts)
  // Find the <table> inside the cloned printable area
  const clonedTable = printable.querySelector('table');
  const tableHTML = clonedTable ? clonedTable.outerHTML : '<p>No table found</p>';

  const fullHTML = `<!doctype html>
    <html>
      <head>
        <meta charset="utf-8"/>
        <title>Access Log</title>
        <style>${css}</style>
      </head>
      <body>
        ${headerHTML}
        ${tableHTML}
      </body>
    </html>`;

  // Create a blob and force download as .doc (Word will open it)
  const blob = new Blob([fullHTML], { type: 'application/msword' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'Access_Log.doc';
  document.body.appendChild(a);
  a.click();
  setTimeout(() => {
    URL.revokeObjectURL(url);
    a.remove();
  }, 500);
}
</script>

</body>
</html>