<?php
session_start();
include "includes/db.php";
include "includes/auth.php";

girisZorunlu();

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Dataset var mi
$result = mysqli_query($conn, "SELECT * FROM datasets WHERE dataset_id = $id");
if(mysqli_num_rows($result) == 0){
    echo "Dataset bulunamadi!";
    exit();
}

$dataset = mysqli_fetch_assoc($result);

// Indirme kaydini ekle
mysqli_query($conn, "INSERT INTO downloads (dataset_id, user_id) VALUES ($id, $user_id)");

// Dosyayi indir
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
