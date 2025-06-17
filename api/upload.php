<?php
$target_dir = __DIR__ . "/../uploads/";
if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["imagem"])) {
    $temp = $_FILES["imagem"]["tmp_name"];
    $target = $target_dir . "ultima.jpg";

    if (move_uploaded_file($temp, $target)) {
        echo "Imagem recebida com sucesso.";
    } else {
        echo "Erro ao mover imagem.";
    }
} else {
    echo "Ficheiro de imagem não recebido.";
}
?>
