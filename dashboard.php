<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$sonuc1      = mysqli_query($conn, "SELECT COUNT(*) as sayi FROM users");
$satir1      = mysqli_fetch_assoc($sonuc1);
$toplam_user = $satir1['sayi'];

$sonuc2         = mysqli_query($conn, "SELECT COUNT(*) as sayi FROM datasets");
$satir2         = mysqli_fetch_assoc($sonuc2);
$toplam_dataset = $satir2['sayi'];

$sonuc3         = mysqli_query($conn, "SELECT COUNT(*) as sayi FROM downloads");
$satir3         = mysqli_fetch_assoc($sonuc3);
$toplam_indirme = $satir3['sayi'];

$en_cok = mysqli_query($conn, "SELECT d.dataset_id, d.title, COUNT(dl.download_id) as toplam
    FROM datasets d
    LEFT JOIN downloads dl ON d.dataset_id = dl.dataset_id
    GROUP BY d.dataset_id, d.title
    ORDER BY toplam DESC
    LIMIT 5");

$son_yuklenen = mysqli_query($conn, "SELECT * FROM datasets ORDER BY upload_date DESC LIMIT 5");
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
        <?php while($satir = mysqli_fetch_assoc($en_cok)): ?>
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
        <?php while($satir = mysqli_fetch_assoc($son_yuklenen)): ?>
        <tr>
            <td><a href="dataset.php?id=<?php echo $satir['dataset_id']; ?>" class="duz-link"><?php echo $satir['title']; ?></a></td>
            <td><?php echo $satir['upload_date']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
