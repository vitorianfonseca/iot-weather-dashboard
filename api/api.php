<?php
// Define o tipo de conteúdo da resposta como HTML com codificação UTF-8
header('Content-Type: text/html; charset=utf-8');

// Verifica se a requisição foi feita por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Verifica se os dados esperados foram enviados no POST
    if (isset($_POST['sensor'], $_POST['valor'], $_POST['hora'])) {
        $sensor = $_POST['sensor']; // Nome do sensor enviado no POST
        $valor = $_POST['valor'];   // Valor medido pelo sensor
        $hora = $_POST['hora'];     // Hora da medição

        // Define os caminhos dos ficheiros onde os dados serão armazenados
        $dir = __DIR__ . "/files/$sensor"; // Diretório específico do sensor
        $ficheiro_valor = "$dir/valor.txt"; // Ficheiro que guarda o último valor
        $ficheiro_hora = "$dir/hora.txt";   // Ficheiro que guarda a última hora
        $ficheiro_log = "$dir/log.txt";     // Ficheiro que guarda o histórico (log)

        // Cria o diretório do sensor se ainda não existir
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true); // Cria com permissões totais e diretórios recursivos
        }

        // Verifica se o diretório é gravável antes de tentar escrever nos ficheiros
        if (is_writable($dir)) {
            // Guarda o valor e a hora nos respetivos ficheiros (substitui o conteúdo anterior)
            file_put_contents($ficheiro_valor, $valor);
            file_put_contents($ficheiro_hora, $hora);

            // Adiciona uma entrada no ficheiro de log com o formato "hora;valor"
            $linha_log = "$hora;$valor" . PHP_EOL;
            file_put_contents($ficheiro_log, $linha_log, FILE_APPEND); // Acrescenta no final
        } else {
            // Caso o diretório não possa ser escrito, imprime uma mensagem de erro
            echo "Diretório não tem permissões de escrita.";
        }
    } else {
        // Caso algum parâmetro esteja em falta, imprime uma mensagem de erro
        echo "Parâmetros incompletos.";
    }
} else {
    // Rejeita requisições que não sejam do tipo POST
    echo "Método não permitido.";
}
