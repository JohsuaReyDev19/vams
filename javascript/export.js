$(document).ready(function(){
    $("#menu-button").click(function() {
        $("#sidebar").toggleClass("-translate-x-full");
    });
    $("#closebtn").click(function(){
        $("#sidebar").toggleClass("-translate-x-full");
    });
});

function loadLogs() {
    $.ajax({
        url: "logs.php",
        type: "POST",
        data: {
            date: $("#filter_date").val(),
            owner: $("#filter_owner").val(),
            vehicle: $("#filter_vehicle").val(),
            entry: $("#filter_entry option:selected").val()
        },
        success: function(response) {
            $("#log_data").html(response);
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
            $("#log_data").html("<tr><td colspan='8' class='border p-2 text-red-500'>Failed to load data</td></tr>");
        }
    });
}

// Bind input events
$("#filter_date, #filter_owner, #filter_vehicle, #filter_entry").on("input change", loadLogs);

// Auto refresh every 3 seconds
setInterval(loadLogs, 700);

// Initial load
loadLogs();

// Export CSV Function
$("#export_csv").click(function() {
    let date = $("#filter_date").val();
    let owner = $("#filter_owner").val();
    let vehicle = $("#filter_vehicle").val();
    let entry = $("#filter_entry option:selected").val();
    
    window.location.href = `export.php?format=csv&date=${date}&owner=${owner}&vehicle=${vehicle}&entry=${entry}`;
});

// Export PDF Function
$("#export_pdf").click(function() {
    let date = $("#filter_date").val();
    let owner = $("#filter_owner").val();
    let vehicle = $("#filter_vehicle").val();
    let entry = $("#filter_entry option:selected").val();
    
    window.location.href = `export.php?format=pdf&date=${date}&owner=${owner}&vehicle=${vehicle}&entry=${entry}`;
});