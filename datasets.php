<?php
session_start();
include "includes/db.php";

$result = mysqli_query($conn, "SELECT * FROM datasets ORDER BY upload_date DESC");
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

    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <?php
        $u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM users WHERE user_id = {$row['user_id']}"));
        $k = mysqli_fetch_assoc(mysqli_query($conn, "SELECT cat_name FROM categories WHERE cat_id = {$row['cat_id']}"));
        ?>
        <div class="dataset-kart">
            <h3><a href="dataset.php?id=<?php echo $row['dataset_id']; ?>"><?php echo $row['title']; ?></a></h3>
            <p>Kategori: <?php echo $k['cat_name']; ?> | Yukleyen: <?php echo $u['username']; ?></p>
            <p><?php echo $row['description']; ?></p>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
