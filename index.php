<?php
session_start();
include "db.php";

$arama = "";
if(isset($_GET['ara'])){
    $arama = trim($_GET['ara']);
}

if($arama == ""){
    $result = mysqli_query($conn, "SELECT * FROM datasets ORDER BY upload_date DESC");
} else {
    $result = mysqli_query($conn, "SELECT * FROM datasets WHERE title LIKE '%$arama%' ORDER BY upload_date DESC");
}

$kategoriler = mysqli_query($conn, "SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dataset Paylaşım Sitesi</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>

<div class="navbar">
    <a href="index.php">Ana Sayfa</a>
    <a href="datasets.php">Datasetler</a>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="upload.php">Yükle</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profilim</a>
        <a href="logout.php">Çıkış (<?php echo $_SESSION['username']; ?>)</a>
    <?php else: ?>
        <a href="login.php">Giriş Yap</a>
        <a href="register.php">Kayıt Ol</a>
    <?php endif; ?>
</div>

<div class="container">
    <h2>Dataset Paylaşım Sitesi</h2>
    <p>Veri setlerini yükle, paylaş ve indir!</p>
    <br>

    <input type="text" id="arama" placeholder="Dataset ara..." onkeyup="canliArama()" style="width:60%;">
    <div id="arama-sonuc"></div>

    <hr style="margin:15px 0;">

    <form method="GET">
        <input type="text" name="ara" placeholder="Ara..." value="<?php echo $arama; ?>" style="width:50%; display:inline;">
        <input type="submit" value="Ara" style="width:auto;">
    </form>

    <hr style="margin:15px 0;">

    <h3>Datasetler</h3>
    <br>

    <?php if(mysqli_num_rows($result) == 0): ?>
        <p>Hiç dataset bulunamadı.</p>
    <?php else: ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <?php
            $u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM users WHERE user_id = {$row['user_id']}"));
            $k = mysqli_fetch_assoc(mysqli_query($conn, "SELECT cat_name FROM categories WHERE cat_id = {$row['cat_id']}"));
            $p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(rating) as ort FROM ratings WHERE dataset_id = {$row['dataset_id']}"));
            if($p['ort']){
                $puan = round($p['ort'], 1) . "/5";
            } else {
                $puan = "Yok";
            }
            $d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sayi FROM downloads WHERE dataset_id = {$row['dataset_id']}"));
            ?>
            <div class="dataset-kart">
                <h3><a href="dataset.php?id=<?php echo $row['dataset_id']; ?>"><?php echo $row['title']; ?></a></h3>
                <p>Kategori: <?php echo $k['cat_name']; ?> | Yükleyen: <?php echo $u['username']; ?></p>
                <p>Puan: <?php echo $puan; ?> | İndirme: <?php echo $d['sayi']; ?></p>
                <p><?php echo $row['description']; ?></p>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</body>
</html>
