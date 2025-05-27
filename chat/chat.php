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

            <!-- Чужое сообщение -->
<!--            <div class="chat-message other" data-id="1">-->
<!--                <div class="msg-header">-->
<!--                    <span class="sender">Alice</span>-->
<!--                    <span class="msg-actions">-->
<!--            <button>edit</button>-->
<!--            <button>del</button>-->
<!--          </span>-->
<!--                </div>-->
<!--                <p class="msg-text">Hello, how are you?</p>-->
<!--                <span class="timestamp">14:35</span>-->
<!--            </div>-->

            <!-- Твоё сообщение -->
<!--            <div class="chat-message self" data-id="2">-->
<!--                <div class="msg-header">-->
<!--                    <span class="sender">You</span>-->
<!--                    <span class="msg-actions">-->
<!--            <button>edit</button>-->
<!--            <button>del</button>-->
<!--          </span>-->
<!--                </div>-->
<!--                <p class="msg-text">Fine, thanks!</p>-->
<!--                <span class="timestamp">14:36</span>-->
<!--            </div>-->


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
</body>
<script src="ajaxChatRequests.js"></script>
</html>

