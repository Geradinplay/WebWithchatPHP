<?php
require '../connect.php';
session_start();

if (isset($_SESSION["login"])) {
    header("Location: ../index.php");
    exit;
}


$loginErr = $passwordErr = $error = $emailErr = $personalNameErr = "";
$validation = true;

if (isset($_POST["submit"])) {
    $login = trim($_POST["login"]);
    $personalName = trim($_POST["personalName"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($login)) {
        $loginErr = "Please enter login";
        $validation = false;
    } elseif (strlen($login) < 4) {
        $loginErr = "Login must be at least 4 characters";
        $validation = false;
    }

    if (empty($personalName)) {
        $personalNameErr = "Please enter your name";
        $validation = false;
    } elseif (strlen($personalName) < 2) {
        $personalNameErr = "Name must be at least 2 characters";
        $validation = false;
    }

    if (empty($email)) {
        $emailErr = "Please enter email";
        $validation = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $validation = false;
    }

    if (empty($password)) {
        $passwordErr = "Please enter password";
        $validation = false;
    } elseif (strlen($password) < 4) {
        $passwordErr = "Password must be at least 4 characters";
        $validation = false;
    }

    if ($validation) {
        $sql = "SELECT * FROM users WHERE login = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $login);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $loginErr = "This login is already taken.";
            $validation = false;
        }


        if($validation) {
            $stmt = $conn->prepare("INSERT INTO users (login, name, email, password, role_id, ban) VALUES (?, ?, ?, ?, 2, 0)");
            $stmt->bind_param("ssss", $login, $personalName, $email, $password);
            if ($stmt->execute()) {
                $_SESSION["id"] = $conn->insert_id;
                $_SESSION["login"] = $login;
                $_SESSION["username"] = $personalName;
                $_SESSION["role"] = "user";
                header("Location: ../index.php");
                exit();
            } else {
                $error = "There was an error logging you in. Please try again later.";
            }
            $stmt->close();
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login and Registration</title>
    <link rel="stylesheet" href="./style/form-style.css">
</head>
<body>

<div class="container">
    <form id="loginForm" class="active" method="post" onsubmit="return registrationForm()">
        <h2>Sign up</h2>

        <input type="text" name="login" id="login" placeholder="Login/Nickname" required><br>
        <div class="error" id="loginError" name="loginError"><?php
            if(isset($loginErr)){
                echo $loginErr;
            } ?></div>

        <input type="text" name="personalName" id="personalName" placeholder="PersonalName" required><br>
        <div class="error" id="personalNameError" name="personalNameError"><?php
            if(isset($personalNameErr)){
                echo $personalNameErr;
            } ?></div>

        <input type="text" name="email" id="email" placeholder="Email" required><br>
        <div class="error" id="emailError" name="emailError"><?php
            if(isset($emailErr)){
                echo $emailErr;
            } ?></div>

        <input type="password" name="password" id="password" placeholder="Password" required><br>
        <div class="error" id="passwordError" name="passwordError"><?php
            if(isset($passwordErr)) {
                echo $passwordErr;
            } ?></div>

        <button type="submit" name="submit">Sign up</button>

        <div class="switch-link">
            <a  onclick="window.location.href='logIn.php'">Do you already have an account?</a>
        </div>
    </form>
</div>
<script src="jsValidation/validation.js"></script>
</body>
</html>
