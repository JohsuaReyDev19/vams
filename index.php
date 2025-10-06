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
        <li>Real-time Vehicle Tracking</li>
      </ul> 
    </div>

    <!-- RIGHT SIDE: Login Form -->
    <div class="w-full md:w-1/2 flex items-center justify-center bg-white px-6 py-10">
      <div class="w-full max-w-md px-6 md:px-10 py-8 md:py-12 shadow-lg rounded-xl">
        <h2 id="title1" class="text-2xl md:text-3xl font-bold text-yellow-800 mb-2 text-center">Secure Login</h2>
        <p id="title2" class="text-sm text-gray-500 mb-6 text-center">Access your monitoring dashboard</p>
        <form id="loginForm" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <input id="username" type="text" placeholder="Enter your username" class="mt-1 w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-yellow-700 focus:border-yellow-700" autofocus/>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <div class="relative">
                <input id="password" type="password" placeholder="Enter your password"
                    class="mt-1 w-full border border-gray-300 rounded-md px-4 py-2 pr-10 focus:ring-yellow-700 focus:border-yellow-700" />

                <!-- Toggle Button -->
                <button type="button" id="togglePassword" 
                    class="absolute mt-2 right-2 top-1/2 transform -translate-y-1/2 text-sm text-gray-500 focus:outline-none">
                    Show
                </button>
            </div>
          </div>
          <button type="submit" class="w-full py-2 text-white font-semibold rounded-md bg-yellow-700 hover:bg-yellow-800 transition duration-200">
            ACCESS DASHBOARD
          </button>
          
          <div id="attemp" class="text-center text-sm hidden">
            <a href="#" class="text-blue-500">Forgot Password</a>
          </div>
        </form>

        <div id="showFormForgot" class="bg-white rounded-xl p-8 w-full hidden">
          <h2 class="text-2xl font-bold text-yellow-800 mb-2 text-center">Forgot Password</h2>
          <p class="text-gray-600 text-sm mb-6 text-center">Enter your registered email to receive a reset code</p>

          <form id="forgotForm" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
              <input type="email" name="email" placeholder="Enter your email"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-600"
                required>
            </div>

            <button type="submit"
              class="w-full py-2 bg-yellow-700 text-white font-semibold rounded-lg shadow hover:bg-yellow-800 transition">
              Send Reset Code
            </button>
          </form>

          <p id="remember" class="mt-6 text-center text-sm text-gray-500 hidden">
            Remember your password?
            <a href="index.php" class="text-yellow-700 font-medium hover:underline">Back to Login</a>
          </p>
        </div>

        <div id="resetPasswordForm" class="bg-white rounded-xl p-8 w-full hidden">
            <h2 class="text-2xl font-bold text-yellow-800 mb-2 text-center">Reset Password</h2>
            <p class="text-gray-600 text-sm mb-6 text-center">Enter the code sent to your email and your new password</p>

            <form id="resetForm" class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" placeholder="Enter your email"
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-600" required>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reset Code</label>
                <input type="text" name="reset_code" placeholder="Enter the code"
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-600" required>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="new_password" placeholder="Enter new password"
                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-600" required>
              </div>

              <button type="submit"
                class="w-full py-2 bg-yellow-700 text-white font-semibold rounded-lg shadow hover:bg-yellow-800 transition">
                Reset Password
              </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
              Remember your password?
              <a href="index.php" class="text-yellow-700 font-medium hover:underline">Back to Login</a>
            </p>
          </div>
      </div>
    </div>

  </div>


  <!-- JS Logic -->
  <script>
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');
    let attemp = 0;

    function swowAttemp(){
        console.log(attemp);
      if(attemp >= 3){
        document.getElementById('attemp').classList.remove('hidden');
        Swal.fire({
          icon: "warning",
          title: "Too Many Attempts",
          html: "You have reached the maximum login attempts.<br>Please use <b>Forgot Password</b> to reset your account.",
          showCancelButton: true,
          confirmButtonText: "Forgot Password",
          cancelButtonText: "Close",
          confirmButtonColor: "#f59e0b", // yellow
          cancelButtonColor: "#6b7280"
        }).then((result) => {
          if (result.isConfirmed) {
            // Redirect to forgot password page
            // window.location.href = "./forgot_password.php";
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('showFormForgot').classList.remove('hidden');
            document.getElementById('remember').classList.remove('hidden');
            document.getElementById('title1').innerText = "";
            document.getElementById('title2').innerText = "";
          }
        });
      }
    }

    toggleButton.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        this.textContent = type === 'password' ? 'Show' : 'Hide';
    });

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
        setTimeout(() => window.location.href = data.redirect, 700);
      } else {
        Swal.fire({
          icon: "error",
          text: data.message,
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000
        });
        attemp +=1;
        swowAttemp();
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

  <script>
    document.getElementById("forgotForm").addEventListener("submit", function(e){
    e.preventDefault();

    const btn = this.querySelector("button[type='submit']");
    const originalText = btn.textContent;

    // Set loading state
    btn.textContent = "Sending...";
    btn.disabled = true;

    const formData = new FormData(this);

    fetch("./backend/forgot_password.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text()) // get text first
    .then(text => {
        try {
            const data = JSON.parse(text);

            Swal.fire({
                icon: data.success ? "success" : "error",
                text: data.message,
                confirmButtonColor: "#f59e0b"
            }).then(() => {
                if(data.success){
                    // Switch to reset password form after user clicks OK
                    document.getElementById('showFormForgot').classList.add('hidden');
                    document.getElementById('resetPasswordForm').classList.remove('hidden');
                }
            });

        } catch (err) {
            console.error("PHP Response:", text);
            Swal.fire({
                icon: "error",
                text: "Something went wrong (see console)",
                confirmButtonColor: "#f59e0b"
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: "error",
            text: "Fetch failed",
            confirmButtonColor: "#f59e0b"
        });
    })
    .finally(() => {
        // Reset button state
        btn.textContent = originalText;
        btn.disabled = false;
    });
});

    document.getElementById("resetForm").addEventListener("submit", function(e){
      e.preventDefault();
      const formData = new FormData(this);

      fetch("./backend/reset_password.php", {
          method: "POST",
          body: formData
      })
      .then(res => res.text())  // get text first
      .then(text => {
          try {
              const data = JSON.parse(text);
              Swal.fire({
                  icon: data.success ? "success" : "error",
                  text: data.message,
                  confirmButtonColor: "#f59e0b"
              }).then(() => {
                  if(data.success){
                      window.location.href = "index.php"; // redirect to login
                  }
              });
          } catch (err) {
              console.error("PHP Response:", text);
              Swal.fire({
                  icon: "error",
                  text: "Something went wrong (see console)",
                  confirmButtonColor: "#f59e0b"
              });
          }
      })
      .catch(err => {
          console.error(err);
          Swal.fire({
              icon: "error",
              text: "Fetch failed",
              confirmButtonColor: "#f59e0b"
          });
      });
  });
  </script>

</body>
</html>