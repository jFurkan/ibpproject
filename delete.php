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

// Dataset bu kullaniciya ait mi
$result = mysqli_query($conn, "SELECT * FROM datasets WHERE dataset_id = $id AND user_id = $user_id");
if(mysqli_num_rows($result) == 0){
    echo "Bu islemi yapamazsiniz!";
    exit();
}

$dataset = mysqli_fetch_assoc($result);

// Once iliskili kayitlari sil
mysqli_query($conn, "DELETE FROM dataset_tags WHERE dataset_id = $id");
mysqli_query($conn, "DELETE FROM comments WHERE dataset_id = $id");
mysqli_query($conn, "DELETE FROM ratings WHERE dataset_id = $id");
mysqli_query($conn, "DELETE FROM downloads WHERE dataset_id = $id");

// Dosyayi sil
$dosya = "uploads/" . $dataset['filename'];
if(file_exists($dosya)){
    unlink($dosya);
}

// Dataseti sil
mysqli_query($conn, "DELETE FROM datasets WHERE dataset_id = $id");

header("Location: pages/profile.php");
exit();
?>
