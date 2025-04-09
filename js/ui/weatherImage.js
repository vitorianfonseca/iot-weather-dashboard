// ui/weatherImage.js

window.updateWeatherImage = function (condition, isNight = false) {
  const weatherIcon = document.getElementById('weatherIcon')
  if (!weatherIcon) return
  weatherIcon.style.opacity = 0

  const mapDay = {
    Clear: 'day.svg',
    Sunny: 'day.svg',
    Clouds: 'cloudy.svg',
    Rain: 'rainy-5.svg',
    Drizzle: 'rainy-4.svg',
    Thunderstorm: 'thunder.svg',
    Snow: 'snowy-5.svg',
    Mist: 'cloudy-day-3.svg',
    Haze: 'cloudy-day-1.svg',
    Fog: 'cloudy-day-2.svg',
    Smoke: 'cloudy-day-2.svg',
    Dust: 'cloudy-day-1.svg',
    Sand: 'cloudy-day-1.svg',
    Ash: 'cloudy-day-1.svg',
    Squall: 'rainy-7.svg',
    Tornado: 'thunder.svg',
  }

  const mapNight = {
    Clear: 'night.svg',
    Clouds: 'cloudy-night-2.svg',
    Rain: 'rainy-6.svg',
    Drizzle: 'rainy-4.svg',
    Thunderstorm: 'thunder.svg',
    Snow: 'snowy-3.svg',
    Mist: 'cloudy-night-3.svg',
    Haze: 'cloudy-night-1.svg',
    Fog: 'cloudy-night-2.svg',
    Smoke: 'cloudy-night-2.svg',
    Dust: 'cloudy-night-1.svg',
    Sand: 'cloudy-night-1.svg',
    Ash: 'cloudy-night-1.svg',
    Squall: 'rainy-7.svg',
    Tornado: 'thunder.svg',
  }

  const fileName = isNight
    ? mapNight[condition] || 'weather.svg'
    : mapDay[condition] || 'weather.svg'

  weatherIcon.src = `assets/weather/${fileName}`
  weatherIcon.alt = condition
  weatherIcon.className = 'weather-icon'
  weatherIcon.style.opacity = 1
}
