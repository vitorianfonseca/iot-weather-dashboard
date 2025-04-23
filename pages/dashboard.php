<!-- BLOCO 1: Linha principal do dashboard com o cartão do tempo e a visão geral -->
<div class="row weather-top">

  <!-- BLOCO 1.1: Weather Card (lado esquerdo) -->
  <div class="col-lg-6 d-flex">
    <section class="weather-card w-100 h-100 d-none">
      <!-- Cabeçalho do cartão com localização e hora atual -->
      <div class="d-flex justify-content-between header-weather-card">
        <span class="d-flex align-items-center gap-2">
          <img src="assets/icons/location.svg" width="18" alt="Location icon">
          <span class="weather-card-location">Surakarta</span>
        </span>
        <span class="weather-card-date" id="liveTime">Wed 07:03 PM</span>
      </div>

      <!-- Informação principal: temperatura e condição -->
      <div class="d-flex align-items-center justify-content-center weather-card-info">
        <div class="weather-card-info-left d-flex flex-column align-items-center">
          <h2 class="temp">24°</h2>
          <p class="weather-card-status">Sunny</p>
        </div>
        <div class="weather-card-info-right">
          <img id="weatherIcon" src="assets/weather/day.svg" width="150" alt="Weather Icon">
        </div>
      </div>

      <!-- Navegação entre métricas -->
      <button class="d-flex justify-content-between align-items-center w-100">
        <img id="metric-prev" src="assets/icons/arrow-left.svg" width="16" alt="Previous">
        <span class="mx-2 fw-medium" id="weatherMetric">Air temperature</span>
        <img id="metric-next" src="assets/icons/arrow-right.svg" width="16" alt="Next">
      </button>
    </section>
  </div>

    <!-- BLOCO 1.2: Weather Overview (lado direito) -->
  <div class="col-lg-6 d-flex">
    <section class="weather-overview w-100 h-100">
      <div class="weather-overview-info d-flex justify-content-between align-items-center">
        <h5>Weather overview</h5>

        <!-- Botão para alternar entre 'Daily' e 'Weekly' -->
        <button id="overviewToggleBtn" class="d-flex justify-content-between align-items-center w-25">
          <img src="assets/icons/arrow-left.svg" width="16" alt="Previous">
          <span class="mx-2 fw-medium" id="overviewToggleLabel">Daily</span>
          <img src="assets/icons/arrow-right.svg" width="16" alt="Next">
        </button>
      </div>

      <!-- Cartões de visão geral: vento, chuva, pressão e pôr do sol -->
      <div class="row g-4 weather-overview-grid">
        <div class="col-6 col-md-6 col-xl-6">
          <div class="card-metric-overview">
            <div class="card-metric-left-overview">
              <img src="assets/overview/wind.svg" width="28" alt="Rain Chance">
            </div>
            <div class="card-metric-right-overview">
              <p class="label">Wind Speed</p>
              <p class="value">12 km/h</p>
            </div>
          </div>
        </div>

        <!-- Repetição do mesmo padrão para outros 3 indicadores -->
        <div class="col-6 col-md-6 col-xl-6">
          <div class="card-metric-overview">
            <div class="card-metric-left-overview">
              <img src="assets/overview/rain.svg" width="28" alt="Rain Chance">
            </div>
            <div class="card-metric-right-overview">
              <p class="label">Rain Chance</p>
              <p class="value">24%</p>
            </div>
          </div>
        </div>

        <div class="col-6 col-md-6 col-xl-6">
          <div class="card-metric-overview">
            <div class="card-metric-left-overview">
              <img src="assets/overview/wind-2.svg" width="28" alt="Pressure">
            </div>
            <div class="card-metric-right-overview">
              <p class="label">Pressure</p>
              <p class="value">720 hpa</p>
            </div>
          </div>
        </div>

        <div class="col-6 col-md-6 col-xl-6">
          <div class="card-metric-overview">
            <div class="card-metric-left-overview">
              <img src="assets/overview/sun-fog.svg" width="22" alt="Sunset">
            </div>
            <div class="card-metric-right-overview">
              <p class="label">Sunset</p>
              <p class="value">--</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

    <!-- BLOCO 2: Gráficos de temperatura e chuva -->
  <div class="row charts">
    <!-- Botão para atualizar os gráficos manualmente -->
    <div class="d-flex justify-content-start mt-3 mb-3 px-3">
      <button id="refreshChartsBtn" class="d-flex align-items-center refreshbttn">
        <span>Atualizar Gráficos</span>
      </button>
    </div>

    <!-- Gráfico de temperatura semanal -->
    <div class="col-lg-6 col-12">
      <div class="p-3 rounded-4 shadow-sm border" style="background-color: #fff">
        <h6 class="mb-3">Average Weekly Temperature</h6>
        <canvas id="weeklyTemperatureChart" height="200"></canvas>
      </div>
    </div>

    <!-- Gráfico de precipitação diária -->
    <div class="col-lg-6 col-12">
      <div class="p-3 rounded-4 shadow-sm border" style="background-color: #fff">
        <h6 class="mb-3">Average Daily Rainfall</h6>
        <canvas id="dailyRainfallChart" height="200"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- BLOCO 3: Scripts necessários para funcionalidade do dashboard -->

<!-- Biblioteca de gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Configurações globais -->
<script src="js/config.js"></script>

<!-- Lógica de gráficos -->
<script src="js/charts/charts.js"></script>

<!-- Core do sistema -->
<script src="js/core/api.js"></script>
<script src="js/core/geolocation.js"></script>
<script src="js/core/forecast.js"></script>

<!-- Atualizações visuais -->
<script src="js/ui/mainCard.js"></script>
<script src="js/ui/overview.js"></script>
<script src="js/ui/weatherImage.js"></script>
<script src="js/ui/weatherUI.js"></script>

<!-- Event handlers -->
<script src="js/events/listeners.js"></script>

<!-- Inicialização -->
<script src="js/app.js"></script>

<!-- BLOCO 4: Carregamento automático ao iniciar a página -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // força uma atualização com as coordenadas padrão
    fetchWeatherByCoords(window.config.currentCoords.lat, window.config.currentCoords.lon)
  })
</script>
