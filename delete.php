<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id      = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

$sonuc = mysqli_query($conn, "SELECT * FROM datasets WHERE dataset_id = $id AND user_id = $user_id");
if(mysqli_num_rows($sonuc) == 0){
    echo "Bu islemi yapamazsiniz!";
    exit();
}

$dataset = mysqli_fetch_assoc($sonuc);

mysqli_query($conn, "DELETE FROM dataset_tags WHERE dataset_id = $id");
mysqli_query($conn, "DELETE FROM comments WHERE dataset_id = $id");
mysqli_query($conn, "DELETE FROM ratings WHERE dataset_id = $id");
mysqli_query($conn, "DELETE FROM downloads WHERE dataset_id = $id");

$dosya = "uploads/" . $dataset['filename'];
if(file_exists($dosya)){
    unlink($dosya);
}

mysqli_query($conn, "DELETE FROM datasets WHERE dataset_id = $id");

header("Location: profile.php");
exit();
?>
