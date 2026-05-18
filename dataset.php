<?php
session_start();
include "db.php";

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

$ds_sonuc = db_query($conn, "SELECT * FROM datasets WHERE dataset_id = $id");
$dataset  = db_fetch($ds_sonuc);
if(!$dataset){
    echo "Dataset bulunamadi!";
    exit();
}

$u_sonuc  = db_query($conn, "SELECT username FROM users WHERE user_id = " . $dataset['user_id']);
$yukleyen = db_fetch($u_sonuc);

$k_sonuc  = db_query($conn, "SELECT cat_name FROM categories WHERE cat_id = " . $dataset['cat_id']);
$kategori = db_fetch($k_sonuc);

$p_sonuc    = db_query($conn, "SELECT AVG(rating) as ort FROM ratings WHERE dataset_id = $id");
$puan_sonuc = db_fetch($p_sonuc);
if($puan_sonuc['ort']){
    $puan = round($puan_sonuc['ort'], 1) . "/5";
} else {
    $puan = "Henuz puanlanmadi";
}

$d_sonuc = db_query($conn, "SELECT COUNT(*) as sayi FROM downloads WHERE dataset_id = $id");
$indirme = db_fetch($d_sonuc);

$tagler = db_query($conn, "SELECT t.tag_name FROM tags t JOIN dataset_tags dt ON t.tag_id = dt.tag_id WHERE dt.dataset_id = $id");

$yorum_sonuc = db_query($conn, "SELECT c.comment_text, c.comment_date, u.username FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.dataset_id = $id ORDER BY c.comment_date DESC");
$yorumlar = [];
while($y = db_fetch($yorum_sonuc)) $yorumlar[] = $y;

$hata   = "";
$basari = "";

if(isset($_POST['puan_gonder']) && isset($_SESSION['user_id'])){
    $puan_deger = (int)$_POST['rating'];
    $user_id    = $_SESSION['user_id'];
    db_query($conn, "DELETE FROM ratings WHERE dataset_id = $id AND user_id = $user_id");
    db_query($conn, "INSERT INTO ratings (dataset_id, user_id, rating) VALUES ($id, $user_id, $puan_deger)");
    $basari = "Puaniniz kaydedildi!";
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
        <a href="upload.php">Yukle</a>
        <a href="profile.php">Profilim</a>
        <a href="logout.php">Cikis Yap</a>
    <?php else: ?>
        <a href="login.php">Giris Yap</a>
        <a href="register.php">Kayit Ol</a>
    <?php endif; ?>
</div>

<div class="container">
    <h2><?php echo $dataset['title']; ?></h2>

    <?php if($hata != "") echo "<p class='hata'>$hata</p>"; ?>
    <?php if($basari != "") echo "<p class='basari'>$basari</p>"; ?>

    <p><strong>Kategori:</strong> <?php echo $kategori['cat_name']; ?></p>
    <p><strong>Yukleyen:</strong> <?php echo $yukleyen['username']; ?></p>
    <p><strong>Tarih:</strong> <?php echo $dataset['upload_date']; ?></p>
    <p><strong>Puan:</strong> <?php echo $puan; ?></p>
    <p><strong>Indirme:</strong> <?php echo $indirme['sayi']; ?></p>
    <p><strong>Aciklama:</strong> <?php echo $dataset['description']; ?></p>

    <p><strong>Taglar:</strong>
    <?php while($tag = db_fetch($tagler)): ?>
        <span class="tag"><?php echo $tag['tag_name']; ?></span>
    <?php endwhile; ?>
    </p>

    <br>

    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="download.php?id=<?php echo $id; ?>"><input type="button" value="Indir"></a>
    <?php else: ?>
        <p><a href="login.php">Indirmek icin giris yapin</a></p>
    <?php endif; ?>

    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $dataset['user_id']): ?>
        &nbsp;
        <a href="edit.php?id=<?php echo $id; ?>">
            <button>Duzenle</button>
        </a>
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
            <option value="1">1 - Cok Kotü</option>
            <option value="2">2 - Kotü</option>
            <option value="3">3 - Orta</option>
            <option value="4">4 - Iyi</option>
            <option value="5">5 - Mukemmel</option>
        </select>
        <input type="submit" name="puan_gonder" value="Puan Ver" style="width:auto;">
    </form>
    <?php endif; ?>

    <hr style="margin:20px 0;">

    <h3>Yorumlar</h3>
    <br>
    <div id="yorumlar">
        <?php if(empty($yorumlar)): ?>
            <p>Henuz yorum yok.</p>
        <?php else: ?>
            <?php foreach($yorumlar as $yorum): ?>
                <div class="yorum-kutu">
                    <strong><?php echo $yorum['username']; ?></strong> - <?php echo $yorum['comment_date']; ?>
                    <p><?php echo $yorum['comment_text']; ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <br>

    <?php if(isset($_SESSION['user_id'])): ?>
        <h3>Yorum Yap</h3>
        <textarea id="comment_text" rows="3" style="width:100%;"></textarea>
        <br><br>
        <button onclick="yorumGonder(<?php echo $id; ?>)">Gonder</button>
    <?php else: ?>
        <p><a href="login.php">Yorum icin giris yapin</a></p>
    <?php endif; ?>
</div>

</body>
</html>
