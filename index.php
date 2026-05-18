<?php
session_start();
include "db.php";

$arama = "";
if(isset($_GET['ara'])){
    $arama = trim($_GET['ara']);
}

if($arama == ""){
    $sonuc = db_query($conn, "SELECT * FROM datasets ORDER BY upload_date DESC");
} else {
    $sonuc = db_query($conn, "SELECT * FROM datasets WHERE title LIKE '%$arama%' ORDER BY upload_date DESC");
}

$rows = [];
while($row = db_fetch($sonuc)) $rows[] = $row;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dataset Paylasim Sitesi</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>

<div class="navbar">
    <a href="index.php">Ana Sayfa</a>
    <a href="datasets.php">Datasetler</a>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="upload.php">Yukle</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profilim</a>
        <a href="logout.php">Cikis (<?php echo $_SESSION['username']; ?>)</a>
    <?php else: ?>
        <a href="login.php">Giris Yap</a>
        <a href="register.php">Kayit Ol</a>
    <?php endif; ?>
</div>

<div class="container">
    <h2>Dataset Paylasim Sitesi</h2>
    <p>Veri setlerini yukle, paylas ve indir!</p>
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

    <?php if(empty($rows)): ?>
        <p>Hic dataset bulunamadi.</p>
    <?php else: ?>
        <?php foreach($rows as $satir): ?>
            <?php
            $u_sonuc  = db_query($conn, "SELECT username FROM users WHERE user_id = " . $satir['user_id']);
            $yukleyen = db_fetch($u_sonuc);

            $k_sonuc  = db_query($conn, "SELECT cat_name FROM categories WHERE cat_id = " . $satir['cat_id']);
            $kategori = db_fetch($k_sonuc);

            $p_sonuc    = db_query($conn, "SELECT AVG(rating) as ort FROM ratings WHERE dataset_id = " . $satir['dataset_id']);
            $puan_sonuc = db_fetch($p_sonuc);
            $puan       = $puan_sonuc['ort'] ? round($puan_sonuc['ort'], 1) . "/5" : "Yok";

            $d_sonuc = db_query($conn, "SELECT COUNT(*) as sayi FROM downloads WHERE dataset_id = " . $satir['dataset_id']);
            $indirme = db_fetch($d_sonuc);
            ?>
            <div class="dataset-kart">
                <h3><a href="dataset.php?id=<?php echo $satir['dataset_id']; ?>"><?php echo $satir['title']; ?></a></h3>
                <p>Kategori: <?php echo $kategori['cat_name']; ?> | Yukleyen: <?php echo $yukleyen['username']; ?></p>
                <p>Puan: <?php echo $puan; ?> | Indirme: <?php echo $indirme['sayi']; ?></p>
                <p><?php echo $satir['description']; ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
