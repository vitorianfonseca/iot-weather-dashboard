<?php
// BLOCO 1: Identifica o sensor selecionado via parâmetro GET (fallback para 'temperatura')
$selectedSensor = isset($_GET['sensor']) ? $_GET['sensor'] : 'temperatura';

// BLOCO 2: Lista de sensores e atuadores com respetivo tipo e ícone
$dispositivos = [
  'temperatura' => ['tipo' => 'Sensor', 'icon' => 'high-temperature.svg'],
  'humidade'    => ['tipo' => 'Sensor', 'icon' => 'humidity.svg'],
  'uv'          => ['tipo' => 'Sensor', 'icon' => 'uv.svg'],
  'led'         => ['tipo' => 'Atuador', 'icon' => 'led-light.svg'],
  'servo'       => ['tipo' => 'Atuador', 'icon' => 'servo.svg'],
  'buzzer'      => ['tipo' => 'Atuador', 'icon' => 'alarm.svg'],
];

// BLOCO 3: Limpa o log, mantendo os últimos 5
if (isset($_GET['limpar']) && isset($_GET['sensor'])) {
  $id = $_GET['sensor'];
  $tipo = isset($dispositivos[$id]) ? strtolower($dispositivos[$id]['tipo']) : null;

  if ($tipo) {
    $log_path = __DIR__ . "/../api/files/{$tipo}/{$id}/log.txt";
    if (file_exists($log_path)) {
      $linhas = array_reverse(file($log_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
      $ultimas5 = array_slice($linhas, 0, 5);
      file_put_contents($log_path, implode("\n", array_reverse($ultimas5)) . "\n");
    }
  }

  echo "<script>window.location.href = 'index.php?page=history&sensor=" . $id . "';</script>";
  exit;
}

// BLOCO 4: Gera a tabela de histórico
function gerarTabela($id, $tipo, $icon) {
  $tipo_pasta = strtolower($tipo);
  $log_path = __DIR__ . "/../api/files/{$tipo_pasta}/{$id}/log.txt";

  if (!file_exists($log_path)) {
    return "<tr><td colspan='5'>⚠️ Ficheiro não encontrado: {$tipo_pasta}/{$id}/log.txt</td></tr>";
  }

  $linhas = array_reverse(file($log_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
  $html = '';

  foreach ($linhas as $linha) {
    $html .= processLinha($linha, $id, $tipo, $icon);
  }

  return $html;
}

// BLOCO 5: Processa cada linha do ficheiro de log
function processLinha($linha, $id, $tipo, $icon) {
  $partes = explode(';', $linha);
  if (count($partes) !== 2) return '';

  [$hora, $valor] = array_map('trim', $partes);

  $label = getLabel($id);
  $estado = getEstado($id, $valor);
  $badge = getBadge($estado);

  return "<tr>
    <td><div class='d-flex align-items-center gap-3'>
      <img src='assets/sensors/{$icon}' alt='{$id}' width='32'>
      <span>" . ucfirst($id) . "</span>
    </div></td>
    <td>{$tipo}</td>
    <td>{$valor} {$label}</td>
    <td>{$hora}</td>
    <td><span class='{$badge}'>{$estado}</span></td>
  </tr>";
}

function getLabel($id) {
  if ($id === 'temperatura') return '°C';
  if ($id === 'humidade') return '%';
  return '';
}

function getEstado($id, $valor) {
  switch ($id) {
    case 'temperatura':
      return getEstadoTemperatura($valor);
    case 'humidade':
      return getEstadoHumidade($valor);
    case 'uv':
      return getEstadoUv($valor);
    case 'led':
      return getEstadoLed($valor);
    case 'servo':
      return getEstadoServo($valor);
    case 'buzzer':
      return getEstadoBuzzer($valor);
    default:
      return '';
  }
}

function getEstadoTemperatura($valor) {
  if ($valor > 30) return 'Elevado';
  if ($valor > 25) return 'Normal';
  return 'Baixo';
}

function getEstadoHumidade($valor) {
  if ($valor > 50) return 'Humido';
  if ($valor > 30) return 'Moderado';
  return 'Seco';
}

function getEstadoUv($valor) {
  if ($valor > 7) return 'Elevado';
  if ($valor > 4) return 'Moderado';
  return 'Baixo';
}

function getEstadoLed($valor) {
  $v = strtolower(trim($valor));
  if (in_array($v, ['verde', 'amarelo', 'vermelho'])) return ucfirst($v);
  return ($v == 'on' || $v == 'ativo') ? 'Ativo' : 'Inativo';
}

function getEstadoServo($valor) {
  return ($valor == 'Proteção ativa' ? 'Ativo' : 'Inativo');
}

function getEstadoBuzzer($valor) {
  return ($valor == 'Alerta ativo' ? 'Ativo' : 'Inativo');
}
/*
A função getEstado() e suas auxiliares (getEstadoTemperatura, getEstadoHumidade, etc) são responsáveis por determinar o "estado" de cada valor de sensor/atuador, retornando uma string descritiva baseada no valor lido. O funcionamento é equivalente ao código anterior: cada tipo de dispositivo tem sua própria lógica para determinar o estado, e o resultado é usado para exibir o badge correto na tabela de histórico. Portanto, sim, faz a mesma coisa.
*/

function getBadge($estado) {
  $estado = strtolower($estado);

  if ($estado === 'vermelho') return 'badge bg-danger-subtle text-danger-emphasis';
  if ($estado === 'amarelo') return 'badge bg-warning-subtle text-warning-emphasis';
  if ($estado === 'verde') return 'badge bg-success-subtle text-success-emphasis';

  if (in_array($estado, ['elevado', 'humido', 'inativo']))
    return 'badge bg-danger-subtle text-danger-emphasis';
  if (in_array($estado, ['normal', 'moderado', 'ativo']))
    return 'badge bg-success-subtle text-success-emphasis';
  if (in_array($estado, ['baixo', 'seco']))
    return 'badge bg-primary-subtle text-primary-emphasis';

  return '';
}
?>

<!-- BLOCO VISUAL -->
<div class="history-page">
  <div class="container my-5">
    <h3 class="fw-bold mb-5">Histórico de sensores e atuadores</h3>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
      <?php $i = 0; foreach ($dispositivos as $id => $d): ?>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?= $i === 0 ? 'active' : '' ?>" id="tab-<?= $id ?>"
            data-bs-toggle="tab" data-bs-target="#pane-<?= $id ?>"
            type="button" role="tab" onclick="openHistoryTab('<?= $id ?>')">
            <?= ucfirst($id) ?>
          </button>
        </li>
      <?php $i++; endforeach ?>
    </ul>

    <div class="tab-content">
      <?php $i = 0; foreach ($dispositivos as $id => $d): ?>
        <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>" id="pane-<?= $id ?>" role="tabpanel">
          <!-- Botão LIMPAR alinhado à esquerda -->
          <div class="d-flex justify-content-start mb-3">
            <a href="index.php?page=history&sensor=<?= $id ?>&limpar=1" class="btn btn-outline-danger">
              🧹 Limpar log (manter últimos 5)
            </a>
          </div>

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

<script>
document.addEventListener("DOMContentLoaded", function () {
  const selectedSensor = "<?php echo $selectedSensor; ?>";
  const tabs = document.querySelectorAll(".nav-link");

  tabs.forEach((tab) => {
    if (tab.textContent.trim().toLowerCase() === selectedSensor.toLowerCase()) {
      tab.click();
    }
  });
});
</script>
