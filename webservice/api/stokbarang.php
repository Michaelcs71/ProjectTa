<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "SELECT 
    d.id_barang_jadi AS 'id_barang_jadi',
    b.nama_barang AS 'nama_barang_jadi',
    SUM(d.jumlah) AS 'total'
FROM 
    detail_barang_jadi_masuk d
JOIN 
    transaksi_barang_jadi_masuk t ON d.id_barang_masuk = t.id_barang_masuk
JOIN 
    master_barang_jadi b ON d.id_barang_jadi = b.id_barang_jadi
GROUP BY 
    d.id_barang_jadi, b.nama_barang;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
