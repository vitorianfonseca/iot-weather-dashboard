// weather-charts.js
document.addEventListener('DOMContentLoaded', function () {
  // Weekly Temperature Chart
  new Chart(document.getElementById('weeklyTemperatureChart'), {
    type: 'line',
    data: {
      labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
      datasets: [
        {
          data: [30, 31, 15, 32],
          fill: false,
          tension: 0.4,
          borderColor: '#3b82f6',
          borderWidth: 2,
          pointBackgroundColor: ['#3b82f6', '#3b82f6', 'orange', '#3b82f6'],
          pointRadius: [4, 4, 6, 4],
        },
      ],
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        y: { min: 10, max: 35, ticks: { stepSize: 5 } },
        x: { grid: { display: false } },
      },
    },
  })

  // Daily Rainfall Chart
  new Chart(document.getElementById('dailyRainfallChart'), {
    type: 'bar',
    data: {
      labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
      datasets: [
        {
          data: [10, 8, 9, 6, 5, 3, 2],
          backgroundColor: '#c7d2fe',
        },
        {
          data: [7, 6, 5, 4, 3, 2, 1],
          backgroundColor: '#818cf8',
        },
        {
          data: [4, 3, 2, 1, 1, 1, 1],
          backgroundColor: '#1e3a8a',
        },
      ],
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        x: { stacked: true, grid: { display: false } },
        y: { stacked: true, beginAtZero: true },
      },
    },
  })
})
