<?php
require_once "../../src/config.php";

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET["id"] ?? null;

if (!$id) {
    echo "<script>alert('Nenhum ID informado.'); window.location.href='list.php';</script>";
    exit();
}

// Confirmar exclusão
if (isset($_GET["confirm"]) && $_GET["confirm"] === "yes") {
    $stmt = $conn->prepare("DELETE FROM vehicles WHERE vehicle_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo "<script>alert('Veículo excluído com sucesso!'); window.location.href='list.php';</script>";
    exit();
}

// Se ainda não confirmou, mostra popup
echo "<script>
if (confirm('Deseja realmente excluir este veículo?')) {
    window.location.href = 'delete.php?id={$id}&confirm=yes';
} else {
    window.location.href = 'list.php';
}
</script>";
