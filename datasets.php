<?php
session_start();
include "db.php";

$sonuc = mysqli_query($conn, "SELECT * FROM datasets ORDER BY upload_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tum Datasetler</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php">Ana Sayfa</a>
    <a href="datasets.php">Datasetler</a>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="upload.php">Yukle</a>
        <a href="profile.php">Profilim</a>
        <a href="logout.php">Cikis Yap</a>
    <?php else: ?>
        <a href="login.php">Giris Yap</a>
        <a href="register.php">Kayit Ol</a>
    <?php endif; ?>
</div>

<div class="container">
    <h2>Tum Datasetler</h2>
    <br>

    <?php while($satir = mysqli_fetch_assoc($sonuc)): ?>
        <?php
        $u_sonuc  = mysqli_query($conn, "SELECT username FROM users WHERE user_id = " . $satir['user_id']);
        $yukleyen = mysqli_fetch_assoc($u_sonuc);

        $k_sonuc  = mysqli_query($conn, "SELECT cat_name FROM categories WHERE cat_id = " . $satir['cat_id']);
        $kategori = mysqli_fetch_assoc($k_sonuc);
        ?>
        <div class="dataset-kart">
            <h3><a href="dataset.php?id=<?php echo $satir['dataset_id']; ?>"><?php echo $satir['title']; ?></a></h3>
            <p>Kategori: <?php echo $kategori['cat_name']; ?> | Yukleyen: <?php echo $yukleyen['username']; ?></p>
            <p><?php echo $satir['description']; ?></p>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
