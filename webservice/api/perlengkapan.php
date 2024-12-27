<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "SELECT 
    tp.*, 
    ms.nama_satuan
FROM
 master_perlengkapan tp
LEFT JOIN 
    master_satuan ms ON ms.id_satuan = tp.id_satuan;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
