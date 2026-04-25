<?php
session_start();
include "includes/db.php";
include "includes/functions.php";

$arama = "";
if(isset($_GET['ara'])){
    $arama = temizle($_GET['ara']);
}

$kategori = "";
if(isset($_GET['kategori'])){
    $kategori = temizle($_GET['kategori']);
}

// Datasetleri getir
$sql = "SELECT d.*, u.username, c.cat_name FROM datasets d
        JOIN users u ON d.user_id = u.user_id
        JOIN categories c ON d.cat_id = c.cat_id
        WHERE 1=1";

if($arama != ""){
    $sql .= " AND (d.title LIKE '%$arama%' OR d.description LIKE '%$arama%')";
}

if($kategori != ""){
    $sql .= " AND d.cat_id = '$kategori'";
}

$sql .= " ORDER BY d.upload_date DESC";
$result = mysqli_query($conn, $sql);

// Kategorileri getir
$kat_result = mysqli_query($conn, "SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Dataset Paylasim Sitesi</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/ajax.js"></script>
</head>
<body>

<div class="navbar">
    <a href="index.php">Ana Sayfa</a>
    <a href="datasets.php">Tum Datasetler</a>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="pages/upload.php">Dataset Yukle</a>
        <a href="pages/dashboard.php">Dashboard</a>
        <a href="pages/profile.php">Profilim</a>
        <a href="logout.php">Cikis Yap (<?php echo $_SESSION['username']; ?>)</a>
    <?php else: ?>
        <a href="login.php">Giris Yap</a>
        <a href="register.php">Kayit Ol</a>
    <?php endif; ?>
</div>

<div class="container">
    <h2>Dataset Paylasim Sitesi</h2>
    <p style="margin-bottom:15px;">Veri setlerini yukle, paylas ve indir!</p>

    <!-- Canli arama kutusu (AJAX) -->
    <input type="text" id="arama" placeholder="Dataset ara..." onkeyup="canliArama()" style="width:70%; margin-bottom:5px;">
    <div id="arama-sonuc"></div>

    <hr style="margin:15px 0;">

    <!-- Normal arama formu -->
    <form method="GET">
        <input type="text" name="ara" placeholder="Baslik veya aciklama ara..." value="<?php echo $arama; ?>" style="width:50%; display:inline;">
        <select name="kategori" style="width:20%; display:inline; padding:8px;">
            <option value="">Tum Kategoriler</option>
            <?php
            mysqli_data_seek($kat_result, 0);
            while($kat = mysqli_fetch_assoc($kat_result)){
                $sec = ($kategori == $kat['cat_id']) ? "selected" : "";
                echo "<option value='{$kat['cat_id']}' $sec>{$kat['cat_name']}</option>";
            }
            ?>
        </select>
        <input type="submit" value="Ara" style="width:auto;">
    </form>

    <hr style="margin:15px 0;">

    <h3>Datasetler</h3>
    <br>

    <?php
    if(mysqli_num_rows($result) == 0){
        echo "<p>Hic dataset bulunamadi.</p>";
    } else {
        while($row = mysqli_fetch_assoc($result)){
            $puan = ortalamaPuan($conn, $row['dataset_id']);
            $indirme = indirmeSayisi($conn, $row['dataset_id']);
            $tarih = date("d.m.Y", strtotime($row['upload_date']));

            // Tagleri getir
            $tag_sql = "SELECT t.tag_name FROM tags t
                        JOIN dataset_tags dt ON t.tag_id = dt.tag_id
                        WHERE dt.dataset_id = {$row['dataset_id']}";
            $tag_result = mysqli_query($conn, $tag_sql);

            echo "<div class='dataset-kart'>";
            echo "<h3><a href='pages/dataset.php?id={$row['dataset_id']}'>{$row['title']}</a></h3>";
            echo "<p>Kategori: {$row['cat_name']} | Yukleyen: {$row['username']} | Tarih: $tarih</p>";
            echo "<p>Puan: $puan | Indirme: $indirme</p>";
            if($row['description'] != ""){
                echo "<p>" . substr($row['description'], 0, 100) . "...</p>";
            }
            echo "<p>";
            while($tag = mysqli_fetch_assoc($tag_result)){
                echo "<span class='tag'>{$tag['tag_name']}</span>";
            }
            echo "</p>";
            echo "</div>";
        }
    }
    ?>
</div>

</body>
</html>
