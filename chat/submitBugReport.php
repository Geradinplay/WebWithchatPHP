<?php
require '../connect.php';
session_start();

if (!isset($_SESSION['id']) || !isset($_POST['comment'], $_POST['bug_types'])) {
    header("Location: reportBug.php");
    exit;
}

$userId = (int)$_SESSION['id'];
$comment = trim($_POST['comment']);
$bugTypes = $_POST['bug_types'];

if ($comment === '' || !is_array($bugTypes) || count($bugTypes) === 0) {
    header("Location: reportBug.php?error=1");
    exit;
}

// Вставка комментария в bug_report
$stmt = $conn->prepare("INSERT INTO bug_report (comment) VALUES (?)");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $comment);
$stmt->execute();
$reportId = $stmt->insert_id;
$stmt->close();

// Вставка записей в bug_report_type
$stmtType = $conn->prepare("INSERT INTO bug_report_type (bug_report_id, bug_type_id, user_id) VALUES (?, ?, ?)");
if (!$stmtType) {
    die("Prepare failed: " . $conn->error);
}

foreach ($bugTypes as $bugTypeId) {
    $bugTypeId = (int)$bugTypeId;
    $stmtType->bind_param("iii", $reportId, $bugTypeId, $userId);
    $stmtType->execute();
}
$stmtType->close();


header("Location: reportBug.php?success=1");
exit;
?>
