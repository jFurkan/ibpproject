<?php
include "includes/db.php";
include "includes/functions.php";

if(!isset($_GET['q'])){
    exit();
}

$q = temizle($_GET['q']);

if(strlen($q) < 2){
    exit();
}

$sql = "SELECT d.dataset_id, d.title, c.cat_name FROM datasets d
        JOIN categories c ON d.cat_id = c.cat_id
        WHERE d.title LIKE '%$q%'
        LIMIT 5";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    echo "<p style='color:gray;'>Sonuc bulunamadi.</p>";
} else {
    echo "<div style='border:1px solid #ccc; padding:10px; background:white;'>";
    while($row = mysqli_fetch_assoc($result)){
        echo "<p><a href='pages/dataset.php?id={$row['dataset_id']}' style='text-decoration:none; color:#2c3e50;'>
              {$row['title']} <small>({$row['cat_name']})</small>
              </a></p>";
    }
    echo "</div>";
}
?>
