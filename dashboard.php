<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$toplam_user    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sayi FROM users"))['sayi'];
$toplam_dataset = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sayi FROM datasets"))['sayi'];
$toplam_indirme = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sayi FROM downloads"))['sayi'];

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
    <a href="upload.php">Yukle</a>
    <a href="profile.php">Profilim</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="logout.php">Cikis Yap</a>
</div>

<div class="container">
    <h2>Dashboard</h2>
    <br>

    <p>Toplam Kullanici: <strong><?php echo $toplam_user; ?></strong></p>
    <p>Toplam Dataset: <strong><?php echo $toplam_dataset; ?></strong></p>
    <p>Toplam Indirme: <strong><?php echo $toplam_indirme; ?></strong></p>

    <hr style="margin:20px 0;">

    <h3>En Cok Indirilen Datasetler</h3>
    <br>
    <table>
        <tr><th>Baslik</th><th>Indirme</th></tr>
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
        <tr><th>Baslik</th><th>Tarih</th></tr>
        <?php while($row = mysqli_fetch_assoc($son_yuklenen)): ?>
        <tr>
            <td><a href="dataset.php?id=<?php echo $row['dataset_id']; ?>" class="duz-link"><?php echo $row['title']; ?></a></td>
            <td><?php echo $row['upload_date']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
