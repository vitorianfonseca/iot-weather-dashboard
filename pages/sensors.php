<?php
  $sensores = ['buzzer', 'led', 'servo', 'temperatura', 'uv', 'vento', 'humidade'];

  foreach ($sensores as $sensor) {
    ${"valor_$sensor"} = file_get_contents("api/files/$sensor/valor.txt");
    ${"hora_$sensor"} = file_get_contents("api/files/$sensor/hora.txt");
  }
?>

<section class="container py-4">
  <h4 class="fw-bold mb-4">Sensor Overview</h4>
  <div class="row gy-4 gx-3">
    <!-- Temperatura -->
    <div class="col-md-4">
      <div class="sensor-card">
        <div class="card-header">
          <span class="card-title">Temperatura</span>
          <span class="badge-type sensor">Sensor</span>
        </div>
        <div class="card-body">
          <img src="assets/sensors/high-temperature.svg" alt="Temperatura" class="icon" >
          <div class="info">
            <div class="value"><?php echo $valor_temperatura . " °C"; ?></div>
            <div class="subtext">BME280</div>
          </div>
        </div>
        <div class="card-footer">
          <small class="text-muted">Atualização:&nbsp;<?php echo $hora_temperatura?></small>
          <a href="index.php?page=history&sensor=temperatura" class="history-link">Ver histórico</a>
        </div>
      </div>
    </div>

    <!-- Velocidade do Vento -->
    <div class="col-md-4">
      <div class="sensor-card">
        <div class="card-header">
          <span class="card-title">Humidade</span>
          <span class="badge-type sensor">Sensor</span>
        </div>
        <div class="card-body">
          <img src="assets/sensors/humidity.svg" alt="Vento" class="icon" >
          <div class="info">
            <div class="value"><?php echo $valor_humidade . " %"; ?></div>
            <div class="subtext">BME280</div>
          </div>
        </div>
        <div class="card-footer">
          <small class="text-muted">Atualização:&nbsp;<?php echo $hora_humidade?></small>
          <a href="index.php?page=history&sensor=humidade" class="history-link">Ver histórico</a>
        </div>
      </div>
    </div>

    <!-- Índice UV -->
    <div class="col-md-4">
      <div class="sensor-card">
        <div class="card-header">
          <span class="card-title">Índice UV</span>
          <span class="badge-type sensor">Sensor</span>
        </div>
        <div class="card-body">
          <img src="assets/sensors/uv.svg" alt="Índice UV" class="icon" >
          <div class="info">
            <div class="value "><?php echo $valor_uv?></div>
            <div class="subtext">VEML6075</div>
          </div>
        </div>
        <div class="card-footer">
          <small class="text-muted">Atualização:&nbsp;<?php echo $hora_uv?></small>
          <a href="index.php?page=history&sensor=uv" class="history-link">Ver histórico</a>
        </div>
      </div>
    </div>

    <!-- LED RGB -->
    <div class="col-md-4">
      <div class="sensor-card">
        <div class="card-header">
          <span class="card-title">LED RGB</span>
          <span class="badge-type actuator">Atuador</span>
        </div>
        <div class="card-body">
          <img src="assets/sensors/led-light.svg" alt="LED" class="icon" >
          <div class="info">
            <div class="value "><?php echo $valor_led?></div>
            <div class="subtext">WS2812</div>
          </div>
        </div>
        <div class="card-footer">
          <small class="text-muted">Atualização:&nbsp;<?php echo $hora_led?></small>
          <a href="index.php?page=history&sensor=led" class="history-link">Ver histórico</a>
        </div>
      </div>
    </div>

    <!-- Servo Motor -->
    <div class="col-md-4">
      <div class="sensor-card">
        <div class="card-header">
          <span class="card-title">Servo Motor</span>
          <span class="badge-type actuator">Atuador</span>
        </div>
        <div class="card-body">
          <img src="assets/sensors/servo.svg" alt="Servo" class="icon" >
          <div class="info">
            <div class="value">
              <?php echo $valor_servo?>
            </div>
            <div class="subtext">SG90</div>
          </div>
        </div>
        <div class="card-footer">
          <small class="text-muted">Atualização:&nbsp;<?php echo $hora_servo?></small>
          <a href="index.php?page=history&sensor=servo" class="history-link">Ver histórico</a>
        </div>
      </div>
    </div>

    <!-- Buzzer -->
    <div class="col-md-4">
      <div class="sensor-card">
        <div class="card-header">
          <span class="card-title">Buzzer</span>
          <span class="badge-type actuator">Atuador</span>
        </div>
        <div class="card-body">
          <img src="assets/sensors/alarm.svg" alt="Buzzer" class="icon" >
          <div class="info">
            <div class="value ">
              <?php echo $valor_buzzer?>
            </div>
            <div class="subtext">5V</div>
          </div>
        </div>
        <div class="card-footer">
          <small class="text-muted">Atualização:&nbsp;<?php echo $hora_buzzer?></small>
          <a href="index.php?page=history&sensor=buzzer" class="history-link">Ver histórico</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container my-5">
  <h4 class="fw-bold mb-4">Tabela de Sensores e Atuadores</h4>
  <div class="card p-3 rounded-4 shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle custom-table mb-0">
        <thead class="table-light">
          <tr>
            <th>Dispositivo</th>
            <th>Tipo</th>
            <th>Valor</th>
            <th>Atualização</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <img src="assets/sensors/high-temperature.svg" alt="Temperatura" width="32" >
                <span>Temperatura</span>
              </div>
            </td>
            <td>Sensor</td>
            <td><?php echo $valor_temperatura . " °C"; ?></td>
            <td><?php echo $hora_temperatura?></td>
            <td>
                <?php if ($valor_temperatura > 40 ) {
                    echo "<span class='badge bg-danger-subtle text-danger-emphasis'>Elevado</span>"; 
                  } elseif ($valor_temperatura < 40 && $valor_temperatura > 20) {
                    echo "<span class='badge bg-success-subtle text-success-emphasis'>Normal</span>";
                  } else {
                    echo "<span class='badge bg-primary-subtle text-primary-emphasis'>Baixo</span>";
                  }
                ?>
          </td>
          </tr>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <img src="assets/sensors/humidity.svg" alt="Humidade" width="32" >
                <span>Humidade</span>
              </div>
            </td>
            <td>Sensor</td>
            <td><?php echo $valor_humidade . " %"; ?></td>
            <td><?php echo $hora_humidade?></td>
            <td>
              <?php if ($valor_humidade > 50 ) {
                    echo "<span class='badge bg-danger-subtle text-danger-emphasis'>Humido</span>"; 
                  } elseif ($valor_humidade < 50 && $valor_humidade > 20) {
                    echo "<span class='badge bg-success-subtle text-success-emphasis'>Medio</span>";
                  } else {
                    echo "<span class='badge bg-primary-subtle text-primary-emphasis'>Seco</span>";
                  }
                ?>
            </td>
          </tr>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <img src="assets/sensors/uv.svg" alt="Índice UV" width="32" >
                <span>Índice UV</span>
              </div>
            </td>
            <td>Sensor</td>
            <td><?php echo $valor_uv?></td>
            <td><?php echo $hora_uv?></td>
            <td>
                <?php if ($valor_uv > 7 ) {
                    echo "<span class='badge bg-danger-subtle text-danger-emphasis'>Elevado</span>"; 
                  } elseif ($valor_uv < 4 && $valor_uv > 7) {
                    echo "<span class='badge bg-success-subtle text-success-emphasis'>Moderado</span>";
                  } else {
                    echo "<span class='badge bg-primary-subtle text-primary-emphasis'>Baixo</span>";
                  }
                ?>
            </td>
          </tr>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <img src="assets/sensors/led-light.svg" alt="LED" width="32" >
                <span>LED RGB</span>
              </div>
            </td>
            <td>Atuador</td>
            <td><?php echo $valor_led?></td>
            <td><?php echo $hora_led?></td>
            <td>
              <span class="badge bg-success-subtle text-success-emphasis">Ativo</span>
            </td>
          </tr>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <img src="assets/sensors/servo.svg" alt="Servo Motor" width="32" >
                <span>Servo Motor</span>
              </div>
            </td>
            <td>Atuador</td>
            <td>
              <?php 
                if ($valor_servo == "0") {
                  echo "Proteção ativa";
                } elseif ($valor_servo == "1") {
                  echo "Proteção inativa";
                } else {
                  echo "Erro";
                }                
              ?>
            </td>
            <td><?php echo $hora_servo?></td>
            <td><span class="badge bg-success-subtle text-success-emphasis">Ativo</span></td>
          </tr>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <img src="assets/sensors/alarm.svg" alt="Buzzer" width="32" >
                <span>Buzzer 5V</span>
              </div>
            </td>
            <td>Atuador</td>
            <td><?php echo $valor_buzzer?></td>
            <td><?php echo $hora_buzzer?></td>
            <td><span class="badge bg-danger-subtle text-danger-emphasis">Ativo</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>
     <script>
    // Save scroll position on scroll
    window.addEventListener('scroll', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
    });

    // Restore scroll position on load
    window.addEventListener('load', function() {
      const savedScrollPosition = localStorage.getItem('scrollPosition');
      if (savedScrollPosition !== null) {
        window.scrollTo(0, savedScrollPosition);
      }
    });
  </script>