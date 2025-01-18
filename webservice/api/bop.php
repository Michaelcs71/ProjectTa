<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "SELECT *
FROM 
    transaksi_pengeluaran_overhead tp;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
