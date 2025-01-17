<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "SELECT 
    mbj.id_barang_jadi,
    mbj.nama_barang,
    COALESCE(stok_masuk.total_masuk, 0) AS stok_masuk,
    COALESCE(stok_keluar.total_keluar, 0) AS stok_keluar,
    COALESCE(stok_masuk.total_masuk, 0) - COALESCE(stok_keluar.total_keluar, 0) AS jumlah_stok
FROM 
    master_barang_jadi mbj
LEFT JOIN 
    (
        SELECT 
            id_barang_jadi, 
            SUM(jumlah) AS total_masuk 
        FROM 
            detail_barang_jadi_masuk 
        GROUP BY 
            id_barang_jadi
    ) AS stok_masuk ON mbj.id_barang_jadi = stok_masuk.id_barang_jadi
LEFT JOIN 
    (
        SELECT 
            id_barang_jadi, 
            SUM(total_barang) AS total_keluar 
        FROM 
            detail_pendapatan 
        GROUP BY 
            id_barang_jadi
    ) AS stok_keluar ON mbj.id_barang_jadi = stok_keluar.id_barang_jadi
ORDER BY 
    mbj.id_barang_jadi ASC;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
