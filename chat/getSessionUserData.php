<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

echo json_encode([
    'id' => $_SESSION['id'],
    'role' => $_SESSION['role'] //admin или user
]);