<?php
require '../connect.php';
session_start();
if (!isset($_SESSION['login'])) {
    header("location: ./index.php");
    exit;
}

$type = isset($_GET["type"]) ? $_GET["type"] : "all";

$edited = [];
$deleted = [];
$normal = [];

//Получаем обычные сообщения(type=all)
if ($type === "all") {
    $sql = "SELECT m.id, u.name, u.login, m.message, m.sent_at 
            FROM messages m 
            JOIN users u ON m.user_id = u.id 
            ORDER BY m.sent_at DESC";
    $result = $conn->query($sql);
    if ($result) {
        $normal = $result->fetch_all(MYSQLI_ASSOC);
    }
}

//Получаем изменённые и удалённые(edited, deleted, combined)
if ($type === "edited" || $type === "combined") {
    $sql = "SELECT e.original_message_id, u.name, u.login, e.old_message, e.new_message, e.sent_at, e.edited_at 
            FROM edited_messages e 
            JOIN users u ON e.user_id = u.id 
            ORDER BY e.edited_at DESC";
    $result = $conn->query($sql);
    if ($result) {
        $edited = $result->fetch_all(MYSQLI_ASSOC);
    }
}

if ($type === "deleted" || $type === "combined") {
    $sql = "SELECT d.original_message_id, u.name, u.login, d.message, d.sent_at, d.deleted_at 
            FROM deleted_messages d 
            JOIN users u ON d.user_id = u.id 
            ORDER BY d.deleted_at DESC";
    $result = $conn->query($sql);
    if ($result) {
        $deleted = $result->fetch_all(MYSQLI_ASSOC);
    }
}

//Формируем текст для скачивания
function buildText($normal, $edited, $deleted, $type) {
    $text = "";
    if ($type === "all") {
        $text .= "=== CHAT MESSAGES ===\n";
        foreach ($normal as $msg) {
            $text .= "[{$msg['sent_at']}] {$msg['name']} ({$msg['login']}): {$msg['message']}\n";
        }
    }
    if ($type === "edited" || $type === "combined") {
        $text .= "\n=== EDITED MESSAGES ===\n";
        foreach ($edited as $e) {
            $text .= "[{$e['edited_at']}] {$e['name']} ({$e['login']}): {$e['old_message']} → {$e['new_message']}\n";
        }
    }
    if ($type === "deleted" || $type === "combined") {
        $text .= "\n=== DELETED MESSAGES ===\n";
        foreach ($deleted as $d) {
            $text .= "[{$d['deleted_at']}] {$d['name']} ({$d['login']}): {$d['message']} → [deleted]\n";
        }
    }
    return $text;
}

//Сохраняем лог
if (isset($_GET["save"])) {
    if (!is_dir("./report")) {
        mkdir("./report", 0777, true);
    }
    $filename = "./report/report_" . date("Y-m-d_H-i-s") . ".txt";
    $text = buildText($normal, $edited, $deleted, $type);
    file_put_contents($filename, $text);
    $savedFile = $filename;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat Log Report</title>
    <link rel="stylesheet" href="./style/header.css">
    <link rel="stylesheet" href="./style/headerPhone.css">
    <link rel="stylesheet" href="./style/footer.css">
    <link rel="stylesheet" href="./style/body.css">
    <link rel="stylesheet" href="./style/chatBody.css">
</head>
<body>
<header>
    <h1>EASY CHAT REPORT</h1>
    <div class="top-nav">
        <h2><a href="../index.php">Back</a></h2>
    </div>
</header>

<main>
    <h2 class="h2-indents">Report View Mode</h2>

    <div class="rules-block-wrapper">
        <a class="go-to-chat-btn" href="?type=all">📋 All</a>
        <a class="go-to-chat-btn" href="?type=edited">✏️ Edited</a>
        <a class="go-to-chat-btn" href="?type=deleted">🗑️ Deleted</a>
        <a class="go-to-chat-btn" href="?type=combined">🔀 Combined</a>
    </div>

    <section class="chat-container">
        <div class="chat-box">
            <?php
            if ($type === "all") {
                foreach ($normal as $msg) {
                    echo '<div class="chat-message other">';
                    echo '<div class="msg-header"><span class="sender">'.htmlspecialchars($msg["name"]).'</span></div>';
                    echo '<p class="msg-text">'.htmlspecialchars($msg["message"]).'</p>';
                    echo '<span class="timestamp">Sent: '.$msg["sent_at"].'</span>';
                    echo '</div>';
                }
            }
            if ($type === "edited" || $type === "combined") {
                foreach ($edited as $msg) {
                    echo '<div class="chat-message other">';
                    echo '<div class="msg-header"><span class="sender">'.htmlspecialchars($msg["name"]).'</span></div>';
                    echo '<p class="msg-text"><strong>Old:</strong> '.htmlspecialchars($msg["old_message"]).'<br><strong>New:</strong> '.htmlspecialchars($msg["new_message"]).'</p>';
                    echo '<span class="timestamp">Edited: '.$msg["edited_at"].'</span>';
                    echo '</div>';
                }
            }
            if ($type === "deleted" || $type === "combined") {
                foreach ($deleted as $msg) {
                    echo '<div class="chat-message other">';
                    echo '<div class="msg-header"><span class="sender">'.htmlspecialchars($msg["name"]).'</span></div>';
                    echo '<p class="msg-text"><strong>Old:</strong> '.htmlspecialchars($msg["message"]).'<br><strong>New:</strong> [deleted]</p>';
                    echo '<span class="timestamp">Deleted: '.$msg["deleted_at"].'</span>';
                    echo '</div>';
                }
            }
            ?>
        </div>

        <div class="chat-input-wrapper" style="justify-content: center; margin-top: 20px;">
            <a class="send-btn" href="?type=<?php echo htmlspecialchars($type); ?>&save=true">💾 Download report</a>
        </div>
    </section>

    <?php
    if (isset($savedFile)) {
        echo '<div class="rules-block-wrapper">';
        echo '<p>✔️ File saved: <a href="'.$savedFile.'" download>Download</a></p>';
        echo '</div>';
    }
    ?>
</main>

<footer>
    <p>© 2025 Easy Chat. All rights reserved.</p>
</footer>
</body>
</html>
