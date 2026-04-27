<?php
session_start();
include "includes/db.php";

if(!isset($_SESSION['user_id'])){
    echo "Giris yapmaniz gerekiyor!";
    exit();
}

$dataset_id = (int)$_POST['dataset_id'];
$yorum      = trim($_POST['yorum']);
$user_id    = $_SESSION['user_id'];

if($yorum == ""){
    echo "Yorum bos olamaz!";
    exit();
}

mysqli_query($conn, "INSERT INTO comments (dataset_id, user_id, comment_text) VALUES ($dataset_id, $user_id, '$yorum')");

// Guncel yorumlari don
$result = mysqli_query($conn, "SELECT c.comment_text, c.comment_date, u.username FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.dataset_id = $dataset_id ORDER BY c.comment_date DESC");

while($row = mysqli_fetch_assoc($result)){
    echo "<div class='yorum-kutu'>";
    echo "<strong>{$row['username']}</strong> - {$row['comment_date']}";
    echo "<p>{$row['comment_text']}</p>";
    echo "</div>";
}
?>
