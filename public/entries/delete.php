<?php
require_once "../../src/config.php";

if (!isset($_SESSION["logado"])) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET["id"] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM parking_entries WHERE entry_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: list.php");
exit();
