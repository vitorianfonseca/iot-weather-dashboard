// overview.js

window.updateOverviewCards = function (data) {
  const { overviewMode } = window.config
  const cards = document.querySelectorAll('.card-metric-overview')

  if (cards.length >= 4) {
    const windValue = overviewMode === 'daily' ? data.wind.speed : (data.wind.speed + 3).toFixed(1)

    const rainValue =
      overviewMode === 'daily' ? data.clouds.all : Math.min(100, data.clouds.all + 10)

    const pressureValue = overviewMode === 'daily' ? data.main.pressure : data.main.pressure + 8

    cards[0].querySelector('.value').textContent = `${windValue} km/h`
    cards[1].querySelector('.value').textContent = `${rainValue}%`
    cards[2].querySelector('.value').textContent = `${pressureValue} hpa`

    // Sunset
    const sunsetEl = cards[3].querySelector('.value')
    if (data.sys?.sunset && sunsetEl) {
      const sunsetDate = new Date(data.sys.sunset * 1000)
      const hours = sunsetDate.getHours().toString().padStart(2, '0')
      const minutes = sunsetDate.getMinutes().toString().padStart(2, '0')
      sunsetEl.textContent = `${hours}:${minutes}`
    }
  }
}
