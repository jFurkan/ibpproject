<?php
session_start();
include "includes/db.php";

$hata = "";

if(isset($_POST['giris'])){

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if($email == "" || $password == ""){
        $hata = "Tum alanlari doldurun!";
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
            $hata = "Email veya sifre yanlis!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Giris Yap</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
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
