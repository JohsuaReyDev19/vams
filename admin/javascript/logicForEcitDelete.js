$(document).ready(function () {
    // Open Edit Modal and Fill Data
    $(document).on("click", ".edit-btn", function () {
        $("#editVehicleId").val($(this).data("id"));
        $("#editName").val($(this).data("name"));
        $("#editVehicle").val($(this).data("vehicle"));
        $("#editRFID").val($(this).data("rfid"));
        $("#editOwnerModal").removeClass("hidden");
    });

    // Close Edit Modal
    $("#closeEditModal").click(function () {
        $("#editOwnerModal").addClass("hidden");
    });

    // Handle Edit Form Submission
    $("#editVehicleForm").submit(function (e) {
        e.preventDefault();
        let vehicleId = $("#editVehicleId").val();
        let updatedData = {
            id: vehicleId,
            name: $("#editName").val(),
            vehicle: $("#editVehicle").val(),
            rfid: $("#editRFID").val(),
        };

        $.post("../database/update_vehicle.php", updatedData, function (response) {
            let res = JSON.parse(response);
            if (res.status === "success") {
                Swal.fire("Updated!", res.message, "success").then(() => {
                    location.reload();
                });
            } else {
                Swal.fire("Error!", res.message, "error");
            }
        });
    });

    // Delete Vehicle
    $(document).on("click", ".delete-btn", function () {
        let vehicleId = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to undo this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("../database/delete_vehicle.php", { id: vehicleId }, function (response) {
                    let res = JSON.parse(response);
                    if (res.status === "success") {
                        Swal.fire("Deleted!", res.message, "success").then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error!", res.message, "error");
                    }
                });
            }
        });
    });
});
