<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.5s ease-out forwards;
        }
        @keyframes fadeIn {
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
<body class="flex items-center justify-center min-h-screen bg-blue-100 p-4">
    <div class="w-full max-w-md  p-8 space-y-6 bg-white border rounded-2xl shadow-md fade-in">
        <div class="flex flex-col items-center">
            <div class="mb-4">
                <img src="./prmsu.png" alt="Logo" class="h-20">
            </div>
            <h2 class="text-2xl font-bold text-yellow-800">Create account</h2>
            <p class="text-sm text-gray-500">Enter your credentials to access Vehicles</p>
        </div>
        <form id="registerForm">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full name</label>
                    <input type="text" name="fullname" id="fullname" placeholder="Enter your full name" class="w-full px-4 py-2 mt-1 border rounded-md focus:ring-black focus:border-black">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter username" class="w-full px-4 py-2 mt-1 border rounded-md focus:ring-black focus:border-black">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input  type="email" name="email" id="email" autocomplete="email" placeholder="Enter your email" class="w-full px-4 py-2 mt-1 border rounded-md focus:ring-black focus:border-black">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Create password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" placeholder="Enter your password" class="w-full px-4 py-2 mt-1 border rounded-md focus:ring-black focus:border-black">
                        <span id="togglePassword" class="absolute inset-y-0 right-3 flex items-center text-sm text-gray-500 cursor-pointer">Show</span>
                    </div>
                </div>
                <button type="submit" class="w-full px-4 py-2 font-semibold text-white bg-yellow-700 rounded-md">Create Account</button>
            </div>
        </form>
        <p id="responseMessage" class="text-center text-sm mt-2"></p>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                this.textContent = 'Hide';
            } else {
                passwordField.type = 'password';
                this.textContent = 'Show';
            }
        });
    </script>
    <script>
    $(document).ready(function() {
        $("#registerForm").on("submit", function(event) {
            event.preventDefault(); // Prevent form from refreshing page

            $.ajax({
                url: "./database/register.php", // The PHP file that will handle the request
                type: "POST",
                data: $(this).serialize(), // Serialize form data
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#responseMessage").html('<p class="text-green-600">' + response.message + '</p>');
                        $("#registerForm")[0].reset();
                    } else {
                        $("#responseMessage").html('<p class="text-red-600">' + response.message + '</p>');
                    }
                },
                error: function() {
                    $("#responseMessage").html('<p class="text-red-600">Something went wrong. Please try again.</p>');
                }
            });
        });
    });
</script>
</body>
</html>
