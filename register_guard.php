<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: ./admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>RFID Access | Secure Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen w-screen m-0 p-0 relative overflow-y-auto bg-white">

  <!-- Loader -->
  <!-- <div id="loader" class="fixed inset-0 flex items-center justify-center bg-gray-400 bg-opacity-50 z-50">
    <div class="loading loading-bars loading-lg text-yellow-600"></div>
  </div> -->

  <div class="flex flex-col md:flex-row min-h-screen w-full">

    <!-- LEFT SIDE: About -->
    <div class="w-full md:w-1/2 bg-yellow-100 flex flex-col justify-center items-center px-6 md:px-12 py-10">
      <div class="w-40 h-auto mb-6">
        <img src="prmsu.png" alt="Security Illustration" class="w-full h-auto">
      </div>
      <h1 class="text-3xl md:text-4xl font-bold text-yellow-800 mb-2 text-center">P R M S U</h1>
      <p class="text-2xl text-gray-700 mb-4 text-center">RFID Sticker Vehicle Access Monitoring System</p>
      <ul class="text-left space-y-3 text-gray-800 font-medium text-base md:text-lg">
        <li>✅ Real-time Vehicle Tracking</li>
      </ul>
    </div>

    <!-- RIGHT SIDE: Login Form -->
    <div class="w-full md:w-1/2 flex items-center justify-center bg-white px-6 py-10">
      <div class="bg-white shadow-md rounded-lg w-full max-w-md p-8">
        <h2 class="text-2xl font-bold text-yellow-800 mb-2 text-center">Register Guard Account</h2>
        <p class="text-sm text-gray-500 mb-6 text-center">Fill in the details to create a new admin</p>
        <form id="registerForm" class="space-y-4">
          <input type="text" id="full_name" placeholder="Full Name" class="w-full border rounded-md px-3 py-2" />
          <input type="number" id="contact_number" min='11' placeholder="Contact Number" class="w-full border rounded-md px-3 py-2" />
          <input type="text" id="address" placeholder="Address" class="w-full border rounded-md px-3 py-2" />
          <input type="email" id="email" placeholder="Email" class="w-full border rounded-md px-3 py-2" />
          <input type="text" id="role" value="Security Personel" class="w-full border rounded-md px-3 py-2 hidden" />
          <input type="text" id="username" placeholder="Username" class="w-full border rounded-md px-3 py-2" />
          <input type="password" id="password" placeholder="Password" class="w-full border rounded-md px-3 py-2" />
          <button type="submit" class="w-full py-2 bg-yellow-700 text-white font-semibold rounded-md hover:bg-yellow-800 transition">REGISTER</button>
        </form>
      </div>
    </div>

  </div>

  <!-- JS Logic -->
<script>
    document.getElementById('registerForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value.trim();
      const fullName = document.getElementById('full_name').value.trim();
      const contact = document.getElementById('contact_number').value.trim();
      const address = document.getElementById('address').value.trim();
      const email = document.getElementById('email').value.trim();
      const role = document.getElementById('role').value;

      if (!username || !password || !role) {
        Swal.fire({
          icon: "warning",
          text: "Username, password, and role are required.",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000
        });
        return;
      }

      fetch('./admin/database/register_guard.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&full_name=${encodeURIComponent(fullName)}&contact_number=${encodeURIComponent(contact)}&address=${encodeURIComponent(address)}&email=${encodeURIComponent(email)}&role=${encodeURIComponent(role)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "Registered Successfully!",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500
          });
          setTimeout(() => window.location.href = "../wbrv-prmsu", 1500);
        } else {
          Swal.fire({
            icon: "error",
            text: data.message,
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000
          });
        }
      });
    });
  </script>

</body>
</html>
