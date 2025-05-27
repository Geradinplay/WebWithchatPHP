<?php
session_start();
if(isset($_SESSION["login"])){
    header("location: ./chat/guestRoom.php");
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
</head>
<body>
<header>
    <h1>EASY CHAT</h1>
    <div class="top-nav">
        <h2>
            <a href="./authorizationPages/logIn.php">Log in</a>
            <a href="./authorizationPages/signUp.php">Sign up</a>
        </h2>
    </div>
</header>

<main>
    <section class="hero">
        <img src="./img/chats-online.png" alt="Online chat illustration">
        <h2>Welcome to Easy Chat!</h2>
        <p>Our platform offers a modern and intuitive way to communicate in real time. Whether you want to chat with friends, discuss a project, or simply have fun — Easy Chat is perfect for everyone!</p>
    </section>

    <section class="features">
        <h3>Why choose us?</h3>
        <ul>
            <li>⚡ Fast messaging with no delays</li>
            <li>🔒 Security and privacy come first</li>
            <li>🎨 Simple and user-friendly interface</li>
            <li>📱 Accessible from any device — phone, tablet or PC</li>
            <li>🌐 Multilingual support</li>
        </ul>
    </section>

    <section class="testimonials">
        <h3>User testimonials</h3>
        <blockquote>
            “The best chat I’ve ever used! Super convenient and fast.” — Anna, designer
        </blockquote>
        <blockquote>
            “Easy Chat saved our team project — we chatted, shared files, and stayed organized.” — Ivan, student
        </blockquote>
    </section>

    <section class="cta">
        <h3>Join now!</h3>
        <p>Create an account in just a few seconds and start chatting without limits. No hassle — just freedom to talk.</p>
        <a href="./authorizationPages/signUp.php" class="signup-button">Sign up</a>
    </section>
</main>

<footer>
    <p>© 2025 Easy Chat. All rights reserved.</p>
</footer>
</body>
</html>
