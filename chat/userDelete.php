<?php
require '../connect.php';
$ID = $_GET["id"];
$sql = "DELETE FROM users WHERE id = ".$ID;
$result = $conn->query($sql);
if($result){
    header("Location: guestRoom.php");
}else {
    die($conn ->error);
}
?>