<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "SELECT 
    tp.*, 
    ma.nama_akun
FROM 
    transaksi_pengeluaran_overhead tp
LEFT JOIN 
    master_akun ma ON ma.id_akun = tp.id_akun;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
