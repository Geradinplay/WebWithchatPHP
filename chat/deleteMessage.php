<?php
require '../connect.php';
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $userId = $_SESSION['id'];
    $role = $_SESSION['role'];

    if ($role === 'admin') {
        $sql = "DELETE FROM messages WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
    } else {
        $sql = "DELETE FROM messages WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $userId);
    }

    if ($stmt->execute()) {
        echo "OK";
    } else {
        http_response_code(500);
        echo "DB error: " . $stmt->error;
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo "Invalid request";
}
