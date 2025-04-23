// BLOCO 1: Definição da chave da API e configuração global inicial
const apiKey = '95c69da5dd0b0066b72f720d96d3d90b'

// Inicializa o objeto global `window.config` com valores padrão
window.config = window.config || {
  lastWeatherData: null,                      // Últimos dados de tempo recebidos
  currentCoords: { lat: -7.5666, lon: 110.8167 }, // Coordenadas iniciais (fallback)
  apiKey,                                     // Chave da API
}

// BLOCO 2: Função principal para obter dados de clima atuais com base nas coordenadas
// Usa a versão 2.5 da OpenWeather para dados principais e overview
window.fetchWeatherByCoords = async function (lat, lon) {
  const endpoint = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&appid=${apiKey}`

  const res = await fetch(endpoint)
  const data = await res.json()

  // Verifica se a cidade foi encontrada
  if (!res.ok || !data.coord) throw new Error('City not found')

  // Atualiza o estado global
  window.config.lastWeatherData = data
  window.config.currentCoords = { lat, lon }

  // Atualiza a UI principal com os dados recebidos
  updateWeatherUI(data)
  updateOverviewCards(data)

  // Espera até que o container de previsão esteja disponível no DOM
  const forecastReady = await waitForElement('.weather-prediction')
  if (forecastReady && typeof updateForecastView === 'function') updateForecastView()
}

// BLOCO 3: Busca coordenadas com base no nome da cidade
// Usa geocodificação direta (API v1.0)
window.fetchCityCoordinates = async function (city) {
  const res = await fetch(
    `https://api.openweathermap.org/geo/1.0/direct?q=${city}&limit=1&appid=${apiKey}`
  )
  const results = await res.json()

  if (!results.length) throw new Error('Cidade não encontrada')

  const { lat, lon } = results[0]
  await fetchWeatherByCoords(lat, lon) // Encadeia a chamada para obter dados climáticos
}

// BLOCO 4: Busca previsão diária (gráficos) usando API One Call v3.0
window.fetchDailyForecast = async function (lat, lon) {
  try {
    const url = `https://api.openweathermap.org/data/3.0/onecall?lat=${lat}&lon=${lon}&exclude=current,minutely,hourly,alerts&appid=${apiKey}&units=metric`
    const res = await fetch(url)
    const data = await res.json()
    return data.daily // Retorna apenas o array de previsão diária
  } catch (err) {
    console.error('Daily forecast fetch failed:', err)
    return null
  }
}

// BLOCO 5: Utilitário que aguarda até que um elemento exista no DOM
// Usado para garantir que containers estejam prontos antes de renderizar
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
