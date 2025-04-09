<?php
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sensor'], $_POST['valor'], $_POST['hora'])) {
        $sensor = $_POST['sensor'];
        $valor = $_POST['valor'];
        $hora = $_POST['hora'];

        // Caminhos para ficheiros
        $dir = __DIR__ . "/files/$sensor";
        $ficheiro_valor = "$dir/valor.txt";
        $ficheiro_hora = "$dir/hora.txt";
        $ficheiro_log = "$dir/log.txt";

        // Verifica se a pasta existe
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true); // Cria o diretório se não existir
        }

        // Verifica se os arquivos podem ser gravados
        if (is_writable($dir)) {
            // Guarda os dados
            file_put_contents($ficheiro_valor, $valor);
            file_put_contents($ficheiro_hora, $hora);

            // Acrescenta ao log.txt com quebra de linha
            $linha_log = "$hora;$valor" . PHP_EOL;
            file_put_contents($ficheiro_log, $linha_log, FILE_APPEND);

            // Resposta
            echo "Dados gravados com sucesso!";
        } else {
            http_response_code(403); // Proibido
            echo "Erro: Não foi possível gravar os dados.";
        }
    } else {
        http_response_code(400); // Bad request
        echo "Erro: parâmetros POST em falta.";
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['sensor'])) {
        $sensor = $_GET['sensor'];
        $ficheiro = __DIR__ . "/files/$sensor/valor.txt";

        if (file_exists($ficheiro)) {
            echo file_get_contents($ficheiro);
        } else {
            http_response_code(404); // Recurso não encontrado
            echo "Erro: ficheiro \"$ficheiro\" não encontrado.";
        }
    } else {
        http_response_code(400); // Bad request
        echo "Erro: parâmetro GET 'sensor' em falta.";
    }
}
else {
    http_response_code(405); // Método não permitido
    echo "Erro: apenas são suportados os métodos POST e GET.";
}
