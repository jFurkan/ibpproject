<?php
include "db.php";

$q = trim($_GET['q']);

if(strlen($q) < 2){
    exit();
}

$result = db_query($conn, "SELECT dataset_id, title FROM datasets WHERE title LIKE '%$q%' FETCH FIRST 5 ROWS ONLY");

$rows = [];
while($row = db_fetch($result)) $rows[] = $row;

if(empty($rows)){
    echo "<p style='color:gray;'>Sonuc bulunamadi.</p>";
} else {
    echo "<div style='border:1px solid #ccc; padding:10px; background:white;'>";
    foreach($rows as $row){
        echo "<p><a href='dataset.php?id={$row['dataset_id']}' style='color:#2c3e50;'>{$row['title']}</a></p>";
    }
    echo "</div>";
}
?>
