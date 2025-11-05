<!-- profile.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
    <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>
<body>
  <div class="max-w-6xl mx-auto space-y-8">

    <!-- Profile Info -->
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
      <profile class="text-sm text-gray-600 mb-4">Only admin can update your name and username</p>
      <br>
      <form action="update_profile.php" method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
          <input type="text" name="name" value=" <?php echo $_SESSION['full_name'];?>" class="w-full px-4 py-2 border rounded-md focus:outline-none bg-gray-100" readonly>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
          <input type="email" name="email" value="<?php echo $_SESSION['username'];?>" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:outline-none bg-gray-100" readonly>
        </div>
      </form>
    </div>

    <!-- Update Password -->
<div class="bg-white shadow rounded-lg p-6 mb-4">
  <h2 class="text-xl font-semibold text-gray-900">Update Password</h2>
  <p class="text-sm text-gray-600 mb-4">Ensure your account is using a long, random password to stay secure.</p>
  
  <form id="form" method="POST" class="space-y-4">

    <!-- Current Password -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
      <div class="relative">
        <input type="password" name="current_password" id="current_password"
          class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-200 pr-10" required>
        <button type="button" id="toggleCurrentPass" 
          class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
          <svg id="eyeCurrent" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 
                  9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
        </button>
      </div>
    </div>

    <!-- New Password -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
      <div class="relative">
        <input type="password" name="new_password" id="new_pass"
          class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-200 pr-10" required>
        <button type="button" id="toggleNewPass"
          class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
          <svg id="eyeNew" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 
                  9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Confirm Password -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
      <div class="relative">
        <input type="password" name="confirm_password" id="confirm_password"
          class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-200 pr-10" required>
        <button type="button" id="toggleConfirmPass"
          class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
          <svg id="eyeConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 
                  9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
        </button>
      </div>

      <div class="my-2">
        <div class="text-red-500 text-sm" id="errorM"></div>
        <div class="text-green-500 text-sm" id="successMsg"></div>
      </div>
    </div>

    <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
      Save Changes
    </button>
  </form>
</div>
    
  <!-- SUCCESS MODAL -->
  <div id="successModal" class="hidden fixed inset-0 flex items-center justify-center z-50 w-full">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm transform transition-all scale-95">
      <div class="flex flex-col items-center text-center">
        <!-- Success Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 13l4 4L19 7" />
        </svg>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Password Updated!</h3>
        <p class="text-gray-600 text-sm mb-5">Your password has been changed successfully.</p>
        <button id="closeModal"
          class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
          OK
        </button>
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function () {

  // === Toggle Password Visibility ===
  function togglePassword(inputId, buttonId) {
    const input = $(inputId);
    const icon = $(buttonId + " svg");
    $(buttonId).on('click', function () {
      const isPassword = input.attr('type') === 'password';
      input.attr('type', isPassword ? 'text' : 'password');
      icon.toggleClass('text-indigo-600');
    });
  }

  togglePassword('#current_password', '#toggleCurrentPass');
  togglePassword('#new_pass', '#toggleNewPass');
  togglePassword('#confirm_password', '#toggleConfirmPass');

  // === Submit Handler ===
  $('#form').on('submit', function (e) {
    e.preventDefault();

    $('#errorM').text('');
    $('#successMsg').text('');

    const current_password = $('#current_password').val().trim();
    const new_password = $('#new_pass').val().trim();
    const confirm_password = $('#confirm_password').val().trim();

    // Client-side validation
    if (!current_password || !new_password || !confirm_password) {
      $('#errorM').text('Please fill in all fields.');
      return;
    }
    if (new_password.length < 6) {
      $('#errorM').text('New password must be at least 6 characters long.');
      return;
    }
    if (new_password !== confirm_password) {
      $('#errorM').text('New password and confirm password do not match.');
      return;
    }
    if (new_password === current_password) {
      $('#errorM').text('New password cannot be the same as current password.');
      return;
    }

    // AJAX request
    $.ajax({
      type: 'POST',
      url: '../backend/update_password.php',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (response) {
        if (response.status === 'success') {
          // Show modal on success
          $('#successModal').removeClass('hidden').addClass('flex').hide().fadeIn(200);
          $('#form')[0].reset();
        } else {
          $('#errorM').text(response.message);
          setTimeout(() => { $('#errorM').text(''); }, 3000);
        }
      },
      error: function () {
        $('#errorM').text('Something went wrong. Please try again.');
      }
    });
  });

  // === Close Modal Button ===
  $('#closeModal').on('click', function () {
    $('#successModal').fadeOut(200, function () {
      $(this).addClass('hidden');
    });
  });
});
</script>

</body>
</html>
