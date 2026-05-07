<?php
include "db.php";

$q = trim($_GET['q']);

if(strlen($q) < 2){
    exit();
}

$result = mysqli_query($conn, "SELECT dataset_id, title FROM datasets WHERE title LIKE '%$q%' LIMIT 5");

if(mysqli_num_rows($result) == 0){
    echo "<p style='color:gray;'>Sonuç bulunamadı.</p>";
} else {
    echo "<div style='border:1px solid #ccc; padding:10px; background:white;'>";
    while($row = mysqli_fetch_assoc($result)){
        echo "<p><a href='dataset.php?id={$row['dataset_id']}' style='color:#2c3e50;'>{$row['title']}</a></p>";
    }
    echo "</div>";
}
?>
