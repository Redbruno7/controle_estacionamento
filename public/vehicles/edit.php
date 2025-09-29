<?php
require_once __DIR__ . "/../../src/config.php";

if (empty($_SESSION["logado"]) || $_SESSION["logado"] !== true) {
    header("Location: login.php");
    exit();
}

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: list.php");
    exit();
}

// Buscar dados do veículo
$stmt = $conn->prepare("SELECT * FROM vehicles WHERE vehicle_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();

if (!$vehicle) {
    echo "Veículo não encontrado!";
    exit();
}

// Atualizar veículo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner = trim($_POST["owner"]);
    $plate = strtoupper(trim($_POST["plate"]));
    $model = trim($_POST["model"]);
    $color = trim($_POST["color"]);

    $stmt = $conn->prepare("UPDATE vehicles SET owner_name=?, plate=?, model=?, color=? WHERE vehicle_id=?");
    $stmt->bind_param("ssssi", $owner, $plate, $model, $color, $id);
    $stmt->execute();

    header("Location: list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Veículo</title>
    <link rel="stylesheet" href="../../assets/css/style.css">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
    <main class="container">
        <h2>Editar Veículo</h2>

        <nav>
            <a href="../index.php"><i class="bi bi-house"></i> Início</a>
            <a href="list.php"><i class="bi bi-car-front"></i> Veículos</a>
            <a href="../entries/list.php"><i class="bi bi-journal-check"></i> Entradas</a>
            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>

        <form method="post">
            <label for="owner">Dono</label>
            <input type="text" id="owner" name="owner" value="<?= $vehicle['owner_name'] ?>" placeholder="Digite o nome do proprietário" required>

            <label for="plate">Placa</label>
            <input type="text" id="plate" name="plate" value="<?= $vehicle['plate'] ?>" placeholder="Ex: AAA1234" required>

            <label for="model">Modelo</label>
            <input type="text" id="model" name="model" value="<?= $vehicle['model'] ?>" placeholder="Digite o modelo do carro" required>

            <label for="color">Cor</label>
            <input type="text" id="color" name="color" value="<?= $vehicle['color'] ?>" placeholder="Digite a cor do carro" required>

            <button type="submit" class="btn">Salvar</button>
        </form>

        <?php include "../footer.php"; ?>
    </main>
</body>
</html>
