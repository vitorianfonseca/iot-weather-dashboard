<?php
$sensores = [
  'temperatura' => ['label' => 'Temperatura', 'icon' => 'high-temperature 1.svg'],
  'vento' => ['label' => 'Velocidade do Vento', 'icon' => 'fan 1.svg'],
  'uv' => ['label' => 'Índice UV', 'icon' => 'uv 1.svg']
];

$atuadores = [
  'led' => ['label' => 'LED RGB', 'icon' => 'led-light 1.svg'],
  'servo' => ['label' => 'Servo Motor', 'icon' => 'servo 1.svg'],
  'buzzer' => ['label' => 'Buzzer', 'icon' => 'alarm 1.svg']
];

function lerLog($nome) {
  $ficheiro = __DIR__ . "/api/files/$nome/log.txt";
  $linhas = file_exists($ficheiro) ? file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
  return array_map(function($linha) {
    $partes = explode(' - ', $linha);
    return [
      'hora' => $partes[0] ?? '---',
      'valor' => $partes[1] ?? '---'
    ];
  }, $linhas);
}
?>

<div class="history-page">

<div class="container-fluid">
  <h4 class="fw-bold mb-4">Histórico de Sensores e Atuadores</h4>

  <!-- Tabs -->
  <ul class="nav nav-tabs mb-4" id="historyTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="tab-sensores" data-bs-toggle="tab" data-bs-target="#sensores" type="button" role="tab">Sensores</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tab-atuadores" data-bs-toggle="tab" data-bs-target="#atuadores" type="button" role="tab">Atuadores</button>
    </li>
  </ul>

  <div class="tab-content" id="historyTabContent">
    <!-- Sensores -->
    <div class="tab-pane fade show active" id="sensores" role="tabpanel">
      <div class="row">
        <?php foreach ($sensores as $id => $dados): ?>
          <div class="col-md-4 mb-4">
            <div class="sensor-card card">
              <div class="card-body d-flex align-items-center gap-3 mb-3">
                <img src="assets/sensors/<?= $dados['icon'] ?>" alt="<?= $dados['label'] ?>" class="icon">
                <div class="info">
                  <div class="fw-bold sensor-title"><?= $dados['label'] ?></div>
                  <span class="badge-type sensor">Sensor</span>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table custom-table">
                  <thead>
                    <tr><th>Hora</th><th>Valor</th></tr>
                  </thead>
                  <tbody>
                    <?php foreach (lerLog($id) as $linha): ?>
                      <tr>
                        <td><?= htmlspecialchars($linha['hora']) ?></td>
                        <td><?= htmlspecialchars($linha['valor']) ?></td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </div>

    <!-- Atuadores -->
    <div class="tab-pane fade" id="atuadores" role="tabpanel">
      <div class="row">
        <?php foreach ($atuadores as $id => $dados): ?>
          <div class="col-md-4 mb-4">
            <div class="sensor-card card">
              <div class="card-body d-flex align-items-center gap-3 mb-3">
                <img src="assets/sensors/<?= $dados['icon'] ?>" alt="<?= $dados['label'] ?>" class="icon">
                <div class="info">
                  <div class="fw-bold"><?= $dados['label'] ?></div>
                  <span class="badge-type actuator">Atuador</span>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table custom-table">
                  <thead>
                    <tr><th>Hora</th><th>Valor</th></tr>
                  </thead>
                  <tbody>
                    <?php foreach (lerLog($id) as $linha): ?>
                      <tr>
                        <td><?= htmlspecialchars($linha['hora']) ?></td>
                        <td><?= htmlspecialchars($linha['valor']) ?></td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</div>
</div>