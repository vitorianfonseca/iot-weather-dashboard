// events.js

document.addEventListener('click', (e) => {
  const { metrics, lastWeatherData } = window.config

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

  if (e.target.id === 'btn-daily') {
    updateForecastView(7)
  }

  if (e.target.id === 'btn-weekly') {
    updateForecastView(7)
  }

  if (e.target.closest('#overviewToggleBtn')) {
    const label = document.getElementById('overviewToggleLabel')
    const current = label.textContent.trim()
    window.config.overviewMode = current === 'Daily' ? 'weekly' : 'daily'
    label.textContent = current === 'Daily' ? 'Weekly' : 'Daily'
    if (lastWeatherData) updateOverviewCards(lastWeatherData)
  }

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
})

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
    if (!results.length) {
      suggestionsList.innerHTML = `<li class="list-group-item">No results found</li>`
      return
    }

    results.forEach((place) => {
      const cityItem = document.createElement('li')
      cityItem.className = 'list-group-item list-group-item-action'
      cityItem.style.cursor = 'pointer'
      cityItem.textContent = `${place.name}${place.state ? ', ' + place.state : ''}, ${
        place.country
      }`
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

document.addEventListener('keypress', (e) => {
  if (e.target.id === 'searchCity' && e.key === 'Enter') {
    const city = e.target.value.trim()
    if (!city) return
    fetchCityCoordinates(city).catch(() => alert('Cidade não encontrada.'))
    document.getElementById('suggestions').innerHTML = ''
  }
})
