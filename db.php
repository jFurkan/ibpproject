<?php
// Oracle baglanti bilgilerini kendi kurulumunuza gore degistirin
$conn = oci_connect('SYSTEM', 'oracle', 'localhost/XE');
if(!$conn){
    $e = oci_error();
    die("Veritabani baglantisi basarisiz: " . $e['message']);
}

$stmt = oci_parse($conn, "ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'");
oci_execute($stmt);

function db_query($conn, $sql){
    $stmt = oci_parse($conn, $sql);
    if(!oci_execute($stmt, OCI_COMMIT_ON_SUCCESS)){
        $e = oci_error($stmt);
        die("Sorgu hatasi: " . $e['message']);
    }
    return $stmt;
}

function db_fetch($stmt){
    $row = oci_fetch_assoc($stmt);
    if($row) return array_change_key_case($row, CASE_LOWER);
    return false;
}

function db_insert_id($conn, $sql, $id_col){
    $sql .= " RETURNING $id_col INTO :ret_id";
    $stmt = oci_parse($conn, $sql);
    $new_id = 0;
    oci_bind_by_name($stmt, ':ret_id', $new_id, -1, SQLT_INT);
    oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
    return (int)$new_id;
}
?>
