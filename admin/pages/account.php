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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Vehicles</title>
    <style>
        #ownerList {
            scrollbar-width: none; /* Hide scrollbar for Firefox */
            -ms-overflow-style: none; /* Hide scrollbar for IE and Edge */
        }

        #ownerList::-webkit-scrollbar {
            display: none; /* Hide scrollbar for Chrome, Safari, and Edge */
        }
        body{
            background-color:#f4faf9;
            font-style: inherit;
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
    <div class="w-full m-auto bg-white rounded-lg p-6 mt-8">
        <div class="w-full p-4 border-bss">
            <div>
                <h2 class="text-2xl font-semibold">Settings</h2>
            </div>
            <!-- Responsive Buttons -->
            <div class="flex flex-wrap justify-start sm:justify-normal mt-4 gap-2 sm:gap-4">
                <button id="importbtn" class="flex items-center gap-1 px-3 py-2 font-semibold text-gray-500 hover:text-blue-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-import-icon lucide-import"><path d="M12 3v12"/><path d="m8 11 4 4 4-4"/><path d="M8 5H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-4"/></svg>
                    Import data</button>
                
                <button id="profilebtn" class="flex items-center gap-1 px-3 py-2 font-semibold text-gray-500 hover:text-blue-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-cog"><path d="M2 21a8 8 0 0 1 10.434-7.62"/><circle cx="10" cy="8" r="5"/><circle cx="18" cy="18" r="3"/><path d="m19.5 14.3-.4.9"/><path d="m16.9 20.8-.4.9"/><path d="m21.7 19.5-.9-.4"/><path d="m15.2 16.9-.9-.4"/><path d="m21.7 16.5-.9.4"/><path d="m15.2 19.1-.9.4"/><path d="m19.5 21.7-.4-.9"/><path d="m16.9 15.2-.4-.9"/></svg>    
                    My Profile</button>
                <button id="securitybtn" class="flex items-center gap-1 px-3 py-2 font-semibold text-gray-500 hover:text-blue-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>    
                    Security</button>
                <button id="databasebtn" class="flex items-center gap-1 px-3 py-2 font-semibold text-gray-500 hover:text-blue-500 transition" title="backup your database now">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database-backup"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 12a9 3 0 0 0 5 2.69"/><path d="M21 9.3V5"/><path d="M3 5v14a9 3 0 0 0 6.47 2.88"/><path d="M12 12v4h4"/><path d="M13 20a5 5 0 0 0 9-3 4.5 4.5 0 0 0-4.5-4.5c-1.33 0-2.54.54-3.41 1.41L12 16"/></svg>
                    Backup Database</button>
                    <button id="importDatabaseBtn" class="flex items-center gap-1 px-3 py-2 font-semibold text-gray-500 hover:text-green-500 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database-import">
                            <path d="M12 3v12"/><path d="m8 11 4 4 4-4"/><ellipse cx="12" cy="5" rx="9" ry="3"/>
                            <path d="M3 12a9 3 0 0 0 5 2.69"/><path d="M3 5v14a9 3 0 0 0 6.47 2.88"/>
                        </svg>
                        Import Database
                    </button>

                    <!-- Hidden file upload form -->
                    <form id="importForm" action="../backend/import.php" method="POST" enctype="multipart/form-data" class="hidden">
                        <input type="file" name="sqlfile" id="sqlfile" accept=".sql" required>
                    </form>
            </div>
        </div>
        <hr>
        <div class="w-full p-4 mt-8">
            <div id="showImport" class="fade-in">
                <?php 
                    include './student_list.php';
                ?>
            </div>
            <!-- profile settings -->
            <div id="profileContent" class="fade-in hidden">
                <div>
                    <h2 class="text-xl font-semibold">My Profile</h2>
                    <p class="text-gray-500">Manage your personal information</p>
                </div>

                <div class="w-full flex flex-col md:flex-row gap-6 mt-8">
                    <!-- Profile Image Section -->
                    <div class="flex flex-col items-center md:items-start m-4">
                        <div class="relative text-center">
                            <img class="w-36 rounded-full border" src="../prmsu.png" alt="Profile Picture">
                        </div>
                        <div class="text-center mt-4 w-full">
                            <h2 class="text-xl font-semibold">Admin User</h2>
                            <p class="text-gray-500">Administrator</p>
                        </div>
                    </div>

                    <!-- Profile Information Section -->
                    <div class="w-full">
                    <form id="profileUpdate">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user text-gray-500"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        <label class="block">Full Name</label>
                                    </div>
                                    <input id="edit" type="text" name="fullname" class="w-full p-2 border rounded-lg" disabled>
                                </div>
                                <div>
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        <label class="block">Email Address</label>
                                    </div>
                                    <input id="edit2" type="email" name="email" class="w-full p-2 border rounded-lg" disabled>
                                </div>
                                <div>
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-check"><path d="M2 21a8 8 0 0 1 13.292-6"/><circle cx="10" cy="8" r="5"/><path d="m16 19 2 2 4-4"/></svg>
                                        <label class="block">Username</label>
                                    </div>
                                    <input id="edit3" type="text" name="username" class="w-full p-2 border rounded-lg" disabled>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 text-right mt-8">
                                <a href="account.php" id="cancel-btn" class="hidden flex items-center gap-1 px-4 py-2 bg-gray-400 text-white rounded-lg">Cancel</a>
                                <button type="submit" id="edit-btn" class="flex items-center gap-1 px-4 py-2 bg-blue-500 text-white rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-line"><path d="M12 20h9"/><path d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z"/><path d="m15 5 3 3"/></svg>    
                                    <p id="text">Edit Profile</p>
                                </button>
                                <button type="submit" id="save" class="hidden flex items-center gap-1 px-4 py-2 bg-green-500 text-white rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-line"><path d="M12 20h9"/><path d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z"/><path d="m15 5 3 3"/></svg>    
                                    Save Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- security settings -->

            <div id="securityContent" class="hidden fade-in">
                <div>
                    <h2 class="text-xl font-semibold">Change Password</h2>
                    <p class="text-gray-500">Update your password to maintain security</p>
                </div>

                <div class="w-full mt-6">
                    <form class="updatePassword">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Current Password -->
                            <div>
                                <div class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole"><circle cx="12" cy="16" r="1"/><rect x="3" y="10" width="18" height="12" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg>
                                    <label class="block">Current Password</label>
                                </div>
                                <input type="password" name="currentpassword" class="w-full p-2 border rounded-lg">
                            </div>
                            <div></div>
                            <!-- New Password -->
                            <div>
                                <div class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                                    <label class="block">New Password</label>
                                </div>
                                <input type="password" name="newpassword" class="w-full p-2 border rounded-lg">
                            </div>
                            <!-- Confirm Password -->
                            <div>
                                <div class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                                    <label class="block">Confirm Password</label>
                                </div>
                                <input type="password" name="confirmpassword" class="w-full p-2 border rounded-lg">
                            </div>
                        </div>

                        <div class="flex justify-end text-right mt-6">
                            <button type="submit" class="flex items-center gap-1 px-4 py-2 bg-blue-500 text-white rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole"><circle cx="12" cy="16" r="1"/><rect x="3" y="10" width="18" height="12" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg>    
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </div>
  </div>
  <script>

$(document).ready(function () {
    $("#databasebtn").on("click", function (e) {
        e.preventDefault();

        Swal.fire({
            title: "Are you sure?",
            text: "Generate your database backup!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, backup it!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Preparing Backup...",
                    text: "Please wait while we generate your database backup.",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading(); // 🔄 show spinner
                    },
                    timer: 3000 // 3 seconds loading
                }).then(() => {
                    // Start backup download
                    window.location.href = "../backend/backup.php";

                    // Show success after starting download
                    Swal.fire({
                        icon: "success",
                        title: "Backup Complete!",
                        text: "Your database backup has been generated successfully.",
                        timer: 2500,
                        showConfirmButton: false
                    });
                });
            }
        });
    });
});




$(document).ready(function () {
    $("#importDatabaseBtn").on("click", function () {
        $("#sqlfile").click(); // open file selector
    });

    $("#sqlfile").on("change", function () {
        let formData = new FormData($("#importForm")[0]);

        Swal.fire({
            title: "Are you sure?",
            text: "This will overwrite your current database with the uploaded backup!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, import it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading modal (3–5 sec)
                Swal.fire({
                    title: "Importing...",
                    text: "Please wait while we restore your database.",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    timer: 5000 // ⏳ 5 seconds (you can change to 3000 for 3 sec)
                });

                // Send AJAX after short delay
                setTimeout(() => {
                    $.ajax({
                        url: "../backend/import.php",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            Swal.fire({
                                icon: "success",
                                title: "Success!",
                                text: response,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // refresh page
                            });
                        },
                        error: function (xhr, status, error) {
                            Swal.fire("Error!", "Import failed: " + error, "error");
                        }
                    });
                }, 3000); // wait 3 seconds before sending AJAX
            }
        });
    });
});


</script>

  <script>
$(document).ready(function() {
    // Show/Hide Sections
    $('#importbtn').click(function() {
        $("#securityContent").addClass("hidden").addClass("fade-in");
        $("#profileContent").addClass("hidden");
        $("#showImport").removeClass("hidden");
    });

    $('#securitybtn').click(function() {
        $("#securityContent").removeClass("hidden").addClass("fade-in");
        $("#profileContent").addClass("hidden");
        $("#showImport").addClass("hidden");
    });

    $('#profilebtn').click(function() {
        $("#securityContent").addClass("hidden");
        $("#profileContent").removeClass("hidden").addClass("fade-in");
        $("#showImport").addClass("hidden");
    });

    // Fetch Admin Data
    $.ajax({
        url: "../database/fetch_admin.php",
        type: "GET",
        dataType: "json",
        success: function(response) {
            if (response.success) {
                $("input[name='fullname']").val(response.data.full_name);
                $("input[name='username']").val(response.data.username);
                $("input[name='email']").val(response.data.email);
            } else {
                console.error("Error: " + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });

    // Enable Edit Mode
    $("#edit-btn").click(function(e) {
        e.preventDefault();
        $("input").prop("disabled", false);
        $('#edit, #edit2, #edit3').addClass("border-blue-500");
        $('#cancel-btn').removeClass("hidden");
        $('#save').removeClass("hidden");
        $('#edit-btn').addClass("hidden");
        $('#text').text("Save");
    });

});
</script>
<script>
    $(".updatePassword").on("submit", function(e) {
    e.preventDefault();

    let currentPassword = $("input[name='currentpassword']").val().trim();
    let newPassword = $("input[name='newpassword']").val().trim();
    let confirmPassword = $("input[name='confirmpassword']").val().trim();

    // Check if the passwords match
    if (newPassword !== confirmPassword) {
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "New passwords do not match!"
        });
        return;
    }

    // Send the AJAX request
    $.ajax({
        url: "../database/update_pass.php", // Ensure this path is correct
        type: "POST",
        data: { currentPassword, newPassword },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // Refresh the page after success
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            Swal.fire({
                icon: "error",
                title: "AJAX Error",
                text: "Something went wrong!"
            });
        }
    });
});


</script>

<script>
    $(document).ready(function() {
    $("#profileUpdate").on("submit", function(e) {
        e.preventDefault(); // Prevent default form submission

        let updatedData = {
            fullname: $("input[name='fullname']").val().trim(),
            email: $("input[name='email']").val().trim(),
            username: $("input[name='username']").val().trim()
        };

        $.ajax({
            url: "../database/updateAdmin.php", // ✅ Make sure this file handles updates
            type: "POST",
            data: updatedData,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: "Profile updated successfully!",
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // ✅ Refresh after successful update
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                Swal.fire({
                    icon: "error",
                    title: "AJAX Error",
                    text: "Something went wrong!"
                });
            }
        });
    });
});
</script>
</body>
</html>