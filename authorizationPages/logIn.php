<?php
require '../connect.php';
session_start();

if (isset($_SESSION["login"])) {
    header("Location: ../index.php");
    exit;
}

$loginErr = $passwordErr = $error = "";
$validation = true;

if (isset($_POST["submit"])) {
    $login = trim($_POST["login"]);
    $password = trim($_POST["password"]);

    if (empty($login)) {
        $loginErr = "Please enter login";
        $validation = false;
    }

    if (empty($password)) {
        $passwordErr = "Please enter password";
        $validation = false;
    }

    if (strlen($login) < 4) {
        $loginErr = "Login must be at least 4 characters";
        $validation = false;
    }

    if (strlen($password) < 4) {
        $passwordErr = "Password must be at least 4 characters";
        $validation = false;
    }

    if ($validation) {
        $stmt = $conn->prepare("SELECT users.id, users.name, users.login, users.password, roles.name AS role_name FROM users JOIN roles ON users.role_id = roles.id WHERE users.login = ? LIMIT 1");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($password==$row['password']) {
                $_SESSION["username"] = $row['name'];
                $_SESSION["login"] = $row["login"];
                $_SESSION["role"] = $row["role_name"];
                $_SESSION["id"] = $row["id"];
                header("Location: ../index.php");
                exit;
            } else {
                $loginErr = $passwordErr = "Invalid login or password.";
            }
        } else {
            $loginErr = $passwordErr = "Invalid login or password.";
        }
        $stmt->close();
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
    <form id="loginForm" class="active" method="post" onsubmit="return validateForm()">
        <h2>Log in</h2>

        <input type="text" name="login" id="login" placeholder="Username" required><br>
        <div class="error" id="loginError" name="loginError"><?php
            if(isset($loginErr)){
                echo $loginErr;
            } ?></div>

        <input type="password" name="password" id="password" placeholder="Password" required><br>
        <div class="error" id="passwordError" name="passwordError"><?php
            if(isset($passwordErr)) {
                echo $passwordErr;
            } ?></div>

        <button type="submit" name="submit">Log In</button>

        <div class="switch-link">
            <a onclick="window.location.href='signUp.php'">Don't have an account? Sign up</a>
        </div>
    </form>
</div>
<script src="jsValidation/validation.js"></script>
</body>
</html>
