<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>RFID Scan - Vehicle Access</title>
  <style>
    body {
      background: #f8fafc;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .scanner-circle {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      background: #ffffff;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
      border: 1px solid #e2e8f0;
      position: relative;
      overflow: hidden;
    }

    .scan-line {
      position: absolute;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(to right, transparent, #0ea5e9, transparent);
      animation: scanDown 2.2s ease-in-out infinite;
      opacity: 0.9;
    }

    @keyframes scanDown {
      0%   { top: 0; opacity: 0; }
      10%  { opacity: 1; }
      50%  { top: 100%; opacity: 1; }
      90%  { opacity: 1; }
      100% { top: 0; opacity: 0; }
    }

    .glass-glow {
      position: absolute;
      inset: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(6px);
      pointer-events: none;
      z-index: 1;
    }

    .center-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #1e293b;
      font-size: 14px;
      z-index: 2;
      font-weight: 500;
      text-align: center;
      padding: 0 10px;
    }

    .status-success {
      color: #22c55e;
    }

    .status-error {
      color: #ef4444;
    }
  </style>
</head>
<body>
  <div class="scanner-circle">
    <div class="scan-line" id="scanLine"></div>
    <div class="glass-glow"></div>
    <div class="center-text" id="scanMessage">Waiting for RFID...</div>
  </div>

  <script>
    const scanMessage = document.getElementById('scanMessage');
    const scanLine = document.getElementById('scanLine');

    const messages = [
      { text: "Waiting for RFID...", class: "" },
      { text: "Scanning in progress...", class: "" },
      { text: "Access granted ✅", class: "status-success" },
      { text: "Access denied ❌", class: "status-error" },
      { text: "Please rescan RFID tag.", class: "" }
    ];

    let index = 0;

    function cycleMessages() {
      const msg = messages[index];
      scanMessage.textContent = msg.text;
      scanMessage.className = 'center-text'; // Reset class
      if (msg.class) scanMessage.classList.add(msg.class);
      index = (index + 1) % messages.length;
    }

    // Simulate changing messages every 3 seconds
    setInterval(cycleMessages, 3000);
  </script>
</body>
</html>
