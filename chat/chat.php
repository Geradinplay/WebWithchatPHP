<?php
require '../connect.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../authorizationPages/login.php");
    exit;
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Easy Chat — chat easily</title>
    <link rel="stylesheet" href="./style/header.css">
    <link rel="stylesheet" href="./style/headerPhone.css">
    <link rel="stylesheet" href="./style/body.css">
    <link rel="stylesheet" href="./style/footer.css">
    <link rel="stylesheet" href="./style/chatBody.css">
</head>
<body>
<header>
    <h1>EASY CHAT</h1>
    <div class="top-nav">
        <h2>
            <a href="../index.php" >Back</a>
        </h2>
    </div>
</header>

<main>
    <div class="chat-container">
        <div class="chat-box" id="chat-box" data-userid="<?php echo $_SESSION['id']; ?>">


<!--      Сообщения      -->


        </div>

        <form id="message-form" class="chat-input-wrapper">
            <input id="message" type="text" name="message" placeholder="Type your message..." class="chat-input" required />
            <button type="submit" class="send-btn">Send</button>
        </form>
    </div>
</main>

<footer>
    <p>© 2025 Easy Chat. All rights reserved.</p>
</footer>

<div id="edit-popup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
     background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 999;">
    <div style="background: white; padding: 20px; border-radius: 8px; width: 300px;">
        <h3>Edit Message</h3>
        <input type="text" id="edit-popup-input" style="width: 100%; margin-bottom: 10px;">
        <div style="display: flex; justify-content: space-between;">
            <button id="edit-popup-back">Back</button>
            <button id="edit-popup-send">Send</button>
        </div>
    </div>
</div>

</body>
<script src="ajaxChatRequests.js"></script>
</html>

