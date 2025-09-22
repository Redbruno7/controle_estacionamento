<?php
require_once "../../src/config.php";

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner = $_POST["owner"];
    $plate = $_POST["plate"];
    $model = $_POST["model"];
    $color = $_POST["color"];

    $stmt = $conn->prepare("INSERT INTO vehicles (owner_name, plate, model, color) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $owner, $plate, $model, $color);
    $stmt->execute();

    header("Location: list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Veículo</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro de Veículo</h2>

        <nav>
            <a href="../index.php">Início</a>
            <a href="list.php">Veículos</a>
            <a href="../entries/list.php">Entradas</a>
            <a href="../logout.php">Sair</a>
        </nav>

        <form method="post">
            <label for="owner">Nome do Proprietário:</label>
            <input type="text" id="owner" name="owner" required>

            <label for="plate">Placa:</label>
            <input type="text" id="plate" name="plate" required>

            <label for="model">Modelo:</label>
            <input type="text" id="model" name="model">

            <label for="color">Cor:</label>
            <input type="text" id="color" name="color">

            <button type="submit">Salvar Veículo</button>
        </form>
    </div>
</body>
</html>
