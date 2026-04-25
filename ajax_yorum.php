<?php
session_start();
include "includes/db.php";
include "includes/functions.php";

if(!isset($_SESSION['user_id'])){
    echo "Giris yapmaniz gerekiyor!";
    exit();
}

if(!isset($_POST['dataset_id']) || !isset($_POST['yorum'])){
    exit();
}

$dataset_id = (int)$_POST['dataset_id'];
$yorum = temizle($_POST['yorum']);
$user_id = $_SESSION['user_id'];

if(empty($yorum)){
    echo "Yorum bos olamaz!";
    exit();
}

// Yorumu ekle
mysqli_query($conn, "INSERT INTO comments (dataset_id, user_id, comment_text) VALUES ($dataset_id, $user_id, '$yorum')");

// Guncel yorumlari don
$result = mysqli_query($conn, "SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.dataset_id = $dataset_id ORDER BY c.comment_date DESC");

if(mysqli_num_rows($result) == 0){
    echo "<p>Henuz yorum yok.</p>";
} else {
    while($row = mysqli_fetch_assoc($result)){
        $tarih = date("d.m.Y H:i", strtotime($row['comment_date']));
        echo "<div class='yorum-kutu'>";
        echo "<strong>{$row['username']}</strong> - $tarih";
        echo "<p>{$row['comment_text']}</p>";
        echo "</div>";
    }
}
?>
