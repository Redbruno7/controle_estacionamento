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

// Buscar entrada
$stmt = $conn->prepare("SELECT * FROM parking_entries WHERE entry_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$entry = $stmt->get_result()->fetch_assoc();

if (!$entry) {
    echo "Entrada não encontrada!";
    exit();
}

// Atualizar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entry_time = $_POST["entry_time"];
    $exit_time  = $_POST["exit_time"] ?: null;
    $price      = $_POST["price"];

    $stmt = $conn->prepare("UPDATE parking_entries SET entry_time=?, exit_time=?, price=? WHERE entry_id=?");
    $stmt->bind_param("ssdi", $entry_time, $exit_time, $price, $id);
    $stmt->execute();

    header("Location: list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Entrada</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

    <div class="container">
        <h2>Editar Entrada</h2>

        <nav>
            <a href="../index.php">Início</a>
            <a href="../vehicles/list.php">Veículos</a>
            <a href="list.php">Entradas</a>
            <a href="../logout.php">Sair</a>
        </nav>

        <form method="post">
            <label for="entry_time">Entrada:</label>
            <input type="datetime-local" id="entry_time" name="entry_time" value="<?= str_replace(' ', 'T', $entry['entry_time']) ?>" required>

            <label for="exit_time">Saída:</label>
            <input type="datetime-local" id="exit_time" name="exit_time" value="<?= $entry['exit_time'] ? str_replace(' ', 'T', $entry['exit_time']) : '' ?>">

            <label for="price">Preço:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?= $entry['price'] ?>">

            <button type="submit">Salvar</button>
        </form>
    </div>
</body>
</html>
