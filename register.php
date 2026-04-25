<?php
session_start();
include "includes/db.php";
include "includes/functions.php";

$hata = "";
$basari = "";

if(isset($_POST['kayit'])){
    $username = temizle($_POST['username']);
    $email = temizle($_POST['email']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    // PHP dogrulama
    if(empty($username) || empty($email) || empty($password)){
        $hata = "Tum alanlari doldurun!";
    } elseif(strlen($username) < 3){
        $hata = "Kullanici adi en az 3 karakter olmali!";
    } elseif(strpos($email, '@') === false){
        $hata = "Gecerli bir email girin!";
    } elseif(strlen($password) < 6){
        $hata = "Sifre en az 6 karakter olmali!";
    } elseif($password != $password2){
        $hata = "Sifreler eslesmyor!";
    } else {
        $kontrol = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        if(mysqli_num_rows($kontrol) > 0){
            $hata = "Bu email zaten kayitli!";
        } else {
            $hashli_sifre = sha1($password);
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashli_sifre')";
            if(mysqli_query($conn, $sql)){
                $basari = "Kayit basarili! <a href='login.php'>Giris yap</a>";
            } else {
                $hata = "Bir hata olustu, tekrar deneyin.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayit Ol</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/validation.js"></script>
</head>
<body>

<div class="navbar">
    <a href="index.php">Ana Sayfa</a>
    <a href="login.php">Giris Yap</a>
    <a href="register.php">Kayit Ol</a>
</div>

<div class="container">
    <h2>Kayit Ol</h2>

    <?php if($hata != "") echo "<p class='hata'>$hata</p>"; ?>
    <?php if($basari != "") echo "<p class='basari'>$basari</p>"; ?>

    <form method="POST" onsubmit="return kayitDogrula()">
        <label>Kullanici Adi:</label>
        <input type="text" id="username" name="username">

        <label>Email:</label>
        <input type="email" id="email" name="email">

        <label>Sifre:</label>
        <input type="password" id="password" name="password">

        <label>Sifre Tekrar:</label>
        <input type="password" id="password2" name="password2">

        <input type="submit" name="kayit" value="Kayit Ol">
    </form>

    <br>
    <a href="login.php">Zaten hesabin var mi? Giris yap</a>
</div>

</body>
</html>
