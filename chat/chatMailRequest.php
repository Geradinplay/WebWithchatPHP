<?php
require '../connect.php';

$sql = "SELECT messages.id AS message_id, messages.message, messages.sent_at, users.id AS user_id, users.name AS user_name, users.role_id AS role_id 
FROM messages
JOIN users ON messages.user_id = users.id
ORDER BY messages.sent_at ASC";

$result = $conn->query($sql);

$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

header('Content-Type: application/json');
echo json_encode($messages);
?>