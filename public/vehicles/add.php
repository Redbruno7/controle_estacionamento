<?php
require_once __DIR__ . "/../../src/config.php";

if (empty($_SESSION["logado"]) || $_SESSION["logado"] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner = trim($_POST["owner"]);
    $plate = strtoupper(trim($_POST["plate"]));
    $model = trim($_POST["model"]);
    $color = trim($_POST["color"]);

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
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
    <main class="container">
        <h2>Cadastro de Veículo</h2>

        <nav>
            <a href="../index.php"><i class="bi bi-house"></i> Início</a>
            <a href="list.php"><i class="bi bi-car-front"></i> Veículos</a>
            <a href="../entries/list.php"><i class="bi bi-journal-check"></i> Entradas</a>
            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>

        <form method="post">
            <label for="owner">Nome do Proprietário:</label>
            <input type="text" id="owner" name="owner" placeholder="Digite o nome do proprietário" required>

            <label for="plate">Placa:</label>
            <input type="text" id="plate" name="plate" placeholder="Ex: AAA1234" required>

            <label for="model">Modelo:</label>
            <input type="text" id="model" name="model" placeholder="Digite o modelo do carro" required>

            <label for="color">Cor:</label>
            <input type="text" id="color" name="color" placeholder="Digite a cor do carro" required>

            <button type="submit">Salvar Veículo</button>
        </form>

        <?php include "../footer.php"; ?>
    </main>
</body>
</html>
