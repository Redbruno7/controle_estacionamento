<?php
require_once "../../src/config.php";

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit();
}

// Captura o termo de busca, se houver
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    // Prepared statement para buscar por dono, placa ou modelo
    $stmt = $conn->prepare("SELECT * FROM vehicles 
                            WHERE owner_name LIKE ? 
                               OR plate LIKE ? 
                               OR model LIKE ? 
                            ORDER BY vehicle_id ASC");
    $like = "%$search%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM vehicles ORDER BY vehicle_id ASC");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Veículos</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <main class="container">
        <h2>Lista de Veículos</h2>

        <nav>
            <a href="../index.php">Início</a>
            <a href="add.php">Adicionar Veículo</a>
            <a href="../entries/list.php">Gerenciar Entradas</a>
            <a href="../logout.php">Sair</a>
        </nav>

        <!-- Formulário de busca -->
        <form method="get" class="search-form">
            <input type="text" name="search" placeholder="Buscar veículo..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Buscar</button>
            <?php if($search): ?>
                <a href="list.php" class="clear-btn">Limpar</a>
            <?php endif; ?>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Dono</th>
                    <th>Placa</th>
                    <th>Modelo</th>
                    <th>Cor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row["owner_name"] ?></td>
                    <td><?= $row["plate"] ?></td>
                    <td><?= $row["model"] ?></td>
                    <td><?= $row["color"] ?></td>
                    <td>
                        <a href="../entries/list.php?vehicle_id=<?= $row['vehicle_id'] ?>">Entradas</a>
                        <a href="edit.php?id=<?= $row['vehicle_id'] ?>">Editar</a>
                        <a href="delete.php?id=<?= $row['vehicle_id'] ?>" class="table-link-danger">
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
