$.ajax({
    url: '../database/totalentered.php', // Replace with the actual PHP file path
    type: 'GET',
    dataType: 'json',
    success: function (response) {
        $('#total_entered').text(response.total_entered);
    },
    error: function (xhr, status, error) {
        console.error("Error fetching data:", error);
    }
});