<?php
session_start(); // Deve ser a primeira linha
$users = [
    "admin" => '$2y$12$k41R6J5PRdSnCv8ZGAL/8Ofy.RNVzzUDnLrHQgom016VfwGUyLMCK', // admin123
    "user" => '$2y$12$MDJgsVEcn9hRtM2CzC/RpeOZqJBwOY8Lo.TZ204Wf3QVuXaFM3Sz2'  // exemplo: user123
];

if (
    isset($_POST['username'], $_POST['password']) &&
    isset($users[$_POST['username']]) &&
    password_verify($_POST['password'], $users[$_POST['username']])
) {
    $_SESSION["username"] = $_POST['username'];

    if (isset($_POST['remember'])) {
        setcookie("remembered_user", $_POST['username'], time() + (86400 * 30), "/"); // 30 dias
    } else {
        setcookie("remembered_user", "", time() - 3600, "/");
    }

    header('Location: ../index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/login.css">
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.src = '../assets/login/eye.svg'; // Olho fechado
            } else {
                passwordInput.type = 'password';
                icon.src = '../assets/login/hidden.svg'; // Olho aberto
            }
        }
    </script>
</head>
<body>
    <div class="login">
        <div class="container">
            <div class="left"></div>
            <div class="right">
                <h1>Welcome to SkyCast</h1>
                <div class="form-wrapper">
                    <p class="subtitle"><b>Nice to see you again</b></p>
                    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
                    <form method="POST" action="">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter username" autocomplete="username" class="input" required  value="<?php echo $_COOKIE['remembered_user'] ?? ''; ?>" />

                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" autocomplete="current-password" placeholder="Enter password" class="input" required />
                            <span class="eye-icon" onclick="togglePassword()">
                                <img id="eye-icon" src="../assets/login/hidden.svg" alt="Toggle password visibility">
                            </span>
                        </div>

                        <div class="options">
                            <label class="remember-me">
                                <label class="toggle-remember">
                                    <input type="checkbox" id="remember-me" name="remember" <?php if (isset($_COOKIE['remembered_user'])) echo 'checked'; ?>>
                                    <span class="slider"></span>
                                    <span class="label-text">Remember me</span>
                                </label>
                            </label>
                            <a href="forgot.php" class="forgot">Forgot password?</a>
                        </div>

                        <button class="sign-in" type="submit">Sign in</button>
                        <div class="logo">
                            <img src="../assets/login/ipl.svg" alt="Politécnico de Leiria">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>