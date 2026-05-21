<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$sonuc1      = db_query($conn, "SELECT COUNT(*) as sayi FROM users");
$satir1      = db_fetch($sonuc1);
$toplam_user = $satir1['sayi'];

$sonuc2         = db_query($conn, "SELECT COUNT(*) as sayi FROM datasets");
$satir2         = db_fetch($sonuc2);
$toplam_dataset = $satir2['sayi'];

$sonuc3         = db_query($conn, "SELECT COUNT(*) as sayi FROM downloads");
$satir3         = db_fetch($sonuc3);
$toplam_indirme = $satir3['sayi'];

$en_cok = db_query($conn, "SELECT d.dataset_id, d.title, COUNT(dl.download_id) as toplam
    FROM datasets d
    LEFT JOIN downloads dl ON d.dataset_id = dl.dataset_id
    GROUP BY d.dataset_id, d.title
    ORDER BY toplam DESC
    FETCH FIRST 5 ROWS ONLY");

$son_yuklenen = db_query($conn, "SELECT * FROM datasets ORDER BY upload_date DESC FETCH FIRST 5 ROWS ONLY");
$son_rows     = [];
while($s = db_fetch($son_yuklenen)) $son_rows[] = $s;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php">Ana Sayfa</a>
    <a href="upload.php">Yükle</a>
    <a href="profile.php">Profilim</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="logout.php">Çıkış Yap</a>
</div>

<div class="container">
    <h2>Dashboard</h2>
    <br>

    <p>Toplam Kullanıcı: <strong><?php echo $toplam_user; ?></strong></p>
    <p>Toplam Dataset: <strong><?php echo $toplam_dataset; ?></strong></p>
    <p>Toplam İndirme: <strong><?php echo $toplam_indirme; ?></strong></p>

    <hr style="margin:20px 0;">

    <h3>En Çok İndirilen Datasetler</h3>
    <br>
    <table>
        <tr><th>Başlık</th><th>İndirme</th></tr>
        <?php while($satir = db_fetch($en_cok)): ?>
        <tr>
            <td><a href="dataset.php?id=<?php echo $satir['dataset_id']; ?>" class="duz-link"><?php echo $satir['title']; ?></a></td>
            <td><?php echo $satir['toplam']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <hr style="margin:20px 0;">

    <h3>Son Yüklenen Datasetler</h3>
    <br>
    <table>
        <tr><th>Başlık</th><th>Tarih</th></tr>
        <?php foreach($son_rows as $satir): ?>
        <tr>
            <td><a href="dataset.php?id=<?php echo $satir['dataset_id']; ?>" class="duz-link"><?php echo $satir['title']; ?></a></td>
            <td><?php echo $satir['upload_date']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
