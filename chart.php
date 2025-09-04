<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Weekly Access Summary</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

  <div class="max-w-6xl mx-auto bg-white rounded-lg border border-gray-200 shadow-sm">
    <div class="p-6 border-b border-gray-200">
      <h3 class="font-medium text-xl">Weekly Access Summary</h3>
      <p class="text-sm text-gray-500">Vehicle access patterns over the past week</p>
    </div>
    <div class="p-6 h-[400px] flex items-center justify-center">
      <div class="w-full h-full">
        <canvas id="weeklyAccessChart" class="w-full h-full"></canvas>
      </div>
    </div>
  </div>

  <script>
    async function loadChartData() {
      const response = await fetch('get_daily_logs.php');
      const data = await response.json();

      // Convert YYYY-MM-DD to Day Names
      const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
      const chartLabels = data.map(d => {
        const dayIndex = new Date(d.log_date).getDay();
        return days[dayIndex];
      });

      const entries = data.map(d => parseInt(d.entries));
      const exits = data.map(d => parseInt(d.exits));

      const ctx = document.getElementById('weeklyAccessChart').getContext('2d');

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: chartLabels,
          datasets: [
            {
              label: 'Entries',
              data: entries,
              backgroundColor: '#2563eb' // Tailwind blue-600
            },
            {
              label: 'Exits',
              data: exits,
              backgroundColor: '#22c55e' // Tailwind green-500
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
              labels: {
                font: {
                  size: 14
                }
              }
            }
          },
          scales: {
            x: {
              ticks: {
                font: {
                  size: 14
                }
              }
            },
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 5,
                font: {
                  size: 14
                }
              }
            }
          }
        }
      });
    }

    loadChartData();
  </script>

</body>
</html>
