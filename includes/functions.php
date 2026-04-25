<?php
function temizle($veri){
    $veri = trim($veri);
    $veri = htmlspecialchars($veri);
    return $veri;
}

function dosyaBoyutuFormatla($bytes){
    if($bytes < 1024){
        return $bytes . " B";
    } elseif($bytes < 1048576){
        return round($bytes/1024, 1) . " KB";
    } else {
        return round($bytes/1048576, 1) . " MB";
    }
}

function ortalamaPuan($conn, $dataset_id){
    $sql = "SELECT AVG(rating) as ort FROM ratings WHERE dataset_id = $dataset_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if($row['ort'] == null) return "Henuz puanlanmadi";
    return round($row['ort'], 1) . " / 5";
}

function indirmeSayisi($conn, $dataset_id){
    $sql = "SELECT COUNT(*) as sayi FROM downloads WHERE dataset_id = $dataset_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['sayi'];
}
?>
