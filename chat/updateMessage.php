<?php
require '../connect.php';
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['message'])) {
    $id = (int)$_POST['id'];
    $message = trim($_POST['message']);
    $userId = $_SESSION['id'];
    $role = $_SESSION['role'];
    if ($role == 'admin') {
        // Админ может редактировать всё
        $sql = "UPDATE messages SET message = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $message, $id);
    } else {
        // Пользователь — только свои сообщения
        $sql = "UPDATE messages SET message = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $message, $id, $userId);
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
