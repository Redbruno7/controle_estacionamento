<?php
require_once __DIR__ . "/../src/config.php";

if (empty($_SESSION["logado"]) || $_SESSION["logado"] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema de Estacionamento</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
    <header class="container">
        <h2>Bem-vindo ao Sistema</h2>
        <nav>
            <a href="vehicles/list.php"><i class="bi bi-car-front"></i> Veículos</a>
            <a href="entries/list.php"><i class="bi bi-journal-check"></i> Entradas</a>
            <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>

        <?php include "footer.php"; ?>
    </header>
</body>
</html>