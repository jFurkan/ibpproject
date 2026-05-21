<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    echo "Giriş yapmanız gerekiyor!";
    exit();
}

$dataset_id = (int)$_POST['dataset_id'];
$yorum      = trim($_POST['yorum']);
$user_id    = $_SESSION['user_id'];

if($yorum == ""){
    echo "Yorum boş olamaz!";
    exit();
}

db_query($conn, "INSERT INTO comments (dataset_id, user_id, comment_text) VALUES ($dataset_id, $user_id, '$yorum')");

$sonuc = db_query($conn, "SELECT comment_text, comment_date, user_id FROM comments WHERE dataset_id = $dataset_id ORDER BY comment_date DESC");

while($satir = db_fetch($sonuc)){
    $uid       = $satir['user_id'];
    $ksonuc    = db_query($conn, "SELECT username FROM users WHERE user_id = $uid");
    $kullanici = db_fetch($ksonuc);

    echo "<div class='yorum-kutu'>";
    echo "<strong>" . $kullanici['username'] . "</strong> - " . $satir['comment_date'];
    echo "<p>" . $satir['comment_text'] . "</p>";
    echo "</div>";
}
?>
