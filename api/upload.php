<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['imagem'])) {
        $temp = $_FILES['imagem']['tmp_name'];
        $destino = '../api/images/webcam.jpg'; // caminho relativo ao upload.php
        if (move_uploaded_file($temp, $destino)) {
            echo "✅ Imagem recebida!";
        } else {
            echo "❌ Erro ao mover imagem.";
        }
    } else {
        echo "❌ Ficheiro imagem não recebido.";
    }
} else {
    echo "❌ Método inválido.";
}
?>
