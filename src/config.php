<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "controle_estacionamento";

$conn = new mysqli($host, $user, $pass, $db, 3309);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

session_start();
