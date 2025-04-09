// ui/weatherUI.js

window.updateWeatherUI = function (data) {
  if (document.querySelectorAll('.card-metric-overview').length < 4) {
    return setTimeout(() => updateWeatherUI(data), 300)
  }

  document.querySelector('.weather-card-location').textContent = data.name
  document.querySelector('.weather-card-date').textContent = new Date().toLocaleString([], {
    weekday: 'short',
    hour: '2-digit',
    minute: '2-digit',
  })

  const now = Date.now() / 1000
  const isNight = now < data.sys.sunrise || now > data.sys.sunset
  const condition = data.weather[0].main

  document.querySelector('.weather-card-status').textContent = condition

  updateWeatherImage(condition, isNight)
  updateMainCardValue(data)
  updateOverviewCards(data)

  showWeatherCard()
}

window.showWeatherCard = function () {
  const loader = document.getElementById('loader')
  const card = document.querySelector('.weather-card')
  if (loader) loader.remove()
  if (card) card.classList.remove('d-none')
}
