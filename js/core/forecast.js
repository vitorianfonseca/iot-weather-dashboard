// core/forecast.js

window.updateForecastView = async function (daysToShow = 7) {
  const { currentCoords } = window.config
  const daily = await fetchDailyForecast(currentCoords.lat, currentCoords.lon)
  if (!daily) return

  const container = document.querySelector('.weather-prediction')
  if (!container) return

  container.innerHTML = ''

  const iconMap = {
    clear: 'sun.svg',
    sunny: 'sun.svg',
    clouds: 'cloud.svg',
    rain: 'rain.svg',
    drizzle: 'rain.svg',
    thunderstorm: 'flash.svg',
    snow: 'snow.svg',
    mist: 'sun-fog.svg',
    haze: 'sun-fog.svg',
    fog: 'sun-fog.svg',
    smoke: 'sun-fog.svg',
    dust: 'sun-fog.svg',
    sand: 'sun-fog.svg',
    squall: 'wind.svg',
    tornado: 'wind.svg',
  }

  daily.slice(0, daysToShow).forEach((day) => {
    const date = new Date(day.dt * 1000)
    const dayName = date.toLocaleDateString('en-US', { weekday: 'short' })
    const temp = Math.round(day.temp.day)
    const condition = day.weather[0].main.toLowerCase()

    const iconFile = iconMap[condition] || 'sun.svg'

    const card = document.createElement('article')
    card.className = 'card-metric-prediction'
    card.innerHTML = `
      <div class="card-metric-left-prediction">
        <img src="assets/overview/${iconFile}" width="22" alt="${condition}" />
      </div>
      <div class="card-metric-right-prediction">
        <p class="label">${dayName}</p>
        <p class="value">${temp}º</p>
      </div>
    `
    container.appendChild(card)
  })
}
