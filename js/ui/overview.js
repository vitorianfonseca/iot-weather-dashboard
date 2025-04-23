// BLOCO 1: Função global para atualizar os cartões de visão geral
window.updateOverviewCards = function (data) {
  const { overviewMode } = window.config // Obtém o modo atual (ex: 'daily' ou 'hourly')
  const cards = document.querySelectorAll('.card-metric-overview') // Seleciona os cartões

  // BLOCO 2: Verifica se existem pelo menos 4 cartões para atualizar
  if (cards.length >= 4) {
    // BLOCO 3: Calcula os valores a mostrar, dependendo do modo (diário ou alternativo)
    const windValue = overviewMode === 'daily'
      ? data.wind.speed
      : (data.wind.speed + 3).toFixed(1)

    const rainValue = overviewMode === 'daily'
      ? data.clouds.all
      : Math.min(100, data.clouds.all + 10)

    const pressureValue = overviewMode === 'daily'
      ? data.main.pressure
      : data.main.pressure + 8

    // BLOCO 4: Atualiza os 3 primeiros cartões com valores de vento, chuva e pressão
    cards[0].querySelector('.value').textContent = `${windValue} km/h`
    cards[1].querySelector('.value').textContent = `${rainValue}%`
    cards[2].querySelector('.value').textContent = `${pressureValue} hpa`

    // BLOCO 5: Atualiza o 4.º cartão com a hora do pôr do sol, formatada como HH:MM
    const sunsetEl = cards[3].querySelector('.value')
    if (data.sys?.sunset && sunsetEl) {
      const sunsetDate = new Date(data.sys.sunset * 1000)
      const hours = sunsetDate.getHours().toString().padStart(2, '0')
      const minutes = sunsetDate.getMinutes().toString().padStart(2, '0')
      sunsetEl.textContent = `${hours}:${minutes}`
    }
  }
}
