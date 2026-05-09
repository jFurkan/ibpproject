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

$sonuc = mysqli_query($conn, "SELECT * FROM datasets WHERE dataset_id = $id");
if(mysqli_num_rows($sonuc) == 0){
    echo "Dataset bulunamadi!";
    exit();
}

$dataset = mysqli_fetch_assoc($sonuc);

mysqli_query($conn, "INSERT INTO downloads (dataset_id, user_id) VALUES ($id, $user_id)");

$dosya = "uploads/" . $dataset['filename'];

if(file_exists($dosya)){
    header("Content-Disposition: attachment; filename=" . $dataset['filename']);
    header("Content-Type: application/octet-stream");
    header("Content-Length: " . filesize($dosya));
    readfile($dosya);
    exit();
} else {
    echo "Dosya bulunamadi! <a href='index.php'>Ana sayfaya don</a>";
}
?>
