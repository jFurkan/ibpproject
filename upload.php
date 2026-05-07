<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$hata = "";
$basari = "";

$kategoriler = mysqli_query($conn, "SELECT * FROM categories");

if(isset($_POST['yukle'])){

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $cat_id      = $_POST['cat_id'];
    $tags        = trim($_POST['tags']);

    if($title == ""){
        $hata = "Başlık boş olamaz!";
    } elseif($cat_id == ""){
        $hata = "Kategori seçin!";
    } elseif($_FILES['file']['error'] != 0){
        $hata = "Dosya seçin!";
    } else {

        $dosya_adi   = $_FILES['file']['name'];
        $dosya_boyut = $_FILES['file']['size'];
        $yeni_isim   = time() . "_" . $dosya_adi;

        if(!is_dir("uploads/")) mkdir("uploads/");

        move_uploaded_file($_FILES['file']['tmp_name'], "uploads/" . $yeni_isim);

        $user_id = $_SESSION['user_id'];
        mysqli_query($conn, "INSERT INTO datasets (user_id, cat_id, title, description, filename, filesize) VALUES ('$user_id', '$cat_id', '$title', '$description', '$yeni_isim', '$dosya_boyut')");
        $dataset_id = mysqli_insert_id($conn);

        if($tags != ""){
            foreach(explode(",", $tags) as $tag){
                $tag = trim($tag);
                if($tag == "") continue;
                mysqli_query($conn, "INSERT INTO tags (tag_name) VALUES ('$tag')");
                $tag_id = mysqli_insert_id($conn);
                mysqli_query($conn, "INSERT INTO dataset_tags (dataset_id, tag_id) VALUES ('$dataset_id', '$tag_id')");
            }
        }

        $basari = "Dataset yüklendi! <a href='dataset.php?id=$dataset_id'>Görüntüle</a>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dataset Yükle</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>

<div class="navbar">
    <a href="index.php">Ana Sayfa</a>
    <a href="upload.php">Yükle</a>
    <a href="profile.php">Profilim</a>
    <a href="logout.php">Çıkış Yap</a>
</div>

<div class="container">
    <h2>Dataset Yükle</h2>

    <?php if($hata != "") echo "<p class='hata'>$hata</p>"; ?>
    <?php if($basari != "") echo "<p class='basari'>$basari</p>"; ?>

    <form method="POST" enctype="multipart/form-data" onsubmit="return uploadDogrula()">

        <label>Başlık:</label>
        <input type="text" id="title" name="title">

        <label>Açıklama:</label>
        <textarea name="description" rows="4"></textarea>

        <label>Kategori:</label>
        <select name="cat_id">
            <option value="">Seçin...</option>
            <?php while($k = mysqli_fetch_assoc($kategoriler)): ?>
                <option value="<?php echo $k['cat_id']; ?>"><?php echo $k['cat_name']; ?></option>
            <?php endwhile; ?>
        </select>

        <label>Taglar (virgül ile ayırın):</label>
        <input type="text" name="tags" placeholder="csv, sağlık, makine öğrenmesi">

        <label>Dosya:</label>
        <input type="file" id="file" name="file">

        <br><br>
        <input type="submit" name="yukle" value="Yükle">
    </form>
</div>

</body>
</html>
