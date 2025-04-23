// BLOCO 1: Função global para atualizar o valor principal do cartão de clima
window.updateMainCardValue = function (data) {
  // Extrai as configurações globais
  const { metrics, metricIndex, isCelsius } = window.config
  const metric = metrics[metricIndex]

  // BLOCO 2: Função auxiliar para converter temperaturas, se necessário
  const convertTemp = (temp) => (
    isCelsius ? Math.round(temp) : Math.round((temp * 9) / 5 + 32)
  )

  // BLOCO 3: Seleciona o elemento onde será exibido o valor
  const el = document.querySelector('.weather-card .temp')
  if (!el) return // Se o elemento não existir, sai da função

  // BLOCO 4: Atualiza o conteúdo do cartão com base na métrica selecionada
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
