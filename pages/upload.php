<?php
session_start();
include "../includes/db.php";
include "../includes/auth.php";
include "../includes/functions.php";

girisZorunlu();

$hata = "";
$basari = "";

// Kategorileri getir
$kat_result = mysqli_query($conn, "SELECT * FROM categories");

if(isset($_POST['yukle'])){
    $title = temizle($_POST['title']);
    $description = temizle($_POST['description']);
    $cat_id = temizle($_POST['cat_id']);
    $tags = temizle($_POST['tags']);

    // PHP dogrulama
    if(empty($title)){
        $hata = "Baslik bos olamaz!";
    } elseif(empty($cat_id)){
        $hata = "Kategori secin!";
    } elseif(!isset($_FILES['file']) || $_FILES['file']['error'] != 0){
        $hata = "Lutfen bir dosya secin!";
    } else {
        $dosya_adi = $_FILES['file']['name'];
        $dosya_boyutu = $_FILES['file']['size'];
        $gecici = $_FILES['file']['tmp_name'];

        // Dosyayi kaydet
        $yeni_isim = time() . "_" . $dosya_adi;
        $hedef = "../uploads/" . $yeni_isim;

        if(!is_dir("../uploads/")){
            mkdir("../uploads/");
        }

        if(move_uploaded_file($gecici, $hedef)){
            $user_id = $_SESSION['user_id'];
            $sql = "INSERT INTO datasets (user_id, cat_id, title, description, filename, filesize)
                    VALUES ('$user_id', '$cat_id', '$title', '$description', '$yeni_isim', '$dosya_boyutu')";

            if(mysqli_query($conn, $sql)){
                $dataset_id = mysqli_insert_id($conn);

                // Tagleri ekle
                if($tags != ""){
                    $tag_listesi = explode(",", $tags);
                    foreach($tag_listesi as $tag){
                        $tag = trim(temizle($tag));
                        if($tag == "") continue;

                        // Tag var mi kontrol et
                        $tag_kontrol = mysqli_query($conn, "SELECT tag_id FROM tags WHERE tag_name = '$tag'");
                        if(mysqli_num_rows($tag_kontrol) > 0){
                            $tag_row = mysqli_fetch_assoc($tag_kontrol);
                            $tag_id = $tag_row['tag_id'];
                        } else {
                            mysqli_query($conn, "INSERT INTO tags (tag_name) VALUES ('$tag')");
                            $tag_id = mysqli_insert_id($conn);
                        }
                        mysqli_query($conn, "INSERT INTO dataset_tags (dataset_id, tag_id) VALUES ('$dataset_id', '$tag_id')");
                    }
                }

                $basari = "Dataset yuklendi! <a href='dataset.php?id=$dataset_id'>Goruntule</a>";
            } else {
                $hata = "Veritabani hatasi!";
            }
        } else {
            $hata = "Dosya yuklenemedi!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Dataset Yukle</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/validation.js"></script>
</head>
<body>

<div class="navbar">
    <a href="../index.php">Ana Sayfa</a>
    <a href="upload.php">Dataset Yukle</a>
    <a href="profile.php">Profilim</a>
    <a href="../logout.php">Cikis Yap</a>
</div>

<div class="container">
    <h2>Dataset Yukle</h2>

    <?php if($hata != "") echo "<p class='hata'>$hata</p>"; ?>
    <?php if($basari != "") echo "<p class='basari'>$basari</p>"; ?>

    <form method="POST" enctype="multipart/form-data" onsubmit="return uploadDogrula()">
        <label>Baslik:</label>
        <input type="text" id="title" name="title">

        <label>Aciklama:</label>
        <textarea name="description" rows="4"></textarea>

        <label>Kategori:</label>
        <select name="cat_id">
            <option value="">Secin...</option>
            <?php while($kat = mysqli_fetch_assoc($kat_result)): ?>
                <option value="<?php echo $kat['cat_id']; ?>"><?php echo $kat['cat_name']; ?></option>
            <?php endwhile; ?>
        </select>

        <label>Tagler (virgul ile ayirin):</label>
        <input type="text" name="tags" placeholder="ornek: csv, makine ogrenimi, saglik">

        <label>Dosya Sec:</label>
        <input type="file" id="file" name="file">

        <br><br>
        <input type="submit" name="yukle" value="Yukle">
    </form>
</div>

</body>
</html>
