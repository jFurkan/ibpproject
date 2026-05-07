<?php
session_start();
include "db.php";

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM datasets WHERE dataset_id = $id");
if(mysqli_num_rows($result) == 0){
    echo "Dataset bulunamadı!";
    exit();
}
$dataset = mysqli_fetch_assoc($result);

$u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM users WHERE user_id = {$dataset['user_id']}"));
$k = mysqli_fetch_assoc(mysqli_query($conn, "SELECT cat_name FROM categories WHERE cat_id = {$dataset['cat_id']}"));

$p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(rating) as ort FROM ratings WHERE dataset_id = $id"));
if($p['ort']){
    $puan = round($p['ort'], 1) . "/5";
} else {
    $puan = "Henüz puanlanmadı";
}

$d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sayi FROM downloads WHERE dataset_id = $id"));

$tagler  = mysqli_query($conn, "SELECT t.tag_name FROM tags t JOIN dataset_tags dt ON t.tag_id = dt.tag_id WHERE dt.dataset_id = $id");
$yorumlar = mysqli_query($conn, "SELECT c.comment_text, c.comment_date, u.username FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.dataset_id = $id ORDER BY c.comment_date DESC");

$hata = "";
$basari = "";

if(isset($_POST['puan_gonder']) && isset($_SESSION['user_id'])){
    $puan_deger = (int)$_POST['rating'];
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "DELETE FROM ratings WHERE dataset_id = $id AND user_id = $user_id");
    mysqli_query($conn, "INSERT INTO ratings (dataset_id, user_id, rating) VALUES ($id, $user_id, $puan_deger)");
    $basari = "Puanınız kaydedildi!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $dataset['title']; ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>

<div class="navbar">
    <a href="index.php">Ana Sayfa</a>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="upload.php">Yükle</a>
        <a href="profile.php">Profilim</a>
        <a href="logout.php">Çıkış Yap</a>
    <?php else: ?>
        <a href="login.php">Giriş Yap</a>
        <a href="register.php">Kayıt Ol</a>
    <?php endif; ?>
</div>

<div class="container">
    <h2><?php echo $dataset['title']; ?></h2>

    <?php if($hata != "") echo "<p class='hata'>$hata</p>"; ?>
    <?php if($basari != "") echo "<p class='basari'>$basari</p>"; ?>

    <p><strong>Kategori:</strong> <?php echo $k['cat_name']; ?></p>
    <p><strong>Yükleyen:</strong> <?php echo $u['username']; ?></p>
    <p><strong>Tarih:</strong> <?php echo $dataset['upload_date']; ?></p>
    <p><strong>Puan:</strong> <?php echo $puan; ?></p>
    <p><strong>İndirme:</strong> <?php echo $d['sayi']; ?></p>
    <p><strong>Açıklama:</strong> <?php echo $dataset['description']; ?></p>

    <p><strong>Taglar:</strong>
    <?php while($tag = mysqli_fetch_assoc($tagler)): ?>
        <span class="tag"><?php echo $tag['tag_name']; ?></span>
    <?php endwhile; ?>
    </p>

    <br>

    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="download.php?id=<?php echo $id; ?>"><input type="button" value="İndir"></a>
    <?php else: ?>
        <p><a href="login.php">İndirmek için giriş yapın</a></p>
    <?php endif; ?>

    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $dataset['user_id']): ?>
        &nbsp;
        <a href="delete.php?id=<?php echo $id; ?>" onclick="return confirm('Emin misiniz?')">
            <button class="silme-btn">Sil</button>
        </a>
    <?php endif; ?>

    <hr style="margin:20px 0;">

    <?php if(isset($_SESSION['user_id'])): ?>
    <h3>Puan Ver</h3>
    <form method="POST">
        <select name="rating" style="width:auto; display:inline; padding:8px;">
            <option value="1">1 - Çok Kötü</option>
            <option value="2">2 - Kötü</option>
            <option value="3">3 - Orta</option>
            <option value="4">4 - İyi</option>
            <option value="5">5 - Mükemmel</option>
        </select>
        <input type="submit" name="puan_gonder" value="Puan Ver" style="width:auto;">
    </form>
    <?php endif; ?>

    <hr style="margin:20px 0;">

    <h3>Yorumlar</h3>
    <br>
    <div id="yorumlar">
        <?php if(mysqli_num_rows($yorumlar) == 0): ?>
            <p>Henüz yorum yok.</p>
        <?php else: ?>
            <?php while($yorum = mysqli_fetch_assoc($yorumlar)): ?>
                <div class="yorum-kutu">
                    <strong><?php echo $yorum['username']; ?></strong> - <?php echo $yorum['comment_date']; ?>
                    <p><?php echo $yorum['comment_text']; ?></p>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <br>

    <?php if(isset($_SESSION['user_id'])): ?>
        <h3>Yorum Yap</h3>
        <textarea id="comment_text" rows="3" style="width:100%;"></textarea>
        <br><br>
        <button onclick="yorumGonder(<?php echo $id; ?>)">Gönder</button>
    <?php else: ?>
        <p><a href="login.php">Yorum için giriş yapın</a></p>
    <?php endif; ?>
</div>

</body>
</html>
