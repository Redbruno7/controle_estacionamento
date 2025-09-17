<?php
require_once "../../src/config.php";

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM vehicles ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Veículos</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <h2>Lista de Veículos</h2>
    <a href="add.php">Adicionar Veículo</a> | 
    <a href="../index.php">Início</a>
    <br><br>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Dono</th>
            <th>Placa</th>
            <th>Modelo</th>
            <th>Cor</th>
            <th>Ações</th>
        </tr>

        <?php while($row = $result->fetch_assoc()) { ?>
            
        <tr>
            <td><?= $row["vehicle_id"] ?></td>
            <td><?= $row["owner_name"] ?></td>
            <td><?= $row["plate"] ?></td>
            <td><?= $row["model"] ?></td>
            <td><?= $row["color"] ?></td>
            <td>
                <a href="edit.php?id=<?= $row['vehicle_id'] ?>">Editar</a> | 
                <a href="delete.php?id=<?= $row['vehicle_id'] ?>" onclick="return confirm('Excluir veículo?')">Excluir</a> | 
                <a href="../entries/list.php?vehicle_id=<?= $row['vehicle_id'] ?>">Entradas</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
