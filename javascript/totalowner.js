$.ajax({
    url: '../database/totalowners.php', // Replace with the actual PHP file path
    type: 'GET',
    dataType: 'json',
    success: function (response) {
        $('#totalOwners').text(response.total_owners);
    },
    error: function (xhr, status, error) {
        console.error("Error fetching data:", error);
    }
});

$.ajax({
    url: '../database/totalvehicle.php', // Replace with the actual PHP file path
    type: 'GET',
    dataType: 'json',
    success: function (response) {
        $('#totalVehicles').text(response.total_vehicles);
    },
    error: function (xhr, status, error) {
        console.error("Error fetching data:", error);
    }
});
// Get total of today's entered logs

$.ajax({
    url: '../database/totalexit.php', // Replace with the actual PHP file path
    type: 'GET',
    dataType: 'json',
    success: function (response) {
        $('#total_exit').text(response.total_exit);
    },
    error: function (xhr, status, error) {
        console.error("Error fetching data:", error);
    }
});