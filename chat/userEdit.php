<?php
require "../connect.php";
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['login'])) {
    header("location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = $_POST['id'];
    if (!$id) {
        header("location: ./guestRoom.php");
        exit();
    }
}

if (isset($_POST['submit'])) {
    $username = $_POST['name'];
    $login = $_POST['login'];
    $email = $_POST['email'];

    // Загрузка текущих данных (status и role) если нужно
    $currentStatus = 1;
    $currentRole = 2;

    if ($_SESSION['id'] == $id && $_SESSION['role'] != "admin") {
        $sqlCurrent = "SELECT ban, role_id FROM users WHERE id = ?";
        $stmtCurrent = $conn->prepare($sqlCurrent);
        $stmtCurrent->bind_param("i", $id);
        $stmtCurrent->execute();
        $resultCurrent = $stmtCurrent->get_result();
        if ($rowCurrent = $resultCurrent->fetch_assoc()) {
            $currentStatus = $rowCurrent['ban'];
            $currentRole = $rowCurrent['role_id'];
        }
        $status = $currentStatus;
        $role = $currentRole;
    } elseif ($_SESSION['role'] === "admin") {
        $status = isset($_POST['status']) ? intval($_POST['status']) : 1;
        $role = isset($_POST['role']) ? intval($_POST['role']) : 2;
    } else {
        $status = 1;
        $role = 2;
    }

    $updateSql = "UPDATE users SET name = ?, login = ?, email = ?, ban = ?, role_id = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("sssiii", $username, $login, $email, $status, $role, $id);
    $stmt->execute();

    header("location: ./guestRoom.php");
    exit();
}

$sql = "SELECT users.name AS username, users.login, users.email, users.ban AS status, roles.name AS role 
        FROM users 
        JOIN roles ON users.role_id = roles.id 
        WHERE users.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
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
</head>
<body>
<header>
    <h1>EASY CHAT</h1>
    <div class="top-nav">
        <h2>
            <a href="../sessionDestroyer.php">logout</a>
        </h2>
    </div>
</header>
<main>
    <form class="card-container-users-for-admin" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="user-card">
            <div class="card-header">
                <h3>👤 <input type="text" name="name" value="<?php echo htmlspecialchars($row['username']); ?>"></h3>
                <div class="role-select-wrapper" style="margin-top: 10px;">
                    <label for="role" style="font-weight: bold;"></label>
                    <select name="role" id="role" class="role-dropdown" style="margin-left: 10px; padding: 5px; border-radius: 8px;" <?php if ($_SESSION['role'] != "admin") echo "disabled"; ?>>
                        <option value="1" <?php if ($row['role'] == 'admin') echo "selected"; ?>>ADMIN</option>
                        <option value="2" <?php if ($row['role'] == 'user') echo "selected"; ?>>USER</option>
                    </select>
                    <?php
                    if ($_SESSION['role'] != "admin") {
                        echo '<input type="hidden" name="role" value="' . ($row['role'] == 'admin' ? 1 : 2) . '">';
                    }
                    ?>
                </div>
            </div>

            <div class="card-body">
                <p><strong>Login: </strong><input type="text" name="login" value="<?php echo htmlspecialchars($row['login']); ?>"></p>
                <p><strong>Email: </strong><input type="text" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"></p>
                <p><strong>Status: </strong>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">

                    <select name="status" style="padding: 5px; border-radius: 8px;" <?php if ($_SESSION['role'] != "admin") echo "disabled"; ?>>
                        <option value="0" <?php if ($row['status'] == 0) echo "selected"; ?>>Active</option>
                        <option value="1" <?php if ($row['status'] == 1) echo "selected"; ?>>Banned</option>
                    </select>
                    <?php
                    if ($_SESSION['role'] != "admin") {
                        echo '<input type="hidden" name="status" value="' . $row['status'] . '">';
                    }
                    ?>
                </p>
            </div>
            <input style="border: none; margin-top: 20px;" type="submit" class="edit-btn" value="submit" name="submit">
        </div>
    </form>
</main>

<footer>
    <p>© 2025 Easy Chat. All rights reserved.</p>
</footer>
</body>
</html>
