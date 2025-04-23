// BLOCO 1: Função global para iniciar a localização do utilizador
window.initLocation = function () {
  // Verifica se o navegador suporta geolocalização
  if (navigator.geolocation) {
    // Tenta obter a localização atual do utilizador
    navigator.geolocation.getCurrentPosition(
      (position) => {
        // BLOCO 2: Se bem-sucedido, extrai latitude e longitude
        const lat = position.coords.latitude
        const lon = position.coords.longitude
        fetchWeatherByCoords(lat, lon) // Atualiza o tempo com base na posição real
      },
      () => {
        // BLOCO 3: Se falhar (ex: negado ou erro), usa localização padrão
        console.warn('⚠️ Geolocalização falhou, usando local padrão (Leiria)')
        const { lat, lon } = window.config.defaultLocation
        fetchWeatherByCoords(lat, lon)
      }
    )
  } else {
    // BLOCO 4: Se o navegador não suportar geolocalização, também usa o fallback
    console.warn('⚠️ Geolocalização não suportada, usando local padrão (Leiria)')
    const { lat, lon } = window.config.defaultLocation
    fetchWeatherByCoords(lat, lon)
  }
}
