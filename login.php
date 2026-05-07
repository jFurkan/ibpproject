<?php
session_start();
include "db.php";

$hata = "";

if(isset($_POST['giris'])){

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if($email == "" || $password == ""){
        $hata = "Tüm alanları doldurun!";
    } else {
        $sifre = sha1($password);
        $sorgu = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND password = '$sifre'");

        if(mysqli_num_rows($sorgu) == 1){
            $kullanici = mysqli_fetch_assoc($sorgu);
            $_SESSION['user_id']  = $kullanici['user_id'];
            $_SESSION['username'] = $kullanici['username'];
            header("Location: index.php");
            exit();
        } else {
            $hata = "Email veya şifre yanlış!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
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
    <h2>Giriş Yap</h2>

    <?php if($hata != "") echo "<p class='hata'>$hata</p>"; ?>

    <form method="POST" onsubmit="return girisDogrula()">
        <label>Email:</label>
        <input type="email" id="email" name="email">

        <label>Şifre:</label>
        <input type="password" id="password" name="password">

        <input type="submit" name="giris" value="Giriş Yap">
    </form>

    <br>
    <a href="register.php">Hesabın yok mu? Kayıt ol</a>
</div>

</body>
</html>
