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
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
          <input type="password" name="current_password" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-200" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
          <input type="text" name="new_password" id="new_pass" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-200" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <input type="password" name="confirm_password" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-200" required>
          <div class="text-red-500 text-sm" id="errorMsg"></div>
          <div class="text-green-500 text-sm mx-2" id="successMsg"></div>
        </div>
        <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">Save Changes</button>
      </form>
    </div>
    
  </div>

<script>
  $(document).ready(function () {

    // const id = document.getElementById('guard_id').value;

  $('#form').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: '../backend/update_password.php',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (response) {
        if (response.status === 'success') {
          // toastr.success(response.message);
          $('#successMsg').text(response.message);
          if(response.message){
            setTimeout(() => {
                $('#successMsg').text('');
            }, 4000);
          }
          // Clear all password fields manually
          $('input[type="password"]').val('');
          $('#new_pass').val('');
          // Also clear hidden inputs if needed:
          // $('input[type="hidden"]').val('');

        } else {
          // toastr.error(response.message);
          $('#errorMsg').text(response.message);
          if(response.message){
            setTimeout(() => {
                $('#errorMsg').text('');
            }, 2000);
          }
        }
      },
      error: function () {
        toastr.error('Something went wrong. Please try again.');
      }
    });
  });
});

</script>

</body>
</html>
