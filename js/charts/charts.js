let tempChart = null
let rainChart = null

function drawChartsFromData(dailyData) {
  const labels = dailyData.map((day) =>
    new Date(day.dt * 1000).toLocaleDateString('en-US', { weekday: 'short' })
  )
  const temperatures = dailyData.map((day) => day.temp.day)
  const rainVolumes = dailyData.map((day) => day.rain || 0)

  if (tempChart) tempChart.destroy()
  if (rainChart) rainChart.destroy()

  tempChart = new Chart(document.getElementById('weeklyTemperatureChart'), {
    type: 'line',
    data: {
      labels,
      datasets: [
        {
          data: temperatures,
          fill: false,
          tension: 0.4,
          borderColor: '#3b82f6',
          borderWidth: 2,
          pointBackgroundColor: ['#3b82f6', '#3b82f6', 'orange', '#3b82f6'],
          pointRadius: [4, 4, 6, 4, 4, 4, 4],
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

  rainChart = new Chart(document.getElementById('dailyRainfallChart'), {
    type: 'bar',
    data: {
      labels,
      datasets: [
        {
          data: rainVolumes.map((v) => v * 0.4),
          backgroundColor: '#c7d2fe',
        },
        {
          data: rainVolumes.map((v) => v * 0.35),
          backgroundColor: '#818cf8',
        },
        {
          data: rainVolumes.map((v) => v * 0.25),
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
}

async function drawWeatherCharts() {
  const coords = window.config.currentCoords
  const dailyData = await window.fetchDailyForecast(coords.lat, coords.lon)
  if (!dailyData) return

  localStorage.setItem('cachedChartsData', JSON.stringify(dailyData))
  drawChartsFromData(dailyData)
}

document.addEventListener('DOMContentLoaded', () => {
  const cached = localStorage.getItem('cachedChartsData')
  if (cached) {
    try {
      const parsed = JSON.parse(cached)
      drawChartsFromData(parsed)
    } catch (e) {
      console.warn('Erro ao restaurar gráfico do localStorage:', e)
    }
  }

  const refreshBtn = document.getElementById('refreshChartsBtn')
  if (refreshBtn) {
    refreshBtn.addEventListener('click', () => {
      drawWeatherCharts()
    })
  }
})
