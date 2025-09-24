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

$stmt = $conn->prepare("SELECT * FROM parking_entries WHERE entry_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$entry = $stmt->get_result()->fetch_assoc();

if (!$entry) {
    echo "Entrada não encontrada!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entry_time = $_POST["entry_time"];
    $exit_time  = $_POST["exit_time"] ?: null;
    $price      = 0;

    // Validação - saída não pode ser antes da entrada
    if ($exit_time && $exit_time < $entry_time) {
        echo "<script>alert('⚠ A saída não pode ser anterior à entrada!'); window.history.back();</script>";
        exit();
    }

    // Se houver saída - calcular preço automaticamente
    if ($exit_time) {
        $entry_timestamp = strtotime($entry_time);
        $exit_timestamp  = strtotime($exit_time);

        $diff_seconds = $exit_timestamp - $entry_timestamp;
        $diff_minutes = ceil($diff_seconds / 60); // arredonda para cima

        // Cada 15 minutos = R$3,50
        $blocks = ceil($diff_minutes / 15);
        $price  = $blocks * 3.50;
    }

    $stmt = $conn->prepare("UPDATE parking_entries SET entry_time=?, exit_time=?, price=? WHERE entry_id=?");
    $stmt->bind_param("ssdi", $entry_time, $exit_time, $price, $id);
    $stmt->execute();

    echo "<script>alert('✅ Registro atualizado com sucesso!'); window.location.href='list.php';</script>";
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

            <label for="price">Preço (calculado automaticamente):</label>
            <input type="text" id="price" value="<?= $entry['price'] > 0 ? "R$ " . number_format($entry['price'],2,",",".") : "-" ?>" disabled>

            <button type="submit">Salvar</button>
        </form>
    </div>
</body>
</html>
