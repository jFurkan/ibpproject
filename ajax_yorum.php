<?php
session_start();
include "db.php";

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

$sonuc = mysqli_query($conn, "SELECT comment_text, comment_date, user_id FROM comments WHERE dataset_id = $dataset_id ORDER BY comment_date DESC");

while($satir = mysqli_fetch_assoc($sonuc)){
    $uid = $satir['user_id'];
    $ksonuc = mysqli_query($conn, "SELECT username FROM users WHERE user_id = $uid");
    $kullanici = mysqli_fetch_assoc($ksonuc);

    echo "<div class='yorum-kutu'>";
    echo "<strong>" . $kullanici['username'] . "</strong> - " . $satir['comment_date'];
    echo "<p>" . $satir['comment_text'] . "</p>";
    echo "</div>";
}
?>
