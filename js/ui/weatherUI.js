// BLOCO 1: Função principal para atualizar a interface do tempo
window.updateWeatherUI = function (data) {
  // Espera até que os cartões de overview estejam carregados (renderizados no DOM)
  if (document.querySelectorAll('.card-metric-overview').length < 4) {
    return setTimeout(() => updateWeatherUI(data), 300) // Tenta novamente após 300ms
  }

  // BLOCO 2: Atualiza a localização e data atual
  document.querySelector('.weather-card-location').textContent = data.name
  document.querySelector('.weather-card-date').textContent = new Date().toLocaleString([], {
    weekday: 'short',
    hour: '2-digit',
    minute: '2-digit',
  })

  // BLOCO 3: Determina se é noite com base no tempo atual e no nascer/pôr do sol
  const now = Date.now() / 1000
  const isNight = now < data.sys.sunrise || now > data.sys.sunset

  // BLOCO 4: Atualiza o estado do tempo (ex: "Clear", "Rain", etc.)
  const condition = data.weather[0].main
  document.querySelector('.weather-card-status').textContent = condition

  // BLOCO 5: Atualiza os diferentes componentes visuais do dashboard
  updateWeatherImage(condition, isNight) // Ícone do tempo
  updateMainCardValue(data)              // Métrica principal
  updateOverviewCards(data)             // Cartões secundários

  // BLOCO 6: Exibe o cartão de clima e remove o loader
  showWeatherCard()
}

// BLOCO 7: Mostra o cartão do tempo e remove o carregador da interface
window.showWeatherCard = function () {
  const loader = document.getElementById('loader')
  const card = document.querySelector('.weather-card')
  if (loader) loader.remove()                // Remove spinner/loader
  if (card) card.classList.remove('d-none') // Torna o cartão visível
}
