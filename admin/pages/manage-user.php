<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../../index.php"); // Redirect users who are not logged in
    exit();
 }
?>

<?php
// Get the values from URL
$id = $_GET['id'] ?? null;
$name = $_GET['name'] ?? null;

// Optional: Sanitize for security
$id = htmlspecialchars($id);
$name = htmlspecialchars(urldecode($name));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <title>Vehicles</title>
    <style>
        #ownerList {
            scrollbar-width: none; /* Hide scrollbar for Firefox */
            -ms-overflow-style: none; /* Hide scrollbar for IE and Edge */
        }

        #ownerList::-webkit-scrollbar {
            display: none; /* Hide scrollbar for Chrome, Safari, and Edge */
        }
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.3s ease-out forwards;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <?php include './sidebar.php'?>
  <div class="p-6 sm:ml-64">
    <div class="w-full mx-auto" id="vehicleshow">
                    <div class="w-full mt-12">
                        <h1 class="font-bold text-3xl mb-2">Security Personel Account's</h1>
                        <p class="text-gray-500">Manage User</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-xl flex flex-wrap gap-4 justify-between items-center mt-6">
                    <!-- Filters Section -->
                    <div class="w-full flex flex-col md:flex-row md:items-end md:gap-4 space-y-2 md:space-y-0">
                        <button id="addVehicleBtn"
                            class="w-full md:w-auto px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg shadow-sm text-sm transition duration-150 ease-in-out">
                            + Add
                        </button>
                        <a id="btnBack" href="./manage-user.php"
                            class="w-full md:w-auto px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg shadow-sm text-sm transition duration-150 ease-in-out hidden">
                            Back
                        </a>
                    </div>
                </div>
                    <!-- vehicle list -->
                    <div id="vehicleList" class="bg-white p-4 rounded-lg">
                        <div id="hideTable" class="overflow-x-auto w-full">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md text-xs sm:text-xs">
                                <thead class="bg-gray-100">
                                    <tr class="text-center">
                                        <th class="px-2 py-1.5">Name</th>
                                        <th class="px-2 py-1.5">Contact No.</th>
                                        <th class="px-2 py-1.5">Address</th>
                                        <th class="px-2 py-1.5">email</th>
                                        <th class="px-2 py-1.5">Username</th>
                                        <th class="px-2 py-1.5">Created at</th>
                                        <th class="px-2 py-1.5 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="accountTableBody">
                                    <!-- Dynamic rows will be inserted here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Profile Info -->
                        <div id="profile" class="bg-white shadow rounded-lg p-6 hidden fade-in">
                            <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                            <br>
                            <form id="updateProfileForm">
                                <input type="hidden" id="editVehicleId">
                                
                                <label for="fullname" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" id="fullname" class="mt-1 mb-2 p-2 w-full border rounded-md" required>

                                <label for="contact" class="block text-sm font-medium text-gray-700">Contact no.</label>
                                <input 
                                    type="tel" 
                                    id="contact" 
                                    class="mt-1 p-2 w-full border rounded-md"
                                    pattern="[0-9]{11}"
                                    maxlength="11"
                                    required
                                >

                                <label for="address" class="block text-sm font-medium text-gray-700 mt-4">Address</label>
                                <input type="text" id="address" class="mt-1 p-2 w-full border rounded-md" required>

                                <label for="username" class="block text-sm font-medium text-gray-700 mt-4">Username</label>
                                <input type="text" id="username" class="mt-1 p-2 w-full border rounded-md" required>

                                <label for="mail" class="block text-sm font-medium text-gray-700 mt-4">Email</label>
                                <input type="email" id="mail" class="mt-1 p-2 w-full border rounded-md" required>

                                <div class="flex justify-center" id="error">
                                    <p class="text-red-600 text-sm" id="message"></p>
                                </div>

                                <div class="mt-6 flex justify-end space-x-4">
                                    <button type="button" id="deleteBtn" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>
                                    <button type="submit" id="saveBtn" class="bg-gray-500 text-white px-4 py-2 rounded-md">Save Changes</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- image modal -->
                <div id="imageModal" class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-75 flex justify-center items-center z-50">
                    <div class="relative">
                        <button onclick="closeModal()" class="bg-gray-700 rounded-full absolute top-0 right-0 p-2 text-white text-xl">×</button>
                        <img id="modalImage" class="max-w-full max-h-screen object-contain" alt="Modal Image" />
                    </div>
                </div>
            </div>
  </div>

<!-- add Modal -->
<div id="addModal" class="fixed inset-0 flex justify-center items-center bg-gray-600 bg-opacity-50 z-50 hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-3/4 md:w-1/2 lg:w-1/3 xl:w-1/4 fade-in">
        <h2 class="text-xl font-semibold mb-4">Add Security Personnel</h2>
        
        <form id="createAccountForm">
            <input type="hidden" id="editVehicleId" name="editVehicleId">
            
            <label for="fullname" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="fullname" name="fullname" class="mt-1 mb-2 p-2 w-full border rounded-md" placeholder="Enter fullname" required>

            <label for="contact" class="block text-sm font-medium text-gray-700">Contact no.</label>
            <input 
                type="tel" 
                id="contact" 
                name="contact"
                class="mt-1 p-2 w-full border rounded-md input validator tabular-nums" 
                placeholder="Enter contact number" 
                pattern="[0-9]{11}" 
                maxlength="11"
                required
            >
            <p class="validator-hint">Must be 11 digits [0-9]{11}</p>

            <label for="address" class="block text-sm font-medium text-gray-700 mt-4">Address</label>
            <input type="text" id="address" name="address" class="mt-1 p-2 w-full border rounded-md" placeholder="Enter address" required>

            <label for="username" class="block text-sm font-medium text-gray-700 mt-4">Username</label>
            <input type="text" id="username" name="username" class="mt-1 p-2 w-full border rounded-md" placeholder="Enter username" required>
            <label for="email" class="block text-sm font-medium text-gray-700 mt-4">Email</label>
            <input type="email" id="email" name="email" class="mt-1 p-2 w-full border rounded-md" placeholder="Enter Email" required>
            <div class="flex justify-center" id="errorMessage">
                <p class="text-red-600 text-sm" id="messageError"></p>
            </div>

            <input type="text" id="purpose" name="purpose" value="Security Personel" class="hidden">

            <div class="mt-6 flex justify-end space-x-4">
                <p id="hideModal" class="bg-gray-400 text-white px-4 py-2 rounded-md cursor-pointer">Cancel</p>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Submit</button>
            </div>
        </form>
    </div>
</div>




<!-- Open the modal using ID.showModal() method -->

<dialog id="my_modal_2" class="modal">
  <div class="modal-box">
    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <h3 class="text-lg font-bold">Account Created</h3>
    <p class="py-4">Please copy this Password</p>

    <div class="my-1 p-2 text-center gap-1">
        <div class="flex justify-center gap-3 items-center">
            <p class="text-sm text-gray-600 copy-text">default123</p>
            <button class="copy-btn bg-blue-100 text-blue-600 text-sm">Copy</button>
        </div>
    </div>

  </div>
</dialog>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 z-50 hidden">
    <div id="toastMessage" class="bg-green-500 text-white px-4 py-2 rounded shadow-md">
        Success message here
    </div>
</div>

<!-- Fullscreen Loading Overlay -->
<!-- <div id="loader" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-50 hidden">
  <div class="loading loading-bars loading-lg text-yellow-600"></div>
</div> -->

<script>

$(document).on('click', '#deleteBtn', function() {
    let id = $("#editVehicleId").val();

    if (!id) {
        showToast("No account selected for deletion.", "error");
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "This account will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../database/delete_guard.php",
                type: "POST",
                data: { id: id },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The account has been removed.',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $("#profile").addClass("hidden");
                        $("#hideTable").removeClass("hidden");
                        $('#btnBack').addClass('hidden');
                        $('#addVehicleBtn').removeClass('hidden');
                        loadAdminAccounts();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while deleting the account.'
                    });
                }
            });
        }
    });
});

// When Edit is clicked
$(document).on('click', '.showProfile', function() {
    let adminId = $(this).data('id');

    $.ajax({
        url: "../database/edit_guard.php", // PHP that fetches the admin data
        type: "POST",
        data: { id: adminId },
        dataType: "json",
        success: function(response) {
            if (response.status === 'success') {
                $("#editVehicleId").val(response.data.id);
                $("#fullname").val(response.data.full_name);
                $("#mail").val(response.data.email);
                $("#contact").val(response.data.contact_number);
                $("#address").val(response.data.address);
                $("#username").val(response.data.username);

                // Show profile form & hide table
                $("#profile").removeClass("hidden");
                $("#hideTable").addClass("hidden");
                $('#btnBack').removeClass('hidden');
                $('#addVehicleBtn').addClass('hidden');

                // Scroll to the form
                $('html, body').animate({
                    scrollTop: $("#profile").offset().top - 100
                }, 400);
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert("Error fetching admin data.");
        }
    });
});


$(document).on('submit', '#updateProfileForm', function(e) {
    e.preventDefault();

    $.ajax({
        url: "../database/update_guard.php", // PHP file to update DB
        type: "POST",
        data: {
            id: $("#editVehicleId").val(),
            fullname: $("#fullname").val(),
            contact: $("#contact").val(),
            address: $("#address").val(),
            username: $("#username").val(),
            email: $("#mail").val()   // ✅ FIXED
        },
        dataType: "json",
        success: function(response) {
            if (response.status === 'success') {
                showToast("Profile updated successfully!", "success");

                $("#profile").addClass("hidden");
                $("#hideTable").removeClass("hidden");
                $('#btnBack').addClass('hidden');
                $('#addVehicleBtn').removeClass('hidden');

                loadAdminAccounts();
            } else {
                $('#message').text(response.message);
                const errormessage = document.getElementById('message').innerText;

                if (errormessage !== '') {
                    setTimeout(() => {
                        $('#message').text('');
                    }, 3000);
                }
            }
        },
        error: function() {
            alert("Error updating admin profile.");
        }
    });
});


function showToast(message, type = "success") {
    let toast = $("#toast");
    let toastMessage = $("#toastMessage");

    if (type === "success") {
        toastMessage.removeClass().addClass("bg-green-500 text-white px-4 py-2 rounded shadow-md");
    } else if (type === "error") {
        toastMessage.removeClass().addClass("bg-red-500 text-white px-4 py-2 rounded shadow-md");
    }

    toastMessage.text(message);
    toast.removeClass("hidden");

    setTimeout(() => {
        toast.addClass("hidden");
    }, 3000);
}


    const copyButtons = document.querySelectorAll('.copy-btn');    

    copyButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const textToCopy = document.querySelectorAll('.copy-text')[index].textContent;

            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                setTimeout(() => {
                    button.textContent = originalText;
                }, 1500);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        });
    });

    $('#addVehicleBtn').on('click', function () {
        $('#addModal').removeClass('hidden');
    });
    $('#hideModal').on('click', function (){
        $('#addModal').addClass('hidden');
    });

    $(document).ready(function () {
        $('#createAccountForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '../backend/create_account_guard.php',
                type: 'POST',
                data: $(this).serialize(), // Now works because we added name=""
                success: function (response) {
                    let res;
                    try {
                        res = JSON.parse(response);
                    } catch (e) {
                        toastr.error('Invalid server response.');
                        return;
                    }

                    if (res.status === 'success') {
                        $('#username-copy').text($('#username').val()); // Set copied username

                        if (typeof my_modal_2 !== 'undefined') {
                            my_modal_2.showModal();
                        }
                        
                        Toastify({
                            text: res.message,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#28a745",
                            stopOnFocus: true
                        }).showToast();

                        $('#createAccountForm')[0].reset();
                        $('#addModal').addClass('hidden');
                    } else {
                        $('#messageError').text(res.message);

                        const errormessage = document.getElementById('messageError').innerText;

                        if (errormessage !== '') {
                            setTimeout(() => {
                                $('#messageError').text('');
                            }, 3000);
                        }

                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    toastr.error('AJAX request failed. Please check your connection or server.');
                }
            });
        });

        // Cancel button to hide modal
        $('#hideModal').on('click', function () {
            $('#addModal').addClass('hidden');
        });
    });


    function loadAdminAccounts() {
        $.ajax({
            url: '../backend/fetch_guard.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let rows = '';
                

                if (data.length === 0) {
                    rows = `<tr><td colspan="5" class="text-center py-4 text-gray-400">No Guard accounts found.</td></tr>`;
                } else {
                    data.forEach(row => {
                        const formattedDate = new Date(row.created_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        rows += `
                            <tr class="text-center border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-2 py-1.5 truncate max-w-[80px]">${row.full_name}</td>
                                <td class="px-2 py-1.5">${row.contact_number}</td>
                                <td class="px-2 py-1.5">${row.address}</td>
                                <td class="px-2 py-1.5">${row.email}</td>
                                <td class="px-2 py-1.5">${row.username}</td>
                                <td class="px-2 py-1.5 text-blue-500">${formattedDate}</td>
                                <td class="px-2 py-1.5">
                                    <div class="flex justify-center space-x-2">
                                        <button class="showProfile bg-blue-100 text-blue-600 rounded p-1.5" data-id="${row.id}">View</button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }

                $('#accountTableBody').html(rows);
            },
            error: function(xhr, status, error) {
                console.error(error);
                $('#accountTableBody').html('<tr><td colspan="5" class="text-center py-4 text-red-500">Error fetching data.</td></tr>');
            }
        });
    }

        // Call it when the page loads
    $(document).ready(function() {
        loadAdminAccounts(); // initial load

        // Refresh every 3 seconds for realtime updates
        setInterval(loadAdminAccounts, 1000);
    });
</script>
    <!-- Main modal -->
</body>
</html>