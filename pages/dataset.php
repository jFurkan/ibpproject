<?php
session_start();
include "../includes/db.php";
include "../includes/functions.php";

if(!isset($_GET['id'])){
    header("Location: ../index.php");
    exit();
}

$id = (int)$_GET['id'];

// Dataset bilgisi
$sql = "SELECT d.*, u.username, c.cat_name FROM datasets d
        JOIN users u ON d.user_id = u.user_id
        JOIN categories c ON d.cat_id = c.cat_id
        WHERE d.dataset_id = $id";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    echo "Dataset bulunamadi!";
    exit();
}

$dataset = mysqli_fetch_assoc($result);

// Puan gonderme
$hata = "";
$basari = "";

if(isset($_POST['puan_gonder']) && isset($_SESSION['user_id'])){
    $puan = (int)$_POST['rating'];
    $user_id = $_SESSION['user_id'];

    if($puan < 1 || $puan > 5){
        $hata = "1 ile 5 arasi puan girin!";
    } else {
        $puan_kontrol = mysqli_query($conn, "SELECT * FROM ratings WHERE dataset_id = $id AND user_id = $user_id");
        if(mysqli_num_rows($puan_kontrol) > 0){
            mysqli_query($conn, "UPDATE ratings SET rating = $puan WHERE dataset_id = $id AND user_id = $user_id");
        } else {
            mysqli_query($conn, "INSERT INTO ratings (dataset_id, user_id, rating) VALUES ($id, $user_id, $puan)");
        }
        $basari = "Puaniniz kaydedildi!";
    }
}

// Tagler
$tag_result = mysqli_query($conn, "SELECT t.tag_name FROM tags t JOIN dataset_tags dt ON t.tag_id = dt.tag_id WHERE dt.dataset_id = $id");

// Yorumlar
$yorum_result = mysqli_query($conn, "SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.dataset_id = $id ORDER BY c.comment_date DESC");

$puan = ortalamaPuan($conn, $id);
$indirme = indirmeSayisi($conn, $id);
$tarih = date("d.m.Y H:i", strtotime($dataset['upload_date']));
$boyut = dosyaBoyutuFormatla($dataset['filesize']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $dataset['title']; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/validation.js"></script>
    <script src="../js/ajax.js"></script>
</head>
<body>

<div class="navbar">
    <a href="../index.php">Ana Sayfa</a>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="upload.php">Dataset Yukle</a>
        <a href="profile.php">Profilim</a>
        <a href="../logout.php">Cikis Yap</a>
    <?php else: ?>
        <a href="../login.php">Giris Yap</a>
        <a href="../register.php">Kayit Ol</a>
    <?php endif; ?>
</div>

<div class="container">
    <h2><?php echo $dataset['title']; ?></h2>

    <?php if($hata != "") echo "<p class='hata'>$hata</p>"; ?>
    <?php if($basari != "") echo "<p class='basari'>$basari</p>"; ?>

    <p><strong>Kategori:</strong> <?php echo $dataset['cat_name']; ?></p>
    <p><strong>Yukleyen:</strong> <?php echo $dataset['username']; ?></p>
    <p><strong>Tarih:</strong> <?php echo $tarih; ?></p>
    <p><strong>Boyut:</strong> <?php echo $boyut; ?></p>
    <p><strong>Ortalama Puan:</strong> <?php echo $puan; ?></p>
    <p><strong>Indirilme Sayisi:</strong> <?php echo $indirme; ?></p>

    <p><strong>Aciklama:</strong><br><?php echo $dataset['description']; ?></p>

    <p><strong>Tagler:</strong>
    <?php while($tag = mysqli_fetch_assoc($tag_result)): ?>
        <span class="tag"><?php echo $tag['tag_name']; ?></span>
    <?php endwhile; ?>
    </p>

    <br>

    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="../download.php?id=<?php echo $id; ?>">
            <input type="button" value="Indir">
        </a>
    <?php else: ?>
        <p><a href="../login.php">Indirmek icin giris yapin</a></p>
    <?php endif; ?>

    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $dataset['user_id']): ?>
        &nbsp;&nbsp;
        <a href="../delete.php?id=<?php echo $id; ?>" onclick="return confirm('Silmek istediginize emin misiniz?')">
            <button class="silme-btn">Dataseti Sil</button>
        </a>
    <?php endif; ?>

    <hr style="margin:20px 0;">

    <!-- Puan verme -->
    <?php if(isset($_SESSION['user_id'])): ?>
    <h3>Puan Ver</h3>
    <form method="POST">
        <select name="rating" style="width:auto; display:inline; padding:8px;">
            <option value="1">1 - Cok Kotu</option>
            <option value="2">2 - Kotu</option>
            <option value="3">3 - Orta</option>
            <option value="4">4 - Iyi</option>
            <option value="5">5 - Mukemmel</option>
        </select>
        <input type="submit" name="puan_gonder" value="Puan Ver" style="width:auto;">
    </form>
    <br>
    <?php endif; ?>

    <hr style="margin:20px 0;">

    <!-- Yorumlar -->
    <h3>Yorumlar</h3>
    <br>

    <div id="yorumlar">
        <?php
        if(mysqli_num_rows($yorum_result) == 0){
            echo "<p>Henuz yorum yok.</p>";
        } else {
            while($yorum = mysqli_fetch_assoc($yorum_result)){
                $ytarih = date("d.m.Y H:i", strtotime($yorum['comment_date']));
                echo "<div class='yorum-kutu'>";
                echo "<strong>{$yorum['username']}</strong> - $ytarih";
                echo "<p>{$yorum['comment_text']}</p>";
                echo "</div>";
            }
        }
        ?>
    </div>

    <br>

    <?php if(isset($_SESSION['user_id'])): ?>
    <h3>Yorum Yap</h3>
    <textarea id="comment_text" rows="3" style="width:100%;"></textarea>
    <br><br>
    <button onclick="yorumGonder(<?php echo $id; ?>)">Yorum Gonder</button>
    <?php else: ?>
        <p><a href="../login.php">Yorum yapmak icin giris yapin</a></p>
    <?php endif; ?>

</div>

</body>
</html>
