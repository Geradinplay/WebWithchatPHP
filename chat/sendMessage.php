<?php
require '../connect.php';
session_start();

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit;//остановка от выполнения дальнейшего кода
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $message = trim($_POST['message']);
    $userId = $_SESSION['id'];

    $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $message);

    if ($stmt->execute()) {
        echo "OK";
    } else {
        http_response_code(500);
        echo "DB Error";
    }

    $stmt->close();
    exit;
} else {
    http_response_code(400);
    echo "Invalid request";
    exit;
}
