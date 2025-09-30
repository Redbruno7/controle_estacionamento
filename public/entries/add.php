<?php
require_once __DIR__ . "/../../src/config.php";
date_default_timezone_set('America/Sao_Paulo');

if (empty($_SESSION["logado"]) || $_SESSION["logado"] !== true) {
    header("Location: ../login.php");
    exit();
}

$selected_vehicle_id = isset($_GET["vehicle_id"]) ? (int)$_GET["vehicle_id"] : null;
$total_vagas = 30;

// Contar vagas ocupadas
$stmt = $conn->query("SELECT COUNT(*) AS ocupadas FROM parking_entries WHERE exit_time IS NULL");
$ocupadas = $stmt->fetch_assoc()['ocupadas'];
$livres = $total_vagas - $ocupadas;

$alertMessage = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($livres <= 0) {
        $alertMessage = "Estacionamento lotado!";
    } elseif (!isset($_POST["vehicle_id"]) || !is_numeric($_POST["vehicle_id"])) {
        $alertMessage = "Veículo inválido.";
    } else {
        $vehicle_id = (int)$_POST["vehicle_id"];
        $entry_time = date("Y-m-d H:i:s");

        $check = $conn->prepare("SELECT 1 FROM parking_entries WHERE vehicle_id = ? AND exit_time IS NULL");
        $check->bind_param("i", $vehicle_id);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $alertMessage = "Este veículo já está estacionado e não pode registrar nova entrada.";
        } else {
            $stmt = $conn->prepare("INSERT INTO parking_entries (vehicle_id, entry_time) VALUES (?, ?)");
            $stmt->bind_param("is", $vehicle_id, $entry_time);
            $stmt->execute();

            header("Location: list.php");
            exit();
        }
    }
}

$vehicles = $conn->query("SELECT * FROM vehicles ORDER BY plate ASC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Entrada</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
    <main class="container">
        <h2>Registrar Entrada</h2>

        <nav>
            <a href="../index.php"><i class="bi bi-house"></i> Início</a>
            <a href="../vehicles/list.php"><i class="bi bi-car-front"></i> Veículos</a>
            <a href="list.php"><i class="bi bi-journal-check"></i> Entradas</a>
            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>

        <section class="vagas-container">
            <article class="vagas-ocupadas" style="width: <?= ($ocupadas/$total_vagas)*100 ?>%"></article>
            <article class="vagas-livres" style="width: <?= ($livres/$total_vagas)*100 ?>%"></article>
        </section>
        <p><?= $livres ?> vagas livres de <?= $total_vagas ?></p>

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

            <label for="entry_time">Data/Hora de Entrada:</label>
            <input type="text" id="entry_time" value="<?= date("d/m/Y H:i") ?>" disabled>

            <button type="submit" <?= $livres <= 0 ? "disabled" : "" ?>>Salvar</button>
        </form>

        <?php if (!empty($alertMessage)) : ?>
            <p class="alert"><?= $alertMessage ?></p>
        <?php endif; ?>

        <?php include "../footer.php"; ?>
    </main>

    <script src="../../assets/js/script.js"></script>
</body>
</html>