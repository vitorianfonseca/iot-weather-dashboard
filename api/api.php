<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Europe/Lisbon"); // Aplica-se a tudo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ----- 🟦 Bloco: Sensor -----
    if (isset($_POST['sensor'], $_POST['valor'])) {
        $sensor = $_POST['sensor'];
        $valor = $_POST['valor'];

        $sensores_validos = ['temperatura', 'humidade', 'uv'];
        if (!in_array($sensor, $sensores_validos)) {
            echo "Sensor inválido.";
            exit;
        }

        $hora = date('Y-m-d H:i:s');
        $dir = __DIR__ . "/files/sensor/$sensor";
        $ficheiro_valor = "$dir/valor.txt";
        $ficheiro_hora = "$dir/hora.txt";
        $ficheiro_log = "$dir/log.txt";

        if (!is_dir($dir)) mkdir($dir, 0777, true);

        if (is_writable($dir)) {
            file_put_contents($ficheiro_valor, $valor);
            file_put_contents($ficheiro_hora, $hora);
            file_put_contents($ficheiro_log, "$hora;$valor\n", FILE_APPEND);
        } else {
            echo "Diretório não tem permissões de escrita.";
        }

        // 🟢 Atualiza automaticamente o servo com base na humidade
        if ($sensor === 'humidade') {
            $humidade_num = floatval($valor); // valor em %
            $angulo = intval($humidade_num * 1.8); // escala 0-100 → 0-180°

            $dir_servo = __DIR__ . "/files/atuador/servo";
            if (!is_dir($dir_servo)) mkdir($dir_servo, 0777, true);

            file_put_contents("$dir_servo/valor.txt", $angulo);
            file_put_contents("$dir_servo/hora.txt", $hora);
            file_put_contents("$dir_servo/log.txt", "$hora;$angulo\n", FILE_APPEND);
        }

        // 🔔 Ativa o buzzer automaticamente se o UV estiver elevado
        if ($sensor === 'uv') {
            $uv_num = floatval($valor);
            $estado_buzzer = ($uv_num > 8) ? "ativo" : "inativo";
            $dir_buzzer = __DIR__ . "/files/atuador/buzzer";
            if (!is_dir($dir_buzzer)) mkdir($dir_buzzer, 0777, true);
            file_put_contents("$dir_buzzer/valor.txt", $estado_buzzer);
            file_put_contents("$dir_buzzer/hora.txt", $hora);
            file_put_contents("$dir_buzzer/log.txt", "$hora;$estado_buzzer\n", FILE_APPEND);
        }

    }

    // ----- 🟩 Bloco: Atuador -----
    elseif (isset($_POST['atuador'], $_POST['valor'])) {
        $atuador = $_POST['atuador'];
        $valor = $_POST['valor'];

        $atuadores_validos = ['led', 'buzzer', 'servo'];
        if (!in_array($atuador, $atuadores_validos)) {
            echo "Dispositivo inválido.";
            exit;
        }

        $hora = date('Y-m-d H:i:s');
        $dir = __DIR__ . "/files/atuador/$atuador";
        $ficheiro_valor = "$dir/valor.txt";
        $ficheiro_hora = "$dir/hora.txt";
        $ficheiro_log = "$dir/log.txt";

        if (!is_dir($dir)) mkdir($dir, 0777, true);

        if (is_writable($dir)) {
            file_put_contents($ficheiro_valor, $valor);
            file_put_contents($ficheiro_hora, $hora);
            file_put_contents($ficheiro_log, "$hora;$valor\n", FILE_APPEND);
            echo "Estado do atuador $atuador atualizado com sucesso.";
        } else {
            echo "Diretório não tem permissões de escrita.";
        }

    }

    elseif (isset($_POST['comando']) && $_POST['comando'] === 'captura') {
        $dir = __DIR__ . "/files/control";
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $ficheiro_comando = "$dir/comando.txt";
        $hora = date('Y-m-d H:i:s');
        file_put_contents($ficheiro_comando, "captura\n$hora\n");
        echo "Comando de captura registado.";
    }
    

    // ----- 🔴 Caso nenhum dos dois tipos foi passado -----
    else {
        echo "Parâmetros incompletos.";
    }

}
// ----- 🔵 Requisição GET -----
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // 🟦 Sensor
    if (isset($_GET['sensor'])) {
        $sensor = $_GET['sensor'];
        $dir = __DIR__ . "/files/sensor/$sensor";
        $ficheiro_valor = "$dir/valor.txt";
        $ficheiro_hora = "$dir/hora.txt";

        if (file_exists($ficheiro_valor) && file_exists($ficheiro_hora)) {
            $valor = trim(file_get_contents($ficheiro_valor));
            echo $valor;
        } else {
            http_response_code(404);
            echo "Erro: ficheiros não encontrados para o sensor '$sensor'.";
        }

    // 🟩 Atuador
    } elseif (isset($_GET['atuador'])) {
        $atuador = $_GET['atuador'];
        $dir = __DIR__ . "/files/atuador/$atuador";
        $ficheiro_valor = "$dir/valor.txt";
        $ficheiro_hora = "$dir/hora.txt";

        if (file_exists($ficheiro_valor) && file_exists($ficheiro_hora)) {
            $valor = trim(file_get_contents($ficheiro_valor));
            echo $valor;
        } else {
            http_response_code(404);
            echo "Erro: ficheiros não encontrados para o atuador '$atuador'.";
        }

    } else {
        http_response_code(400);
        echo "Erro: parâmetro GET 'sensor' ou 'atuador' em falta.";
    }
}
// ----- ❌ Outros métodos HTTP -----
else {
    echo "Método não permitido.";
}
?>
