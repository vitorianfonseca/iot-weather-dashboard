// api.js

const apiKey = '95c69da5dd0b0066b72f720d96d3d90b'

window.config = window.config || {
  lastWeatherData: null,
  currentCoords: { lat: -7.5666, lon: 110.8167 },
  apiKey,
}

// Mantém a versão 2.5 para UI principal e overview
window.fetchWeatherByCoords = async function (lat, lon) {
  const endpoint = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&appid=${apiKey}`

  const res = await fetch(endpoint)
  const data = await res.json()
  if (!res.ok || !data.coord) throw new Error('City not found')

  window.config.lastWeatherData = data
  window.config.currentCoords = { lat, lon }

  updateWeatherUI(data)
  updateOverviewCards(data)

  const forecastReady = await waitForElement('.weather-prediction')
  if (forecastReady && typeof updateForecastView === 'function') updateForecastView()
}

// Geocodificação continua igual
window.fetchCityCoordinates = async function (city) {
  const res = await fetch(
    `https://api.openweathermap.org/geo/1.0/direct?q=${city}&limit=1&appid=${apiKey}`
  )
  const results = await res.json()
  if (!results.length) throw new Error('Cidade não encontrada')

  const { lat, lon } = results[0]
  await fetchWeatherByCoords(lat, lon)
}

// Versão 3.0 só para os gráficos (daily forecast)
window.fetchDailyForecast = async function (lat, lon) {
  try {
    const url = `https://api.openweathermap.org/data/3.0/onecall?lat=${lat}&lon=${lon}&exclude=current,minutely,hourly,alerts&appid=${apiKey}&units=metric`
    const res = await fetch(url)
    const data = await res.json()
    return data.daily
  } catch (err) {
    console.error('Daily forecast fetch failed:', err)
    return null
  }
}

// util para garantir que o container está no DOM antes de renderizar
function waitForElement(selector, retries = 10, delay = 100) {
  return new Promise((resolve) => {
    const check = () => {
      const el = document.querySelector(selector)
      if (el) return resolve(el)
      if (retries <= 0) return resolve(null)
      retries--
      setTimeout(check, delay)
    }
    check()
  })
}
