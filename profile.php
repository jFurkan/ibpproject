<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id      = $_SESSION['user_id'];
$k_sonuc      = db_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
$kullanici    = db_fetch($k_sonuc);
$datasetlerim = db_query($conn, "SELECT * FROM datasets WHERE user_id = $user_id ORDER BY upload_date DESC");
$ds_rows      = [];
while($ds = db_fetch($datasetlerim)) $ds_rows[] = $ds;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profilim</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php">Ana Sayfa</a>
    <a href="upload.php">Yükle</a>
    <a href="profile.php">Profilim</a>
    <a href="logout.php">Çıkış Yap</a>
</div>

<div class="container">
    <h2>Profilim</h2>

    <p><strong>Kullanıcı Adı:</strong> <?php echo $kullanici['username']; ?></p>
    <p><strong>Email:</strong> <?php echo $kullanici['email']; ?></p>
    <p><strong>Üyelik Tarihi:</strong> <?php echo $kullanici['created_at']; ?></p>

    <hr style="margin:20px 0;">

    <h3>Benim Datasetlerim</h3>
    <br>

    <?php if(empty($ds_rows)): ?>
        <p>Henüz dataset yüklemediniz. <a href="upload.php">Yükle</a></p>
    <?php else: ?>
        <table>
            <tr>
                <th>Başlık</th>
                <th>Tarih</th>
                <th>İndirme</th>
                <th>Düzenle</th>
                <th>Sil</th>
            </tr>
            <?php foreach($ds_rows as $ds): ?>
                <?php
                $ind_sonuc = db_query($conn, "SELECT COUNT(*) as sayi FROM downloads WHERE dataset_id = " . $ds['dataset_id']);
                $indirme   = db_fetch($ind_sonuc);
                ?>
                <tr>
                    <td><a href="dataset.php?id=<?php echo $ds['dataset_id']; ?>" class="duz-link"><?php echo $ds['title']; ?></a></td>
                    <td><?php echo $ds['upload_date']; ?></td>
                    <td><?php echo $indirme['sayi']; ?></td>
                    <td><a href="edit.php?id=<?php echo $ds['dataset_id']; ?>">Düzenle</a></td>
                    <td><a href="delete.php?id=<?php echo $ds['dataset_id']; ?>" class="silme-btn" onclick="return confirm('Emin misiniz?')">Sil</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
