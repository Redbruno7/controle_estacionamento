<?php
require_once "../src/config.php";

// Usuário fixo
$usuario = "admin";
$senha = "1234";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioPost = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $senhaPost = $_POST['senha'];

    if ($usuarioPost === $usuario && $senhaPost === $senha) {
        $_SESSION["logado"] = true;
        header("Location: index.php");
        exit();
    } else {
        $erro = "Usuário ou senha inválidos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="login-page">
    <header class="login-container">
        <h2>Login</h2>

        <?php if($erro): ?>
            <p class="erro"><?= $erro ?></p>
        <?php endif; ?>

        <form method="post" autocomplete="off">
            <label for="usuario">Usuário</label>
            <input type="text" id="usuario" name="usuario" placeholder="Digite seu usuário" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>

            <button type="submit">Entrar</button>
        </form>
    </header>
</body>
</html>