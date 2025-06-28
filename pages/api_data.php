<?php
$basePath = "api/files/externo";

$valor_externo = file_exists("$basePath/valor.txt") ? file_get_contents("$basePath/valor.txt") : "N/A";
$hora_externo  = file_exists("$basePath/hora.txt")  ? file_get_contents("$basePath/hora.txt")  : "Sem dados";
?>

<section class="container py-4">
  <h4 class="fw-bold mb-4">Leitura de API Externa (via Raspberry Pi)</h4>
  <div class="row gy-4 gx-3">
    <!-- Card API Externa -->
    <div class="col-md-4">
      <div class="sensor-card">
        <div class="card-header">
          <span class="card-title">API Externa</span>
          <span class="badge-type sensor">Sensor</span>
        </div>
        <div class="card-body">
          <img src="assets/sensors/api.svg" alt="API" class="icon" >
          <div class="info">
            <div class="value"><?php echo $valor_externo; ?></div>
            <div class="subtext">Fonte externa</div>
          </div>
        </div>
        <div class="card-footer">
          <small class="text-muted">Atualização:&nbsp;<?php echo $hora_externo; ?></small>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // Scroll persistente
  window.addEventListener('scroll', function() {
    localStorage.setItem('scrollPosition', window.scrollY);
  });

  window.addEventListener('load', function() {
    const savedScrollPosition = localStorage.getItem('scrollPosition');
    if (savedScrollPosition !== null) {
      window.scrollTo(0, savedScrollPosition);
    }
  });
</script>
