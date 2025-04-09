// app.js

// ⚠️ Certifique-se de carregar os scripts nesta ordem no HTML:
// config.js → core/api.js → core/geolocation.js → outros módulos → app.js

document.addEventListener('DOMContentLoaded', () => {
  console.log('🌤️ Iniciando Weather Dashboard...')
  initLocation()
})
