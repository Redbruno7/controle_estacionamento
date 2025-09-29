<?php
require_once __DIR__ . "/../../src/config.php";

if (empty($_SESSION["logado"]) || $_SESSION["logado"] !== true) {
    header("Location: login.php");
    exit();
}

// Captura o termo de busca
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    // Busca por dono, placa ou modelo
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

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
    <main class="container">
        <h2>Lista de Veículos</h2>

        <nav>
            <a href="../index.php"><i class="bi bi-house"></i> Início</a>
            <a href="add.php"><i class="bi bi-plus-circle"></i> Adicionar Veículo</a>
            <a href="../entries/list.php"><i class="bi bi-journal-check"></i> Entradas</a>
            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>

        <!-- Formulário de busca -->
        <form method="get" class="search-form">
            <input type="text" name="search" placeholder="Buscar veículo..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Buscar</button>
            <?php if($search): ?>
                <a href="list.php" class="clear-btn">Limpar</a>
            <?php endif; ?>
        </form>

        <!-- Tabela -->
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
                        <a href="../entries/list.php?vehicle_id=<?= $row['vehicle_id'] ?>" title="Entradas">
                            <i class="bi bi-box-arrow-in-right"></i> Entradas
                        </a>
                        <a href="edit.php?id=<?= $row['vehicle_id'] ?>" title="Editar">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="delete.php?id=<?= $row['vehicle_id'] ?>" class="table-link-danger btn-delete" data-owner="<?= htmlspecialchars($row['owner_name'], ENT_QUOTES) ?>">
                            <i class="bi bi-trash"></i> Excluir
                        </a>
                    </td>
                </tr>
                <?php } ?>

                <?php if($result->num_rows === 0): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">Nenhum veículo encontrado</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php include "../footer.php"; ?>
    </main>

    <script src="../../assets/js/script.js"></script>
</body>
</html>