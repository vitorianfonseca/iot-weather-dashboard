// config.js
window.config = {
  apiKey: '95c69da5dd0b0066b72f720d96d3d90b', // 🔒 substitui por .env no futuro
  defaultLocation: { lat: 39.74362, lon: -8.80705 }, // Leiria como fallback

  metricIndex: 0,
  isCelsius: true,
  overviewMode: 'daily',
  metrics: ['Air temperature', 'Humidity', 'Wind Speed', 'Rain Chance'],

  lastWeatherData: null,
  currentCoords: { lat: -7.5666, lon: 110.8167 },
}
