// core/geolocation.js

window.initLocation = function () {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const lat = position.coords.latitude
        const lon = position.coords.longitude
        fetchWeatherByCoords(lat, lon)
      },
      () => {
        console.warn('⚠️ Geolocalização falhou, usando local padrão (Leiria)')
        const { lat, lon } = window.config.defaultLocation
        fetchWeatherByCoords(lat, lon)
      }
    )
  } else {
    console.warn('⚠️ Geolocalização não suportada, usando local padrão (Leiria)')
    const { lat, lon } = window.config.defaultLocation
    fetchWeatherByCoords(lat, lon)
  }
}
