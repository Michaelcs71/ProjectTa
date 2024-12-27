<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "SELECT 
    tp.*, 
    ms.nama_supplier, 
    ma.nama_akun
FROM 
    transaksi_pembelian_perlengkapan tp
LEFT JOIN 
    master_supplier ms ON ms.id_supplier = tp.id_supplier
LEFT JOIN 
    master_akun ma ON ma.id_akun = tp.id_akun;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
