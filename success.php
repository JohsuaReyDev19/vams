<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration Successful</title>
  <meta name="description" content="Thank you for pre-registering! Your registration was successful." />
  <meta name="author" content="Lovable" />

  <meta property="og:title" content="Registration Successful" />
  <meta property="og:description" content="Thank you for pre-registering! Your registration was successful." />
  <meta property="og:type" content="website" />
  <meta property="og:image" content="https://lovable.dev/opengraph-image-p98pqg.png" />

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="@lovable_dev" />
  <meta name="twitter:image" content="https://lovable.dev/opengraph-image-p98pqg.png" />
  
  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Confetti Library -->
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
  
  <style>
    /* Additional custom styles beyond Tailwind */
    body {
      background: linear-gradient(to bottom, white, #f9fafb);
      min-height: 100vh;
    }
    .social-button:hover {
      transform: scale(1.05);
      transition: transform 0.2s ease;
    }
  </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center px-4 py-12">
  <div class="w-full max-w-lg p-8 bg-white shadow-lg rounded-xl border-0">
    <div class="flex flex-col items-center text-center">
      <div class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center mb-6">
        <!-- Check icon -->
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
      </div>
      
      <h1 class="text-3xl font-bold text-gray-800 mb-3">Pre-Registration Successful!</h1>
      <p class="text-gray-600 mb-6">
        Thank you for pre-registering with <span class="text-green-600 font-medium" id="email-display">Web-base RFID Vehicle Access Monitoring System</span>
      </p>
      
      <div class="w-full h-0.5 bg-gray-100 my-6"></div>
      
      <div class="space-y-6 w-full">
        <div class="bg-green-50 p-4 rounded-lg">
          <h3 class="font-medium text-gray-800 mb-2">What happens next?</h3>
          <ul class="text-sm text-gray-600 space-y-2">
            <li class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 mr-2 mt-0.5">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>Please wait for your Approval in 24-hours</span>
            </li>
            <li class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 mr-2 mt-0.5">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>Admin review your details</span>
            </li>
            <li class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 mr-2 mt-0.5">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span>Successful Approved</span>
            </li>
          </ul>
        </div>
      </div>
      
      <div class="w-full h-0.5 bg-gray-100 my-6"></div>
      
      <a href="./index.php" class="bg-green-600 hover:bg-green-700 text-white w-full flex items-center justify-center gap-2 py-4 px-4 rounded-md">
        Return to Homepage
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12"></line>
          <polyline points="12 5 19 12 12 19"></polyline>
        </svg>
      </a>
    </div>
  </div>
  
  <p class="text-gray-500 text-sm mt-8">
    Questions? Contact us at <a href="mailto:prmsucandelaria04@gmail.com" class="text-green-600 hover:underline">prmsucandelaria04@gmail.com</a>
  </p>
  
  <script>
    // Parse URL for email parameter
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const emailParam = urlParams.get('email');
      if (emailParam) {
        document.getElementById('email-display').textContent = emailParam;
      }
      
      // Trigger confetti on page load
      confetti({
        particleCount: 200,
        spread: 70,
        origin: { y: 0.6 },
        colors: ['#10B981', '#3B82F6', '#8B5CF6', '#F472B6', '#F59E0B'],
        disableForReducedMotion: true
      });
      
      // Stop confetti after 4 seconds
      setTimeout(() => {
        confetti.reset();
      }, 4000);
    });
  </script>
</body>
</html>