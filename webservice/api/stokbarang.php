<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "SELECT 
    mbj.id_barang_jadi,
    mbj.nama_barang,
    COALESCE(SUM(dbjm.jumlah), 0) - COALESCE(SUM(dp.total_barang), 0) AS jumlah_stok
FROM 
    master_barang_jadi mbj
LEFT JOIN 
    detail_barang_jadi_masuk dbjm ON mbj.id_barang_jadi = dbjm.id_barang_jadi
LEFT JOIN 
    detail_pendapatan dp ON mbj.id_barang_jadi = dp.id_barang_jadi
GROUP BY 
    mbj.id_barang_jadi, mbj.nama_barang
ORDER BY 
    mbj.nama_barang ASC;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
