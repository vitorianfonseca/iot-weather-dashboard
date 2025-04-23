<?php
session_start(); // Inicia a sessão — obrigatório antes de qualquer saída HTML

// BLOCO 1: Verifica se o utilizador já está autenticado
if (isset($_SESSION['username'])) {
    header("Location: ../index.php"); // Redireciona para o index se já estiver autenticado
    exit();
}

// BLOCO 2: Carregamento dos utilizadores e inicialização do estado de erro
$users = require __DIR__ . '/users.php'; // Carrega a lista de utilizadores (com hashes de passwords)
$error = null;

// BLOCO 3: Processamento do formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verificação das credenciais
    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['username'] = $username;

        // BLOCO 4: Lógica "Remember me" com cookies
        if (isset($_POST['remember'])) {
            setcookie("remembered_user", $username, time() + (86400 * 30), "/"); // Guarda por 30 dias
        } else {
            setcookie("remembered_user", "", time() - 3600, "/"); // Apaga cookie se desmarcado
        }

        // Redireciona para a página principal
        header("Location: ../index.php");
        exit();
    } else {
        $error = "Credenciais inválidas. Tenta novamente.";
    }
}
?>


<!-- BLOCO 5: Estrutura HTML da página de login -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags, título e CSS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/other/favicon.svg">
    <title>SkyCast</title>
    <link rel="stylesheet" href="../styles/login.css">

    <!-- BLOCO 6: Script para alternar visibilidade da password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.src = '../assets/login/eye.svg'; // Mostra password
            } else {
                passwordInput.type = 'password';
                icon.src = '../assets/login/hidden.svg'; // Esconde password
            }
        }
    </script>
</head>

<body>
    <!-- BLOCO 7: Estrutura visual da página -->
    <div class="container">
        <div class="left"></div> <!-- Secção visual/esquerda -->

        <div class="right">
            <h1>Welcome to SkyCast</h1>

            <div class="form-wrapper">
                <p class="subtitle"><b>Nice to see you again</b></p>

                <!-- BLOCO 8: Mensagem de erro (caso existam credenciais erradas) -->
                <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

                <!-- BLOCO 9: Formulário de login -->
                <form method="POST">
                    <label for="username">Username</label>
                    <input type="text"
                           id="username"
                           name="username"
                           placeholder="Enter username"
                           autocomplete="username"
                           class="input"
                           required
                           value="<?php echo $_COOKIE['remembered_user'] ?? ''; ?>" >

                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password"
                               id="password"
                               name="password"
                               autocomplete="current-password"
                               placeholder="Enter password"
                               class="input"
                               required>
                        <span class="eye-icon" onclick="togglePassword()">
                            <img id="eye-icon" src="../assets/login/hidden.svg" alt="Toggle password visibility">
                        </span>
                    </div>

                    <!-- BLOCO 10: Opções abaixo do formulário -->
                    <div class="options">
                        <div class="remember-me">
                            <label class="toggle-remember">
                                <input type="checkbox"
                                       id="remember-me"
                                       name="remember"
                                       <?php if (isset($_COOKIE['remembered_user'])) echo 'checked'; ?>>
                                <span class="slider"></span>
                                <span class="label-text">Remember me</span>
                            </label>
                        </div>
                        <a href="forgot.php" class="forgot">Forgot password?</a>
                    </div>

                    <!-- BLOCO 11: Botão de login e logótipo -->
                    <button class="sign-in" type="submit">Sign in</button>
                    <div class="logo">
                        <img src="../assets/login/ipl.svg" alt="Politécnico de Leiria">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
