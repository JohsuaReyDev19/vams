$(document).ready(function () {
    function validateField(input, errorElement, condition) {
        if (condition) {
            $(errorElement).removeClass('hidden');
            return false;
        } else {
            $(errorElement).addClass('hidden');
            return true;
        }
    }

    function isEmptyField(selector) {
        return $(selector).val().trim() === "";
    }

    // Real-time validation
    $('#ownerName').on('input', function () {
        validateField(this, '#nameError', $(this).val().length < 2);
    });

    $('#rfidNumber').on('input', function () {
        validateField(this, '#rfidError', $(this).val().length < 8);
    });

    $('#course').on('change', function () {
        validateField(this, '#courseError', isEmptyField(this));
    });

    $('#contactNumber').on('input', function () {
        validateField(this, '#contactError', $(this).val().length !== 11 || isNaN(Number($(this).val())));
    });

    $('#plateNumber').on('input', function () {
        validateField(this, '#plateError', $(this).val().length < 6);
    });

    $('#registrationForm').submit(function (event) {
        event.preventDefault();
        let isValid = true;

        if (!validateField('#ownerName', '#nameError', $('#ownerName').val().length < 2)) isValid = false;
        if (!validateField('#rfidNumber', '#rfidError', $('#rfidNumber').val().length < 8)) isValid = false;
        if (!validateField('#course', '#courseError', isEmptyField('#course'))) isValid = false;
        if (!validateField('#contactNumber', '#contactError', $('#contactNumber').val().length !== 11 || isNaN(Number($('#contactNumber').val())))) isValid = false;
        if (!validateField('#plateNumber', '#plateError', $('#plateNumber').val().length < 6)) isValid = false;

        // Check if any field is empty
        if (isEmptyField('#ownerName') || isEmptyField('#rfidNumber') || isEmptyField('#course') || 
            isEmptyField('#contactNumber') || isEmptyField('#plateNumber')) {
            Swal.fire({
                icon: "error",
                title: "Missing Input!",
                text: "Please fill out all required fields before submitting.",
                confirmButtonColor: "#d33",
                confirmButtonText: "OK"
            });
            return; // Stop submission
        }

        if (isValid) {
            Swal.fire({
                icon: "success",
                title: "Registration Successful!",
                text: "Your vehicle has been registered.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload(); // Reload page after clicking OK
                }
            });

            $('#registrationForm')[0].reset();
            $('#submitBtn').prop('disabled', true); // Disable submit after submission
        }
    });

    // Agreement checkbox logic
    $('#agreement').change(function () {
        let isChecked = this.checked;
        $('#submitBtn').prop('disabled', !isChecked);

        if (isChecked) {
            $('#submitBtn').show();
            $('#term').show();
            $('#unsubmitBtn').hide();
        } else {
            $('#submitBtn').hide();
            $('#term').hide();
            $('#unsubmitBtn').show();
        }
    });

    // Ensure submit button is disabled on page load
    $('#submitBtn').prop('disabled', !$('#agreement').prop('checked'));
});
