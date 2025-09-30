<?php
require_once __DIR__ . "/../../src/config.php";
date_default_timezone_set('America/Sao_Paulo');

if (empty($_SESSION["logado"]) || $_SESSION["logado"] !== true) {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header("Location: list.php");
    exit();
}

// Buscar veículo existente
$stmt = $conn->prepare("SELECT * FROM vehicles WHERE vehicle_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();
$stmt->close();

if (!$vehicle) {
    header("Location: list.php?msg=not_found");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $owner = trim($_POST['owner'] ?? '');
    $plate  = strtoupper(trim($_POST['plate'] ?? ''));
    $model  = trim($_POST['model'] ?? '');
    $color  = trim($_POST['color'] ?? '');

    // Campos obrigatórios
    if ($owner === '' || $plate === '') {
        header("Location: edit.php?id={$id}&msg=campos_vazios");
        exit();
    }

    // Verificar se já existe outra placa
    $check = $conn->prepare("SELECT 1 FROM vehicles WHERE plate = ? AND vehicle_id <> ?");
    $check->bind_param("si", $plate, $id);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close();
        header("Location: edit.php?id={$id}&msg=placa");
        exit();
    }
    $check->close();

    // Atualizar veículo
    $upd = $conn->prepare("UPDATE vehicles SET owner_name = ?, plate = ?, model = ?, color = ? WHERE vehicle_id = ?");
    $upd->bind_param("ssssi", $owner, $plate, $model, $color, $id);
    if (!$upd->execute()) {
        $upd->close();
        header("Location: edit.php?id={$id}&msg=erro_update");
        exit();
    }
    $upd->close();

    header("Location: list.php?msg=sucesso_edit");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Veículo</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
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
            <label for="owner">Nome do Proprietário:</label>
            <input type="text" id="owner" name="owner" value="<?= htmlspecialchars($vehicle['owner_name']) ?>" placeholder="Digite o nome do proprietário" required>

            <label for="plate">Placa:</label>
            <input type="text" id="plate" name="plate" value="<?= htmlspecialchars($vehicle['plate']) ?>" placeholder="Ex: AAA1234" required>

            <label for="model">Modelo:</label>
            <input type="text" id="model" name="model" value="<?= htmlspecialchars($vehicle['model']) ?>" placeholder="Digite o modelo do carro" required>

            <label for="color">Cor:</label>
            <input type="text" id="color" name="color" value="<?= htmlspecialchars($vehicle['color']) ?>" placeholder="Digite a cor do carro" required>

            <button type="submit">Salvar Veículo</button>
        </form>

        <?php include "../footer.php"; ?>
    </main>

    <script src="../../assets/js/script.js"></script>
</body>
</html>