<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "SELECT 
    tp.*, 
    ms.nama_pekerja, 
    ma.nama_akun
FROM 
    transaksi_barang_jadi_masuk tp
LEFT JOIN 
    master_pekerja ms ON ms.id_pekerja = tp.id_pekerja
LEFT JOIN 
    master_akun ma ON ma.id_akun = tp.id_akun;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
