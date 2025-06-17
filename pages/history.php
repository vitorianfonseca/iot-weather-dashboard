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

// BLOCO 3: Gera a tabela de histórico para um dispositivo específico
function gerarTabela($id, $tipo, $icon) {
  $tipo_pasta = strtolower($tipo); // 'Sensor' ou 'Atuador' → sensor ou atuador
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


// BLOCO 4: Processa cada linha do ficheiro de log para gerar uma linha da tabela
function processLinha($linha, $id, $tipo, $icon) {
  $partes = explode(';', $linha);
  if (count($partes) !== 2) return '';

  [$hora, $valor] = array_map('trim', $partes);

  $label = getLabel($id);          // Ex: °C ou %
  $estado = getEstado($id, $valor); // Interpretação do valor
  $badge = getBadge($estado);      // Cor/estilo do estado

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

// BLOCO 5: Retorna a unidade para o tipo de sensor (ex: °C, %)
function getLabel($id) {
  if ($id === 'temperatura') return '°C';
  if ($id === 'humidade') return '%';
  return '';
}

// BLOCO 6: Determina o estado descritivo com base no valor
function getEstado($id, $valor) {
  if ($id === 'temperatura') {
    return ($valor > 40 ? 'Elevado' : ($valor > 20 ? 'Normal' : 'Baixo'));
  } elseif ($id === 'humidade') {
    return ($valor > 50 ? 'Humido' : ($valor > 30 ? 'Moderado' : 'Seco'));
  } elseif ($id === 'uv') {
    return ($valor > 7 ? 'Elevado' : ($valor > 4 ? 'Moderado' : 'Baixo'));
  } elseif ($id === 'led') {
    return ($valor == 'Ativo' ? 'Ativo' : 'Inativo');
  } elseif ($id === 'servo') {
    return ($valor == 'Proteção ativa' ? 'Ativo' : 'Inativo');
  } elseif ($id === 'buzzer') {
    return ($valor == 'Alerta ativo' ? 'Ativo' : 'Inativo');
  }
  return '';
}

// BLOCO 7: Aplica estilos Bootstrap com base no estado
function getBadge($estado) {
  if (in_array($estado, ['Elevado', 'Humido', 'Inativo'])) {
    return 'badge bg-danger-subtle text-danger-emphasis';
  } elseif (in_array($estado, ['Normal', 'Moderado', 'Ativo'])) {
    return 'badge bg-success-subtle text-success-emphasis';
  } elseif (in_array($estado, ['Baixo', 'Seco'])) {
    return 'badge bg-primary-subtle text-primary-emphasis';
  }
  return '';
}
?>

<!-- BLOCO 8: Estrutura visual da página -->
<div class="history-page">
  <div class="container my-5">
    <h3 class="fw-bold mb-5">Histórico de sensores e atuadores</h3>

    <!-- BLOCO 9: Navegação por tabs (um botão por dispositivo) -->
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

    <!-- BLOCO 10: Conteúdo de cada tab (tabelas) -->
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
<!-- BLOCO 11: Script para ativar automaticamente a tab do sensor selecionado via GET -->
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
