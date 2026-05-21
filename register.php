<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
include "db.php";

$hata   = "";
$basari = "";

if(isset($_POST['kayit'])){

    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];
    $password2 = $_POST['password2'];

    if($username == "" || $email == "" || $password == ""){
        $hata = "Tüm alanları doldurun!";
    } elseif(strlen($password) < 6){
        $hata = "Şifre en az 6 karakter olmalı!";
    } elseif($password != $password2){
        $hata = "Şifreler eşleşmedi!";
    } else {
        $kontrol = db_query($conn, "SELECT user_id FROM users WHERE email = '$email'");
        if(db_fetch($kontrol)){
            $hata = "Bu email zaten kayıtlı!";
        } else {
            $sifre = sha1($password);
            db_query($conn, "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$sifre')");
            $basari = "Kayıt başarılı! <a href='login.php'>Giriş yap</a>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>

<div class="navbar">
    <a href="index.php">Ana Sayfa</a>
    <a href="login.php">Giriş Yap</a>
    <a href="register.php">Kayıt Ol</a>
</div>

<div class="container">
    <h2>Kayıt Ol</h2>

    <?php if($hata != "") echo "<p class='hata'>$hata</p>"; ?>
    <?php if($basari != "") echo "<p class='basari'>$basari</p>"; ?>

    <form method="POST" onsubmit="return kayitDogrula()">
        <label>Kullanıcı Adı:</label>
        <input type="text" id="username" name="username">

        <label>Email:</label>
        <input type="email" id="email" name="email">

        <label>Şifre:</label>
        <input type="password" id="password" name="password">

        <label>Şifre Tekrar:</label>
        <input type="password" id="password2" name="password2">

        <input type="submit" name="kayit" value="Kayıt Ol">
    </form>

    <br>
    <a href="login.php">Zaten hesabın var mı? Giriş yap</a>
</div>

</body>
</html>
