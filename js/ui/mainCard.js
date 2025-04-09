// ui/mainCard.js

window.updateMainCardValue = function (data) {
  const { metrics, metricIndex, isCelsius } = window.config
  const metric = metrics[metricIndex]

  const convertTemp = (temp) => (isCelsius ? Math.round(temp) : Math.round((temp * 9) / 5 + 32))

  const el = document.querySelector('.weather-card h1')
  if (!el) return

  if (metric === 'Air temperature') {
    el.textContent = `${convertTemp(data.main.temp)}°${isCelsius ? 'C' : 'F'}`
  } else if (metric === 'Humidity') {
    el.textContent = `${data.main.humidity}%`
  } else if (metric === 'Wind Speed') {
    el.textContent = `${data.wind.speed} km/h`
  } else if (metric === 'Rain Chance') {
    el.textContent = `${data.clouds.all}%`
  }
}
