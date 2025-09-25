<?php
require_once "../../src/config.php";

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit();
}

$vehicle_id = isset($_GET["vehicle_id"]) ? (int) $_GET["vehicle_id"] : null;

if ($vehicle_id) {
    $stmt = $conn->prepare("SELECT e.*, v.plate, v.owner_name
                            FROM parking_entries e
                            JOIN vehicles v ON e.vehicle_id = v.vehicle_id
                            WHERE e.vehicle_id=? 
                            ORDER BY e.entry_time DESC");
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query(
        "SELECT e.*, v.plate, v.owner_name 
        FROM parking_entries e
        JOIN vehicles v ON e.vehicle_id = v.vehicle_id
    ");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entradas</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <div class="container">
        <h2><?= $vehicle_id ? "Entradas do Veículo #$vehicle_id" : "Lista de Entradas" ?></h2>

        <nav>
            <a href="../index.php">Início</a>
            <a href="add.php<?= $vehicle_id ? "?vehicle_id=$vehicle_id" : "" ?>">Registrar Entrada</a>
            <a href="../vehicles/list.php">Gerenciar Veículos</a>
            <a href="../logout.php">Sair</a>
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
                <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row["plate"] ?> - <?= $row["owner_name"] ?></td>

                        <td><?= date("d-m-Y H:i", strtotime($row["entry_time"])) ?></td>

                        <td>
                            <?= $row["exit_time"] ? date("d-m-Y H:i", strtotime($row["exit_time"])) : "⏳ Em aberto" ?>
                        </td>

                        <td>
                            <?= $row["price"] > 0 ? "R$ " . number_format($row["price"], 2, ",", ".") : "-" ?>
                        </td>

                        <td>
                            <?php if (!$row["exit_time"]) { ?>
                                <a href="edit.php?id=<?= $row['entry_id'] ?>">Registrar Saída</a>
                            <?php } ?>
                            <a href="delete.php?id=<?= $row['entry_id'] ?>" class="table-link-danger">
                                Excluir
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>