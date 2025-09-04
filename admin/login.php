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
  <div id="loader" class="fixed inset-0 flex items-center justify-center bg-gray-400 bg-opacity-50 z-50">
    <div class="loading loading-bars loading-lg text-yellow-600"></div>
  </div>

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
      <div class="w-full max-w-md px-6 md:px-10 py-8 md:py-12 shadow-lg rounded-xl">
        <h2 class="text-2xl md:text-3xl font-bold text-yellow-800 mb-2 text-center">Secure Login</h2>
        <p class="text-sm text-gray-500 mb-6 text-center">Access your monitoring dashboard</p>
        <form id="loginForm" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <input id="username" type="text" placeholder="Enter your username" class="mt-1 w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-yellow-700 focus:border-yellow-700" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" type="password" placeholder="Enter your password" class="mt-1 w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-yellow-700 focus:border-yellow-700" />
          </div>
          <button type="submit" class="w-full py-2 text-white font-semibold rounded-md bg-yellow-700 hover:bg-yellow-800 transition duration-200">
            ACCESS DASHBOARD
          </button>
        </form>
      </div>
    </div>

  </div>

  <!-- JS Logic -->
  <script>
    const loader = document.getElementById('loader');

    // Initially hide the loader using JS
    loader.style.display = 'none';

    document.getElementById('loginForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value.trim();

      if (!username || !password) {
        Swal.fire({
          icon: "warning",
          text: "Please enter both username and password.",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        });
        return;
      }

      loader.style.display = 'flex'; // Show loader

      fetch("./database/login.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
      })
      .then(response => response.json())
      .then(data => {
        loader.style.display = 'none'; // Hide loader
        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "Login Successful!",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 700
          });
          setTimeout(() => window.location.href = "./admin.php", 700);
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
      })
      .catch(() => {
        loader.style.display = 'none'; // Hide loader
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Something went wrong.",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000
        });
      });
    });
  </script>

</body>
</html>
