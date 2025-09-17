<?php
require_once "src/config.php";

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
    <h2>Bem-vindo ao Sistema</h2>
    <a href="vehicles/list.php">Gerenciar Veículos</a> | 
    <a href="logout.php">Sair</a>
</body>
</html>
