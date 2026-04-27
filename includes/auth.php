<?php
session_start();

function girisZorunlu(){
    if(!isset($_SESSION['user_id'])){
        header("Location: /login.php");
        exit();
    }
}
?>
