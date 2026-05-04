<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id  = $_SESSION['user_id'];
$kullanici = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id"));
$datasetlerim = mysqli_query($conn, "SELECT * FROM datasets WHERE user_id = $user_id ORDER BY upload_date DESC");
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
    <a href="upload.php">Yukle</a>
    <a href="profile.php">Profilim</a>
    <a href="logout.php">Cikis Yap</a>
</div>

<div class="container">
    <h2>Profilim</h2>

    <p><strong>Kullanici Adi:</strong> <?php echo $kullanici['username']; ?></p>
    <p><strong>Email:</strong> <?php echo $kullanici['email']; ?></p>
    <p><strong>Uyelik Tarihi:</strong> <?php echo $kullanici['created_at']; ?></p>

    <hr style="margin:20px 0;">

    <h3>Benim Datasetlerim</h3>
    <br>

    <?php if(mysqli_num_rows($datasetlerim) == 0): ?>
        <p>Henuz dataset yuklediniz. <a href="upload.php">Yukle</a></p>
    <?php else: ?>
        <table>
            <tr>
                <th>Baslik</th>
                <th>Tarih</th>
                <th>Indirme</th>
                <th>Sil</th>
            </tr>
            <?php while($ds = mysqli_fetch_assoc($datasetlerim)): ?>
                <?php
                $ind = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sayi FROM downloads WHERE dataset_id = {$ds['dataset_id']}"));
                ?>
                <tr>
                    <td><a href="dataset.php?id=<?php echo $ds['dataset_id']; ?>" class="duz-link"><?php echo $ds['title']; ?></a></td>
                    <td><?php echo $ds['upload_date']; ?></td>
                    <td><?php echo $ind['sayi']; ?></td>
                    <td><a href="delete.php?id=<?php echo $ds['dataset_id']; ?>" class="silme-btn" onclick="return confirm('Emin misiniz?')">Sil</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
