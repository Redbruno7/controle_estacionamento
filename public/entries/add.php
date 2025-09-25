<?php
require_once "../../src/config.php";

$selected_vehicle_id = isset($_GET["vehicle_id"]) ? (int)$_GET["vehicle_id"] : null;

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_id = $_POST["vehicle_id"];
    $entry_time = date("Y-m-d H:i:s");

    // Verificar se já existe entrada em aberto
    $check = $conn->prepare("SELECT 1 FROM parking_entries WHERE vehicle_id = ? AND exit_time IS NULL");
    $check->bind_param("i", $vehicle_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Este veículo já está estacionado e não pode registrar nova entrada.'); window.history.back();</script>";
        exit();
    }

    // Salvar entrada automática
    $stmt = $conn->prepare("INSERT INTO parking_entries (vehicle_id, entry_time) VALUES (?, ?)");
    $stmt->bind_param("is", $vehicle_id, $entry_time);
    $stmt->execute();

    header("Location: list.php");
    exit();
}

$vehicles = $conn->query("SELECT * FROM vehicles ORDER BY plate ASC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Entrada</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Entrada</h2>

        <nav>
            <a href="../index.php">Início</a>
            <a href="../vehicles/list.php">Veículos</a>
            <a href="list.php">Entradas</a>
            <a href="../logout.php">Sair</a>
        </nav>
        
        <form method="post">
            <label for="vehicle_id">Veículo:</label>
            <select id="vehicle_id" name="vehicle_id" required>
                <?php while($v = $vehicles->fetch_assoc()) { ?>
                    <option value="<?= $v['vehicle_id'] ?>" 
                        <?= $selected_vehicle_id == $v['vehicle_id'] ? "selected" : "" ?>>
                        <?= $v['plate'] ?> - <?= $v['owner_name'] ?>
                    </option>
                <?php } ?>
            </select>

            <p><strong>Data/Hora de Entrada:</strong> <?= date("d/m/Y H:i") ?></p>

            <button type="submit">Salvar</button>
        </form>
    </div>
</body>
</html>
