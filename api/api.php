<?php
// Define o tipo de conteúdo da resposta como HTML com codificação UTF-8
header('Content-Type: text/html; charset=utf-8');

// Verifica se a requisição foi feita por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Verifica se os dados esperados foram enviados no POST
    if (isset($_POST['sensor'], $_POST['valor'])) {
        $sensor = $_POST['sensor']; // Nome do sensor enviado no POST
        $valor = $_POST['valor'];   // Valor medido pelo sensor

        // ✅ Validação de sensores permitidos
    $sensores_validos = ['temperatura', 'humidade', 'uv'];
    if (!in_array($sensor, $sensores_validos)) {
        echo "Sensor inválido.";
        exit;
    }
    
        // 🕒 Gera a hora local diretamente no servidor
        date_default_timezone_set("Europe/Lisbon"); // Define fuso horário de Lisboa
        $hora = date('Y-m-d H:i:s'); // Formato "2025-06-02 22:05:00"

        // Define os caminhos dos ficheiros onde os dados serão armazenados
        $dir = __DIR__ . "/files/$sensor"; // Diretório específico do sensor
        $ficheiro_valor = "$dir/valor.txt"; // Guarda o último valor
        $ficheiro_hora = "$dir/hora.txt";   // Guarda a última hora
        $ficheiro_log = "$dir/log.txt";     // Histórico (log)

        // Cria o diretório do sensor se ainda não existir
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true); // Permissões totais e diretórios recursivos
        }

        // Verifica se o diretório é gravável antes de escrever nos ficheiros
        if (is_writable($dir)) {
            // Guarda o valor e a hora atual do servidor
            file_put_contents($ficheiro_valor, $valor);
            file_put_contents($ficheiro_hora, $hora);

            // Regista no log com o formato "hora;valor"
            $linha_log = "$hora;$valor" . PHP_EOL;
            file_put_contents($ficheiro_log, $linha_log, FILE_APPEND);
        } else {
            echo "Diretório não tem permissões de escrita.";
        }
 // Trata comandos de atuadores (ex: LED, buzzer, servo)
} else if (isset($_POST['dispositivo'], $_POST['estado'])) {
    $dispositivo = $_POST['dispositivo'];
    $estado = $_POST['estado'];

    $atuadores_validos = ['led', 'buzzer', 'servo'];
    if (!in_array($dispositivo, $atuadores_validos)) {
        echo "Dispositivo inválido.";
        exit;
    }

    $dir = __DIR__ . "/files/$dispositivo";
    $ficheiro_estado = "$dir/estado.txt";
    $ficheiro_log = "$dir/log.txt";

    // Cria diretório se não existir
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // Grava o estado atual
    file_put_contents($ficheiro_estado, $estado);

    // Regista no log com hora
    date_default_timezone_set("Europe/Lisbon");
    $hora = date('Y-m-d H:i:s');
    $linha_log = "$hora;$estado" . PHP_EOL;
    file_put_contents($ficheiro_log, $linha_log, FILE_APPEND);

    echo "Comando para $dispositivo guardado com sucesso.";
} else {
    echo "Parâmetros incompletos.";
}
    



} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Responde com o valor atual se o parâmetro 'sensor' for fornecido
    if (isset($_GET['sensor'])) {
        $sensor = $_GET['sensor'];
        $dir = __DIR__ . "/files/$sensor";
        $ficheiro_valor = "$dir/valor.txt";
        $ficheiro_hora = "$dir/hora.txt";

        if (file_exists($ficheiro_valor) && file_exists($ficheiro_hora)) {
            $valor = trim(file_get_contents($ficheiro_valor));
            $hora = trim(file_get_contents($ficheiro_hora));

            echo $valor;
        } else {
            http_response_code(404);
            echo "Erro: ficheiros não encontrados para o sensor '$sensor'.";
        }
    } else {
        http_response_code(400);
        echo "Erro: parâmetro GET 'sensor' em falta.";
    }

} else {
    // Rejeita requisições que não sejam do tipo POST ou GET
    echo "Método não permitido.";
}
