// BLOCO 1: Listener global para cliques no documento
document.addEventListener('click', (e) => {
  const { metrics, lastWeatherData } = window.config

  // Navegação entre métricas (temperatura, vento, etc.)
  handleMetricNavigation(e, metrics, lastWeatherData)

  // Botões de previsão (diária/semanal)
  handleForecastButtons(e)

  // Alternar modo de visão geral (daily ↔ weekly)
  handleOverviewToggle(e, lastWeatherData)

  // Alternar tipo de previsão (ex: "This week" ↔ "Next days")
  handleForecastToggle(e)
})

// BLOCO 2: Lida com a navegação entre diferentes métricas meteorológicas
function handleMetricNavigation(e, metrics, lastWeatherData) {
  if (e.target.id === 'metric-prev') {
    window.config.metricIndex = (window.config.metricIndex - 1 + metrics.length) % metrics.length
    document.getElementById('weatherMetric').textContent = metrics[window.config.metricIndex]
    if (lastWeatherData) updateMainCardValue(lastWeatherData)
  }

  if (e.target.id === 'metric-next') {
    window.config.metricIndex = (window.config.metricIndex + 1) % metrics.length
    document.getElementById('weatherMetric').textContent = metrics[window.config.metricIndex]
    if (lastWeatherData) updateMainCardValue(lastWeatherData)
  }
}

// BLOCO 3: Botões de previsão diária/semanal (sem alternância de estado)
function handleForecastButtons(e) {
  if (e.target.id === 'btn-daily' || e.target.id === 'btn-weekly') {
    updateForecastView(7)
  }
}

// BLOCO 4: Alternar entre visão "Daily" e "Weekly" nos cartões de visão geral
function handleOverviewToggle(e, lastWeatherData) {
  if (e.target.closest('#overviewToggleBtn')) {
    const label = document.getElementById('overviewToggleLabel')
    const current = label.textContent.trim()
    window.config.overviewMode = current === 'Daily' ? 'weekly' : 'daily'
    label.textContent = current === 'Daily' ? 'Weekly' : 'Daily'
    if (lastWeatherData) updateOverviewCards(lastWeatherData)
  }
}

// BLOCO 5: Alternar entre previsões "This week" e "Next days"
function handleForecastToggle(e) {
  if (e.target.closest('#overviewToggle')) {
    const label = document.getElementById('forecastToggleLabel')
    const current = label.textContent.trim()

    if (current === 'This week') {
      updateForecastView(3)
      label.textContent = 'Next days'
    } else {
      updateForecastView(7)
      label.textContent = 'This week'
    }
  }
}

// BLOCO 6: Autocomplete do campo de cidade
document.addEventListener('input', async (e) => {
  const { apiKey } = window.config
  if (e.target.id !== 'searchCity') return

  const query = e.target.value.trim()
  const suggestionsList = document.getElementById('suggestions')
  if (query.length < 2) return (suggestionsList.innerHTML = '')

  try {
    const res = await fetch(
      `https://api.openweathermap.org/geo/1.0/direct?q=${query}&limit=5&appid=${apiKey}`
    )
    const results = await res.json()
    suggestionsList.innerHTML = ''

    // Sem resultados
    if (!results.length) {
      suggestionsList.innerHTML = `<li class="list-group-item">No results found</li>`
      return
    }

    // Apresenta sugestões clicáveis
    results.forEach((place) => {
      const cityItem = document.createElement('li')
      cityItem.className = 'list-group-item list-group-item-action'
      cityItem.style.cursor = 'pointer'
      cityItem.textContent = `${place.name}${place.state ? ', ' + place.state : ''}, ${place.country}`

      cityItem.addEventListener('click', () => {
        document.getElementById('searchCity').value = ''
        suggestionsList.innerHTML = ''
        fetchWeatherByCoords(place.lat, place.lon)
      })

      suggestionsList.appendChild(cityItem)
    })
  } catch (err) {
    console.error('Autocomplete failed:', err)
  }
})

// BLOCO 7: Submeter cidade com Enter no campo de pesquisa
document.addEventListener('keypress', (e) => {
  if (e.target.id === 'searchCity' && e.key === 'Enter') {
    const city = e.target.value.trim()
    if (!city) return
    fetchCityCoordinates(city).catch(() => alert('Cidade não encontrada.'))
    document.getElementById('suggestions').innerHTML = ''
  }
})
