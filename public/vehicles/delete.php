<?php
require_once __DIR__ . "/../../src/config.php";

if (empty($_SESSION["logado"]) || $_SESSION["logado"] !== true) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : null;

if (!$id) {
    header("Location: list.php");
    exit();
}

// Verifica se o veículo existe
$stmt = $conn->prepare("SELECT vehicle_id FROM vehicles WHERE vehicle_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: list.php");
    exit();
}

// Executa exclusão
$stmt = $conn->prepare("DELETE FROM vehicles WHERE vehicle_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Redireciona para a lista
header("Location: list.php");
exit();