<?php
require '../connect.php';
session_start();
if(!isset($_SESSION['login'])){
    header("location: ./index.php");
}
$sql="SELECT * FROM `users` WHERE id=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["id"]);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$login = $row["login"];
$personalName = $row["name"];
$email = $row["email"];
$banned = $row["ban"];
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
    <link rel="stylesheet" href="style/customeSearch.css">
</head>
<body>
<header>
    <h1>EASY CHAT HALL</h1>
    <div class="top-nav">
        <h2>
            <a href="../sessionDestroyer.php" >logout</a>
        </h2>
    </div>
</header>
<main>
    <h2>Hello dear user, here is your personal user card.</h2>
    <section class='card-container-users-for-admin' >

    <div class="user-card">
        <div class="card-header">
            <h3>👤  <?php echo htmlspecialchars($personalName);?></h3>
            <span class="badge">
                <?php
                if($_SESSION["role"] == "admin"){
                    echo "Administrator";
                }else {echo "User";}
                ?>
            </span>
        </div>

        <div class="card-body">
            <p><strong>Login:</strong> <?php echo $login?></p>
            <p><strong>Email:</strong> <?php echo $email?></p>
            <p><strong>Status:</strong> <?php if($banned==0){
                    echo "active";
                } else {echo "<font color='#bc8f8f'>banned</font>";}?>
                </p>
        </div>
        <?php echo "<a class='edit-btn ' href='userEdit.php?id=$_SESSION[id]' >edit</a>"?>
    </div>
    </section>
    <div class="divider-line"></div>
<div style="display: flex">
    <a href="chat.php" class="go-to-chat-btn">Go to chat!</a>
    <?php
    if($_SESSION["role"] == "admin"){
        echo '<a href="./report.php" class="go-to-chat-btn">Report!</a>';
    }
    ?>
    </div>
    <section class="rules-block-wrapper">
        <div class="rules-block">
            <div class="rule-card">
                <h3>1. Be respectful<br></h3>
                Respect other participants. Insults, rudeness, and provocations are not allowed.
            </div>

            <div class="rule-card">
                <h3>2. No spam<br></h3>
                Do not flood, send repeated messages, or post ads without permission.
            </div>

            <div class="rule-card">
                <h3>3. No prohibited content<br></h3>
                Messages containing violence, pornography, extremism, drugs, or piracy are forbidden.
            </div>

            <div class="rule-card">
                <h3>4. Don't impersonate others<br></h3>
                Using someone else's name, photo, or personal information is strictly prohibited.
            </div>
        </div>
    </section>


    <div class="divider-line"></div>



    <?php
if($_SESSION["role"] == "admin"){
    echo " <h2 class='h2-indents'>Administrator rights allow you to influence other users by deleting and changing their data.</h2>";
    $search="";
    $ban="";
    if(isset($_POST["search"])){
        if($_POST["search"]!=""){
            $search=$_POST["search"];
        }
        if(isset($_POST["ban"])){
         $ban=$_POST["ban"];
        }
    }


    echo '<form  method="post">
<div class="search-wrapper">
    <span class="search-icon">🔍</span>
    <input class="search-input" type="text" name="search" placeholder="Search something..." value="'.htmlspecialchars($search).'">
    <button class="search-button" type="submit">Go</button>
    </div>';

    echo '<div class="ban-status-wrapper">';
    echo '<label for="ban-status">Ban status: </label>';
    echo '<select name="ban" id="ban-status">';
    echo '<option value="" selected>All</option>';
    echo '<option value="0"' . ($ban == 0 ? ' selected' : '') . '>Active</option>';
    echo '<option value="1"' . ($ban == 1 ? ' selected' : '') . '>Banned</option>';
    echo '</select>';
    echo '</div>';

echo '</form>';

    $sql = "SELECT * FROM users WHERE id != ?";
    $params = [$_SESSION['id']];
    $types = "i";

    if (!empty($search)) {
        $sql .= " AND name LIKE ?";
        $params[] = "%" . $search . "%";
        $types .= "s";
    }
    if (isset($_POST["ban"]) && $ban !== ""){
        $sql .= " AND ban = ?";
        $params[] = $ban;
        $types .= "i";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<section class='card-container-users-for-admin'>";
    while($row = $result->fetch_assoc()) {
        echo '
    <div class="user-card">
        <div class="card-header">
            <h3>👤 '.htmlspecialchars($row["name"]).'</h3>
            <span class="badge">'.($row["role_id"] == 1?'Administrator':'User').'</span>
        </div>

        <div class="card-body">
            <p><strong>Login:</strong> '.htmlspecialchars($row["login"]).'</p>
            <p><strong>Email:</strong> '.htmlspecialchars($row["email"]).'</p>
            <p><strong>Status:</strong> '.($row["ban"] == 0 ? 'active' : "<span style=\"color:#bc8f8f\">banned</span>").'</p>
        </div>
        <a class="edit-btn" href="userEdit.php?id='.$row["id"].'">edit</a>
        <a class="delete-btn" href="userDelete.php?id='.$row["id"].'">delete</a>
    </div>';
    }
    echo "</section>";
}

?>
</main>




<footer>
    <p>© 2025 Easy Chat. All rights reserved.</p>
</footer>
</body>
</html>
