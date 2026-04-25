<?php
session_start();
include "../includes/db.php";
include "../includes/auth.php";
include "../includes/functions.php";

girisZorunlu();

$user_id = $_SESSION['user_id'];

// Kullanici bilgileri
$kullanici = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id"));

// Bu kullanicinin datasetleri
$my_datasets = mysqli_query($conn, "SELECT d.*, c.cat_name FROM datasets d JOIN categories c ON d.cat_id = c.cat_id WHERE d.user_id = $user_id ORDER BY d.upload_date DESC");

// Indirme istatistigi
$indirme_sql = "SELECT COUNT(*) as toplam FROM downloads d JOIN datasets ds ON d.dataset_id = ds.dataset_id WHERE ds.user_id = $user_id";
$ind_result = mysqli_fetch_assoc(mysqli_query($conn, $indirme_sql));
$toplam_indirme = $ind_result['toplam'];

$uye_tarih = date("d.m.Y", strtotime($kullanici['created_at']));
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Profilim</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="navbar">
    <a href="../index.php">Ana Sayfa</a>
    <a href="upload.php">Dataset Yukle</a>
    <a href="profile.php">Profilim</a>
    <a href="../logout.php">Cikis Yap</a>
</div>

<div class="container">
    <h2>Profilim</h2>

    <p><strong>Kullanici Adi:</strong> <?php echo $kullanici['username']; ?></p>
    <p><strong>Email:</strong> <?php echo $kullanici['email']; ?></p>
    <p><strong>Uyelik Tarihi:</strong> <?php echo $uye_tarih; ?></p>
    <p><strong>Toplam Indirme (Datasetlerim):</strong> <?php echo $toplam_indirme; ?></p>

    <hr style="margin:20px 0;">

    <h3>Benim Datasetlerim</h3>
    <br>

    <?php if(mysqli_num_rows($my_datasets) == 0): ?>
        <p>Henuz dataset yuklemdiniz. <a href="upload.php">Yukle</a></p>
    <?php else: ?>
        <table>
            <tr>
                <th>Baslik</th>
                <th>Kategori</th>
                <th>Tarih</th>
                <th>Indirme</th>
                <th>Islem</th>
            </tr>
            <?php while($ds = mysqli_fetch_assoc($my_datasets)): ?>
            <tr>
                <td><a href="dataset.php?id=<?php echo $ds['dataset_id']; ?>" class="duz-link"><?php echo $ds['title']; ?></a></td>
                <td><?php echo $ds['cat_name']; ?></td>
                <td><?php echo date("d.m.Y", strtotime($ds['upload_date'])); ?></td>
                <td><?php echo indirmeSayisi($conn, $ds['dataset_id']); ?></td>
                <td>
                    <a href="../delete.php?id=<?php echo $ds['dataset_id']; ?>" class="silme-btn" onclick="return confirm('Silmek istediginize emin misiniz?')">Sil</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
