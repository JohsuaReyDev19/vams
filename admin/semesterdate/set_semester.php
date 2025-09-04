
<script>


$(document).ready(function() {
    // 🔹 Load saved dates on page load
    $.post("../database/semester.php", { action: "get_dates" }, function(res) {
        console.log("Loaded semester dates:", res); // DEBUG

        if (res.success && res.data) {
            $("#startDateInput").val(res.data.start_date);
            $("#endDateInput").val(res.data.end_date);

            checkEndDate(); // check if end date <= today

            // If semester already started, lock Start button + disable date inputs
            if (res.data.start_date !== "0000-00-00" && res.data.end_date !== "0000-00-00") {
                $("#StartSemesterBtn")
                    .text("Ongoing Semester")
                    .prop("disabled", true)
                    .addClass("opacity-50 cursor-not-allowed");

                // ⛔ Disable date inputs
                $("#startDateInput, #endDateInput").prop("disabled", true);
                $("#modal_semester").addClass("hidden");
            }
        } else {
            console.warn("No semester dates found in DB");
        }
    }, "json");

    // 🔹 Handle Start Semester
    $("#StartSemesterBtn").click(function() {
        let startDate = $("#startDateInput").val();
        let endDate = $("#endDateInput").val();

        if (!startDate || !endDate) {
            Swal.fire({
                icon: "warning",
                title: "Missing Dates",
                text: "Please select both start and end dates."
            });
            return;
        }

        $.post("../database/semester.php", 
            { action: "start", start_date: startDate, end_date: endDate }, 
            function(res) {
                console.log("Start Semester Response:", res); // DEBUG
                if (res.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Semester Started",
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Update button state
                    $("#StartSemesterBtn")
                        .text("Ongoing Semester")
                        .prop("disabled", true)
                        .addClass("opacity-50 cursor-not-allowed");

                        $("#modal_semester").addClass("hidden");
                    // ⛔ Disable date inputs until semester ends
                    $("#startDateInput, #endDateInput").prop("disabled", true);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.message
                    });
                }
            }, 
        "json");
    });

    // 🔹 Function to check if End Date <= Today
    function checkEndDate() {
        let endDate = $("#endDateInput").val();
        let today = new Date().toISOString().split("T")[0]; // YYYY-MM-DD

        if (endDate && endDate <= today) {   // ✅ less than OR equal
            $("#EndSemesterBtn").removeClass("hidden");
            $("#StartSemesterBtn").addClass("hidden"); 
        } else {
            $("#EndSemesterBtn").addClass("hidden");
            if (!$("#StartSemesterBtn").prop("disabled")) {
                $("#StartSemesterBtn").removeClass("hidden");
            }
        }
    }

    // Recheck when endDate input changes
    $("#endDateInput").on("change", checkEndDate);

    // Auto-check every 1 minute
    setInterval(checkEndDate, 700);

    // 🔹 Handle End Semester
    $("#EndSemesterBtn").click(function() {
        $.post("../database/semester.php", { action: "end" }, function(res) {
            console.log("End Semester Response:", res); // DEBUG

            if (res.success) {
                Swal.fire({
                    icon: "success",
                    title: "Semester Ended",
                    text: res.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                // Reset Start button
                $("#StartSemesterBtn")
                    .text("Start Semester")
                    .prop("disabled", false)
                    .removeClass("opacity-50 cursor-not-allowed hidden");

                $("#EndSemesterBtn").addClass("hidden");

                // ✅ Re-enable date inputs so admin can select new semester
                $("#startDateInput, #endDateInput").prop("disabled", false);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: res.message
                });
            }
        }, "json");
    });
});
</script>