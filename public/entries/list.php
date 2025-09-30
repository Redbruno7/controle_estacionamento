<?php
require_once __DIR__ . "/../../src/config.php";

if (empty($_SESSION["logado"]) || $_SESSION["logado"] !== true) {
    header("Location: login.php");
    exit();
}

$vehicle_id = isset($_GET["vehicle_id"]) ? (int) $_GET["vehicle_id"] : null;

if ($vehicle_id) {
    // Busca entradas do veículo específico
    $stmt = $conn->prepare("SELECT e.*, v.plate, v.owner_name
                            FROM parking_entries e
                            JOIN vehicles v ON e.vehicle_id = v.vehicle_id
                            WHERE e.vehicle_id=? 
                            ORDER BY e.entry_time DESC");
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Busca info do veículo
    $stmtInfo = $conn->prepare("SELECT plate, owner_name FROM vehicles WHERE vehicle_id=?");
    $stmtInfo->bind_param("i", $vehicle_id);
    $stmtInfo->execute();
    $vehicleInfo = $stmtInfo->get_result()->fetch_assoc();
} else {
    // Busca todas as entradas
    $result = $conn->query("SELECT e.*, v.plate, v.owner_name 
                            FROM parking_entries e
                            JOIN vehicles v ON e.vehicle_id = v.vehicle_id
                            ORDER BY e.entry_time DESC");
    $vehicleInfo = null;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entradas</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
    <main class="container">
        <?php if ($vehicle_id && $vehicleInfo): ?>
            <h2>Entradas de <?= htmlspecialchars($vehicleInfo['plate']) ?> - <?= htmlspecialchars($vehicleInfo['owner_name']) ?></h2>
        <?php else: ?>
            <h2>Lista de Entradas</h2>
        <?php endif; ?>

        <nav>
            <a href="../index.php"><i class="bi bi-house"></i> Início</a>
            <a href="add.php<?= $vehicle_id ? "?vehicle_id=$vehicle_id" : "" ?>"><i class="bi bi-plus-circle"></i> Registrar Entrada</a>
            <a href="../vehicles/list.php"><i class="bi bi-car-front"></i> Veículos</a>
            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>

        <table>
            <thead>
                <tr>
                    <th>Veículo</th>
                    <th>Entrada</th>
                    <th>Saída</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row["plate"] ?> - <?= $row["owner_name"] ?></td>
                            <td><?= date("d/m/Y H:i", strtotime($row["entry_time"])) ?></td>
                            <td><?= $row["exit_time"] ? date("d/m/Y H:i", strtotime($row["exit_time"])) : "⏳ Em aberto" ?></td>
                            <td><?= $row["price"] > 0 ? "R$ " . number_format($row["price"], 2, ",", ".") : "-" ?></td>
                            
                            <td>
                                <?php if (!$row["exit_time"]) { ?>
                                    <a href="edit.php?id=<?= $row['entry_id'] ?>"><i class="bi bi-box-arrow-left"></i> Registrar Saída</a>
                                <?php } ?>
                                
                                <a href="delete.php?id=<?= $row['entry_id'] ?>" class="table-link-danger">
                                    <i class="bi bi-trash"></i> Excluir
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center">Nenhuma entrada encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php include "../footer.php"; ?>
    </main>
</body>
</html>