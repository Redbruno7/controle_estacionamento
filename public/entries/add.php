<?php
require_once "../../src/config.php";
date_default_timezone_set('America/Sao_Paulo');

$selected_vehicle_id = isset($_GET["vehicle_id"]) ? (int)$_GET["vehicle_id"] : null;

// Verifica login
if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit();
}

// Definir total de vagas
$total_vagas = 30;

// Contar entradas sem saída (ocupadas)
$stmt = $conn->query("SELECT COUNT(*) AS ocupadas FROM parking_entries WHERE exit_time IS NULL");
$ocupadas = $stmt->fetch_assoc()['ocupadas'];
$livres = $total_vagas - $ocupadas;

// Processar envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($livres <= 0) {
        echo "<script>alert('Estacionamento lotado!'); window.history.back();</script>";
        exit();
    }

    $vehicle_id = $_POST["vehicle_id"];
    $entry_time = date("Y-m-d H:i:s");

    // Verificar se já existe entrada aberta para o veículo
    $check = $conn->prepare("SELECT 1 FROM parking_entries WHERE vehicle_id = ? AND exit_time IS NULL");
    $check->bind_param("i", $vehicle_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Este veículo já está estacionado e não pode registrar nova entrada.'); window.history.back();</script>";
        exit();
    }

    // Inserir entrada automática
    $stmt = $conn->prepare("INSERT INTO parking_entries (vehicle_id, entry_time) VALUES (?, ?)");
    $stmt->bind_param("is", $vehicle_id, $entry_time);
    $stmt->execute();

    header("Location: list.php");
    exit();
}

// Buscar veículos
$vehicles = $conn->query("SELECT * FROM vehicles ORDER BY plate ASC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Entrada</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        /* Quadro de vagas */
        .vagas-container {
            width: 100%;
            height: 25px;
            background: #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin: 15px 0;
            display: flex;
        }
        .vagas-ocupadas {
            background: #b00020; /* vermelho */
            height: 100%;
        }
        .vagas-livres {
            background: #0a3a70; /* azul */
            height: 100%;
        }
        .lotado-msg {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
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

    <!-- Quadro de vagas -->
    <div class="vagas-container">
        <div class="vagas-ocupadas" style="width: <?= ($ocupadas/$total_vagas)*100 ?>%"></div>
        <div class="vagas-livres" style="width: <?= ($livres/$total_vagas)*100 ?>%"></div>
    </div>
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
        <?php if($livres <= 0): ?>
            <p class="lotado-msg">Estacionamento lotado!</p>
        <?php endif; ?>
    </form>
</div>
</body>
</html>