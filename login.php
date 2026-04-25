<?php
session_start();
include "includes/db.php";
include "includes/functions.php";

$hata = "";

if(isset($_POST['giris'])){
    $email = temizle($_POST['email']);
    $password = $_POST['password'];

    // PHP dogrulama
    if(empty($email) || empty($password)){
        $hata = "Tum alanlari doldurun!";
    } elseif(strpos($email, '@') === false){
        $hata = "Gecerli bir email girin!";
    } else {
        $hashli_sifre = sha1($password);
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$hashli_sifre'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 1){
            $kullanici = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $kullanici['user_id'];
            $_SESSION['username'] = $kullanici['username'];
            header("Location: index.php");
            exit();
        } else {
            $hata = "Email veya sifre yanlis!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giris Yap</title>
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
    <h2>Giris Yap</h2>

    <?php if($hata != "") echo "<p class='hata'>$hata</p>"; ?>

    <form method="POST" onsubmit="return girisDogrula()">
        <label>Email:</label>
        <input type="email" id="email" name="email">

        <label>Sifre:</label>
        <input type="password" id="password" name="password">

        <input type="submit" name="giris" value="Giris Yap">
    </form>

    <br>
    <a href="register.php">Hesabin yok mu? Kayit ol</a>
</div>

</body>
</html>
