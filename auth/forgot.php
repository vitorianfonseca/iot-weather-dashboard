<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Cabeçalho da página:
         - Define codificação como UTF-8
         - Torna a página responsiva em dispositivos móveis
         - Define o título da aba
         - Liga o ficheiro de estilos CSS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../styles/login.css">
</head>

<body>
    <div class="container">
        <!-- Estrutura principal da página com duas secções lado a lado -->

        <div class="left"></div>
        <!-- Lado esquerdo vazio — usado geralmente para estilo ou imagem de fundo -->

        <div class="right">
            <!-- Lado direito com o conteúdo principal -->

            <h1>Welcome to SkyCast</h1>

            <div class="form-wrapper">
                <!-- Bloco que apresenta a mensagem de recuperação de password -->

                <p class="subtitle"><b>Forgot your password?</b></p>
                <p style="margin-bottom: 20px;">
                    <!-- Instruções para contactar o suporte -->
                    Please contact the system administrator to reset your password or send an email to 
                    <a href="mailto:support@skycast.com" style="color: #6a9cff;">support@skycast.com</a>.
                </p>

                <!-- Link para voltar à página de login -->
                <a href="login.php" style="color: #6a9cff; text-decoration: none;">Back to login</a>

                <!-- Logótipo institucional colocado no fim da secção -->
                <div class="logo" style="margin-top: 40px;">
                    <img src="../assets/login/ipl.svg" alt="Politécnico de Leiria">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
