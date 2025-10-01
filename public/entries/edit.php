<?php
require_once __DIR__ . "/../../src/config.php";
date_default_timezone_set('America/Sao_Paulo');

if (empty($_SESSION["logado"]) || $_SESSION["logado"] !== true) {
    header("Location: login.php");
    exit();
}

$id = $_GET["id"] ?? null;

if (!$id) {
    echo "<script>alert('Nenhum ID informado.'); window.location.href='list.php';</script>";
    exit();
}

// Confirmação
if (isset($_GET["confirm"]) && $_GET["confirm"] === "yes") {
    // Atualiza saída
    $stmt = $conn->prepare("SELECT entry_time, exit_time FROM parking_entries WHERE entry_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        echo "<script>alert('Registro não encontrado.'); window.location.href='list.php';</script>";
        exit();
    }

    $entry = $result->fetch_assoc();

    if ($entry["exit_time"]) {
        echo "<script>alert('Saída já registrada.'); window.location.href='list.php';</script>";
        exit();
    }

    // Calcula preço
    $entry_ts = strtotime($entry["entry_time"]);
    $exit_ts  = time();
    $diff_minutes = ceil(($exit_ts - $entry_ts) / 60);
    $blocks = ceil($diff_minutes / 15);
    $price  = $blocks * 3.50;
    $exit_time_db = date("Y-m-d H:i:s", $exit_ts);

    $stmt = $conn->prepare("UPDATE parking_entries SET exit_time=?, price=? WHERE entry_id=?");
    $stmt->bind_param("sdi", $exit_time_db, $price, $id);
    $stmt->execute();

    echo "<script>window.location.href='list.php';</script>";
    exit();
}

// Se ainda não confirmou, mostra popup
echo "<script>
if (confirm('Deseja registrar a saída deste veículo?')) {
    window.location.href = 'edit.php?id={$id}&confirm=yes';
} else {
    window.location.href = 'list.php';
}
</script>";