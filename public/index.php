<?php
require_once __DIR__ . "/../src/config.php";

if (!isset($_SESSION["logado"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Estacionamento</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Bem-vindo ao Sistema</h2>

        <nav>
            <a href="vehicles/list.php">Gerenciar Veículos</a>
            <a href="entries/list.php">Gerenciar Entradas</a>
            <a href="logout.php">Sair</a>
        </nav>
    </div>
</body>
</html>
