<?php
session_start();
include "includes/db.php";
include "includes/functions.php";

// Tum datasetler - tag ile arama
$tag_ara = "";
if(isset($_GET['tag'])){
    $tag_ara = temizle($_GET['tag']);
}

if($tag_ara != ""){
    $sql = "SELECT d.*, u.username, c.cat_name FROM datasets d
            JOIN users u ON d.user_id = u.user_id
            JOIN categories c ON d.cat_id = c.cat_id
            JOIN dataset_tags dt ON d.dataset_id = dt.dataset_id
            JOIN tags t ON dt.tag_id = t.tag_id
            WHERE t.tag_name LIKE '%$tag_ara%'
            ORDER BY d.upload_date DESC";
} else {
    $sql = "SELECT d.*, u.username, c.cat_name FROM datasets d
            JOIN users u ON d.user_id = u.user_id
            JOIN categories c ON d.cat_id = c.cat_id
            ORDER BY d.upload_date DESC";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Tum Datasetler</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php">Ana Sayfa</a>
    <a href="datasets.php">Tum Datasetler</a>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="pages/upload.php">Dataset Yukle</a>
        <a href="pages/profile.php">Profilim</a>
        <a href="logout.php">Cikis Yap</a>
    <?php else: ?>
        <a href="login.php">Giris Yap</a>
        <a href="register.php">Kayit Ol</a>
    <?php endif; ?>
</div>

<div class="container">
    <h2>Tum Datasetler</h2>

    <?php if($tag_ara != ""): ?>
        <p>Tag araması: <strong><?php echo $tag_ara; ?></strong> | <a href="datasets.php">Temizle</a></p>
    <?php endif; ?>

    <br>

    <?php
    if(mysqli_num_rows($result) == 0){
        echo "<p>Hic dataset bulunamadi.</p>";
    } else {
        while($row = mysqli_fetch_assoc($result)){
            $puan = ortalamaPuan($conn, $row['dataset_id']);
            $indirme = indirmeSayisi($conn, $row['dataset_id']);
            $tarih = date("d.m.Y", strtotime($row['upload_date']));
            $boyut = dosyaBoyutuFormatla($row['filesize']);

            $tag_result = mysqli_query($conn, "SELECT t.tag_name FROM tags t JOIN dataset_tags dt ON t.tag_id = dt.tag_id WHERE dt.dataset_id = {$row['dataset_id']}");

            echo "<div class='dataset-kart'>";
            echo "<h3><a href='pages/dataset.php?id={$row['dataset_id']}'>{$row['title']}</a></h3>";
            echo "<p>Kategori: {$row['cat_name']} | Yukleyen: {$row['username']} | Tarih: $tarih | Boyut: $boyut</p>";
            echo "<p>Puan: $puan | Indirme: $indirme</p>";
            echo "<p>";
            while($tag = mysqli_fetch_assoc($tag_result)){
                echo "<a href='datasets.php?tag={$tag['tag_name']}'><span class='tag'>{$tag['tag_name']}</span></a> ";
            }
            echo "</p>";
            echo "</div>";
        }
    }
    ?>
</div>

</body>
</html>
