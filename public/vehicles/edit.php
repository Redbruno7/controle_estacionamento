<?php
require_once "../../src/config.php";

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
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
    $owner = $_POST["owner"];
    $plate = $_POST["plate"];
    $model = $_POST["model"];
    $color = $_POST["color"];

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
</head>
<body>
    <div class="container">
        <h2>Editar Veículo</h2>

        <nav>
            <a href="../index.php">Início</a>
            <a href="list.php">Lista de Veículos</a>
            <a href="../entries/list.php">Gerenciar Entradas</a>
            <a href="../logout.php">Sair</a>
        </nav>

        <form method="post">
            <label for="owner">Dono</label>
            <input type="text" id="owner" name="owner" value="<?= $vehicle['owner_name'] ?>" required>

            <label for="plate">Placa</label>
            <input type="text" id="plate" name="plate" value="<?= $vehicle['plate'] ?>" required>

            <label for="model">Modelo</label>
            <input type="text" id="model" name="model" value="<?= $vehicle['model'] ?>">

            <label for="color">Cor</label>
            <input type="text" id="color" name="color" value="<?= $vehicle['color'] ?>">

            <button type="submit" class="btn">Salvar</button>
        </form>
    </div>
</body>
</html>
