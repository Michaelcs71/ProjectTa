<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "SELECT 
    mbj.id_barang_jadi,
    mbj.nama_barang,
    COALESCE(stok_masuk.total_masuk, 0) AS stok_masuk,
    COALESCE(stok_keluar.total_keluar, 0) AS stok_keluar,
    COALESCE(stok_masuk.total_masuk, 0) - COALESCE(stok_keluar.total_keluar, 0) AS jumlah_stok,
    COALESCE(proses.target_jumlah, 0) AS barang_dalam_proses
FROM 
    master_barang_jadi mbj
LEFT JOIN 
    (
        SELECT 
            dbjm.id_barang_jadi, 
            SUM(dbjm.jumlah) AS total_masuk 
        FROM 
            detail_barang_jadi_masuk dbjm
        JOIN 
            transaksi_barang_jadi_masuk tbjm 
        ON 
            dbjm.id_barang_masuk = tbjm.id_barang_masuk
        WHERE 
            tbjm.status = 'Aktif'  
        GROUP BY 
            dbjm.id_barang_jadi
    ) AS stok_masuk ON mbj.id_barang_jadi = stok_masuk.id_barang_jadi
LEFT JOIN 
    (
        SELECT 
            dp.id_barang_jadi, 
            SUM(dp.total_barang) AS total_keluar 
        FROM 
            detail_pendapatan dp
        JOIN 
            transaksi_pendapatan tp 
        ON 
            dp.id_pendapatan = tp.id_pendapatan
        WHERE 
            tp.status = 'Aktif' 
        GROUP BY 
            dp.id_barang_jadi
    ) AS stok_keluar ON mbj.id_barang_jadi = stok_keluar.id_barang_jadi
LEFT JOIN 
    (
        SELECT 
            id_barang_jadi, 
            SUM(target_jumlah) AS target_jumlah
        FROM 
            transaksi_penggunaan_bahan_material
        GROUP BY 
            id_barang_jadi
    ) AS proses ON mbj.id_barang_jadi = proses.id_barang_jadi
ORDER BY 
    mbj.id_barang_jadi ASC");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
