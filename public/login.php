<?php
require_once "../src/config.php";

// Usuário fixo
$usuario = "admin";
$senha = "1234";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["usuario"] === $usuario && $_POST["senha"] === $senha) {
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

<body>
    <h2>Login</h2>
    
    <?php if(isset($erro)) echo "<p style='color:red'>$erro</p>"; ?>

    <form method="post">
        <input type="text" name="usuario" placeholder="Usuário" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
