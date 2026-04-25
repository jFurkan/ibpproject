<?php
session_start();
include "../includes/db.php";
include "../includes/auth.php";
include "../includes/functions.php";

girisZorunlu();

// En cok indirilen datasetler - GROUP BY sorgusu
$en_cok = mysqli_query($conn, "SELECT d.title, d.dataset_id, COUNT(dl.download_id) as toplam
    FROM datasets d
    LEFT JOIN downloads dl ON d.dataset_id = dl.dataset_id
    GROUP BY d.dataset_id, d.title
    ORDER BY toplam DESC
    LIMIT 5");

// Son yuklenen datasetler
$son_yuklenen = mysqli_query($conn, "SELECT d.*, u.username FROM datasets d JOIN users u ON d.user_id = u.user_id ORDER BY d.upload_date DESC LIMIT 5");

// Toplam istatistikler
$toplam_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sayi FROM users"))['sayi'];
$toplam_dataset = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sayi FROM datasets"))['sayi'];
$toplam_indirme = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sayi FROM downloads"))['sayi'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="navbar">
    <a href="../index.php">Ana Sayfa</a>
    <a href="upload.php">Dataset Yukle</a>
    <a href="profile.php">Profilim</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="../logout.php">Cikis Yap</a>
</div>

<div class="container">
    <h2>Genel Istatistikler</h2>
    <br>
    <p>Toplam Kullanici: <strong><?php echo $toplam_user; ?></strong></p>
    <p>Toplam Dataset: <strong><?php echo $toplam_dataset; ?></strong></p>
    <p>Toplam Indirme: <strong><?php echo $toplam_indirme; ?></strong></p>

    <hr style="margin:20px 0;">

    <h3>En Cok Indirilen Datasetler</h3>
    <br>
    <table>
        <tr>
            <th>Dataset Basligi</th>
            <th>Indirme Sayisi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($en_cok)): ?>
        <tr>
            <td><a href="dataset.php?id=<?php echo $row['dataset_id']; ?>" class="duz-link"><?php echo $row['title']; ?></a></td>
            <td><?php echo $row['toplam']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <hr style="margin:20px 0;">

    <h3>Son Yuklenen Datasetler</h3>
    <br>
    <table>
        <tr>
            <th>Baslik</th>
            <th>Yukleyen</th>
            <th>Tarih</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($son_yuklenen)): ?>
        <tr>
            <td><a href="dataset.php?id=<?php echo $row['dataset_id']; ?>" class="duz-link"><?php echo $row['title']; ?></a></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo date("d.m.Y", strtotime($row['upload_date'])); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
