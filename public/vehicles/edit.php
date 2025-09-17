<?php
require_once "../../src/config.php";

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO vehicles (owner_name, plate, model, color) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_POST["owner"], $_POST["plate"], $_POST["model"], $_POST["color"]);
    $stmt->execute();
    header("Location: list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head><meta charset="UTF-8"><title>Adicionar Veículo</title></head>
<body>
    <h2>Novo Veículo</h2>
    <form method="post">
        Dono: <input type="text" name="owner" required><br>
        Placa: <input type="text" name="plate" required><br>
        Modelo: <input type="text" name="model"><br>
        Cor: <input type="text" name="color"><br>
        <button type="submit">Salvar</button>
    </form>
    <a href="list.php">Voltar</a>
</body>
</html>
