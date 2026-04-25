<?php
$host = "127.0.0.1";
$dbname = "dataset_site";
$username = "root";
$password = "";
$port = 3307;

$conn = mysqli_connect($host, $username, $password, $dbname, $port);

if(!$conn){
    die("Baglanti hatasi: " . mysqli_connect_error());
}
?>
