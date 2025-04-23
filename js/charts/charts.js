// BLOCO 1: Variáveis globais para guardar instâncias dos gráficos (Chart.js)
let tempChart = null
let rainChart = null

// BLOCO 2: Função que desenha os dois gráficos (temperatura e chuva) a partir dos dados diários
function drawChartsFromData(dailyData) {
  // Extrai os dias da semana para os rótulos dos gráficos
  const labels = dailyData.map((day) =>
    new Date(day.dt * 1000).toLocaleDateString('en-US', { weekday: 'short' })
  )

  // Prepara os dados de temperatura média diária
  const temperatures = dailyData.map((day) => day.temp.day)

  // Prepara os dados de volume de chuva (fallback para 0 se não houver campo)
  const rainVolumes = dailyData.map((day) => day.rain || 0)

  // Destroi os gráficos anteriores se existirem (para evitar duplicação)
  if (tempChart) tempChart.destroy()
  if (rainChart) rainChart.destroy()

      // BLOCO 3: Criação do gráfico de temperatura (linha)
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
          pointRadius: [4, 4, 6, 4, 4, 4, 4], // Destaque personalizado para o meio da semana
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

    // BLOCO 4: Criação do gráfico de precipitação (barras empilhadas)
  rainChart = new Chart(document.getElementById('dailyRainfallChart'), {
    type: 'bar',
    data: {
      labels,
      datasets: [
        {
          data: rainVolumes.map((v) => v * 0.4),  // Camada clara
          backgroundColor: '#c7d2fe',
        },
        {
          data: rainVolumes.map((v) => v * 0.35), // Camada intermédia
          backgroundColor: '#818cf8',
        },
        {
          data: rainVolumes.map((v) => v * 0.25), // Camada escura (base)
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

// BLOCO 5: Função principal para buscar os dados e desenhar os gráficos
async function drawWeatherCharts() {
  const coords = window.config.currentCoords
  const dailyData = await window.fetchDailyForecast(coords.lat, coords.lon)
  if (!dailyData) return

  // Guarda os dados no localStorage para carregamento offline/cache
  localStorage.setItem('cachedChartsData', JSON.stringify(dailyData))
  drawChartsFromData(dailyData)
}

// BLOCO 6: Ao carregar o DOM, tenta restaurar os gráficos a partir do cache (localStorage)
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

  // BLOCO 7: Botão para forçar atualização dos gráficos com novos dados
  const refreshBtn = document.getElementById('refreshChartsBtn')
  if (refreshBtn) {
    refreshBtn.addEventListener('click', () => {
      drawWeatherCharts()
    })
  }
})
