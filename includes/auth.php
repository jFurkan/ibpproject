<?php
session_start();

function girisYapildiMi(){
    if(isset($_SESSION['user_id'])){
        return true;
    }
    return false;
}

function girisZorunlu(){
    if(!girisYapildiMi()){
        header("Location: /login.php");
        exit();
    }
}
?>
