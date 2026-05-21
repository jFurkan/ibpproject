<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id      = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

$sonuc   = db_query($conn, "SELECT * FROM datasets WHERE dataset_id = $id AND user_id = $user_id");
$dataset = db_fetch($sonuc);
if(!$dataset){
    echo "Bu işlemi yapamazsınız!";
    exit();
}

$kategoriler = db_query($conn, "SELECT * FROM categories");
$kat_rows    = [];
while($k = db_fetch($kategoriler)) $kat_rows[] = $k;

$tag_sonuc     = db_query($conn, "SELECT t.tag_name FROM tags t JOIN dataset_tags dt ON t.tag_id = dt.tag_id WHERE dt.dataset_id = $id");
$mevcut_taglar = [];
while($t = db_fetch($tag_sonuc)) $mevcut_taglar[] = $t['tag_name'];

$hata   = "";
$basari = "";

if(isset($_POST['guncelle'])){

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $cat_id      = (int)$_POST['cat_id'];
    $tags        = trim($_POST['tags']);

    if($title == ""){
        $hata = "Başlık boş olamaz!";
    } elseif($cat_id == 0){
        $hata = "Kategori seçin!";
    } else {

        db_query($conn, "UPDATE datasets SET title='$title', description='$description', cat_id='$cat_id' WHERE dataset_id=$id AND user_id=$user_id");

        db_query($conn, "DELETE FROM dataset_tags WHERE dataset_id = $id");

        if($tags != ""){
            foreach(explode(",", $tags) as $tag){
                $tag = trim($tag);
                if($tag == "") continue;
                $tag_id = db_insert_id($conn, "INSERT INTO tags (tag_name) VALUES ('$tag')", "tag_id");
                db_query($conn, "INSERT INTO dataset_tags (dataset_id, tag_id) VALUES ($id, $tag_id)");
            }
        }

        $basari                 = "Dataset güncellendi!";
        $dataset['title']       = $title;
        $dataset['description'] = $description;
        $dataset['cat_id']      = $cat_id;
        $mevcut_taglar          = $tags != "" ? array_map('trim', explode(",", $tags)) : [];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dataset Düzenle</title>
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
    <h2>Dataset Düzenle</h2>

    <?php if($hata != "") echo "<p class='hata'>$hata</p>"; ?>
    <?php if($basari != "") echo "<p class='basari'>$basari</p>"; ?>

    <form method="POST">

        <label>Başlık:</label>
        <input type="text" name="title" value="<?php echo $dataset['title']; ?>">

        <label>Açıklama:</label>
        <textarea name="description" rows="4"><?php echo $dataset['description']; ?></textarea>

        <label>Kategori:</label>
        <select name="cat_id">
            <option value="">Seçin...</option>
            <?php foreach($kat_rows as $k): ?>
                <option value="<?php echo $k['cat_id']; ?>"
                    <?php if($k['cat_id'] == $dataset['cat_id']) echo "selected"; ?>>
                    <?php echo $k['cat_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Taglar (virgül ile ayırın):</label>
        <input type="text" name="tags" value="<?php echo implode(", ", $mevcut_taglar); ?>">

        <br><br>
        <input type="submit" name="guncelle" value="Güncelle">
        &nbsp;
        <a href="dataset.php?id=<?php echo $id; ?>"><button type="button">İptal</button></a>

    </form>
</div>

</body>
</html>
