<?php
// Sensores e atuadores
$dispositivos = [
  'temperatura' => ['tipo' => 'Sensor', 'icon' => 'high-temperature 1.svg'],
  'humidade' => ['tipo' => 'Sensor', 'icon' => 'humidity 1.svg'],
  'uv' => ['tipo' => 'Sensor', 'icon' => 'uv 1.svg'],
  'vento' => ['tipo' => 'Sensor', 'icon' => 'fan 1.svg'],
  'led' => ['tipo' => 'Atuador', 'icon' => 'led-light 1.svg'],
  'servo' => ['tipo' => 'Atuador', 'icon' => 'servo 1.svg'],
  'buzzer' => ['tipo' => 'Atuador', 'icon' => 'alarm 1.svg'],
];

function gerarTabela($id, $tipo, $icon) {
  $log_path = __DIR__ . "/../api/files/{$id}/log.txt";
  if (!file_exists($log_path)) {
    return "<tr><td colspan='5'>⚠️ Ficheiro não encontrado: {$id}</td></tr>";
  }

$linhas = array_reverse(file($log_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
  $html = '';

  foreach ($linhas as $linha) {
    $partes = explode(';', $linha);
    if (count($partes) !== 2) continue;

    [$hora, $valor] = array_map('trim', $partes);

    $label = match($id) {
      'temperatura' => '°C',
      'humidade' => '%',
      'vento' => 'km/h',
      default => '',
    };

    // Estado
    $estado = match($id) {
      'temperatura' => ($valor > 40 ? 'Elevado' : ($valor > 20 ? 'Normal' : 'Baixo')),
      'humidade' => ($valor > 80 ? 'Humido' : ($valor > 40 ? 'Normal' : 'Seco')),
      'uv' => ($valor > 7 ? 'Elevado' : ($valor > 4 ? 'Moderado' : 'Baixo')),
      'vento' => ($valor > 20 ? 'Forte' : 'Suave'),
      'led' => ($valor == 'Ativo' ? 'Ativo' : 'Inativo'),
      'servo' => ($valor == 'Proteção ativa' ? 'Ativo' : 'Inativo'),
      'buzzer' => ($valor == 'Alerta ativo' ? 'Ativo' : 'Inativo'),
      default => '',
    };

    $badge = match($estado) {
      'Elevado', 'Humido', 'Inativo', 'Forte' => 'badge bg-danger-subtle text-danger-emphasis',
      'Normal', 'Moderado', 'Ativo' => 'badge bg-success-subtle text-success-emphasis',
      'Baixo', 'Seco', 'Suave' => 'badge bg-primary-subtle text-primary-emphasis',
      default => '',
    };

    $html .= "<tr>
      <td><div class='d-flex align-items-center gap-3'>
        <img src='assets/sensors/{$icon}' alt='{$id}' width='32' />
        <span>" . ucfirst($id) . "</span>
      </div></td>
      <td>{$tipo}</td>
      <td>{$valor} {$label}</td>
      <td>{$hora}</td>
      <td><span class='{$badge}'>{$estado}</span></td>
    </tr>";
  }

  return $html;
}
?>

<div class="history-page">
  <div class="container my-5">
    <h3 class="fw-bold mb-5">Histórico de sensores e atuadores</h3>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
      <?php $i = 0; foreach ($dispositivos as $id => $d): ?>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= $i === 0 ? 'active' : '' ?>" id="tab-<?= $id ?>"
            data-bs-toggle="tab" data-bs-target="#pane-<?= $id ?>"
            type="button" role="tab">
            <?= ucfirst($id) ?>
          </button>
        </li>
      <?php $i++; endforeach ?>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content">
      <?php $i = 0; foreach ($dispositivos as $id => $d): ?>
        <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>" id="pane-<?= $id ?>" role="tabpanel">
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
                  <?= gerarTabela($id, $d['tipo'], $d['icon']) ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      <?php $i++; endforeach ?>
    </div>
  </div>
</div>