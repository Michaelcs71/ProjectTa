<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "SELECT 
    bm.id_bahan_material,
    bm.nama_bahan_material,
    COALESCE(masuk.total_masuk, 0) AS total_masuk,
    COALESCE(keluar.total_keluar, 0) AS total_keluar,
    COALESCE(masuk.total_masuk, 0) - COALESCE(keluar.total_keluar, 0) AS total_akhir
FROM 
    master_bahan_material bm
LEFT JOIN (
    SELECT 
        dpbm.id_bahan_material, 
        SUM(dpbm.jumlah) AS total_masuk
    FROM 
        detail_pembelian_bahan_material dpbm
    INNER JOIN 
        transaksi_pembelian_bahan_material tpbm
    ON 
        dpbm.id_pembelian_material = tpbm.id_pembelian_material
    WHERE 
        tpbm.status = 'Aktif'
    GROUP BY 
        dpbm.id_bahan_material
) masuk ON bm.id_bahan_material = masuk.id_bahan_material
LEFT JOIN (
    SELECT 
        dpbm.id_bahan_material, 
        SUM(dpbm.jumlah) AS total_keluar
    FROM 
        detail_penggunaan_bahan_material dpbm
    INNER JOIN 
        transaksi_penggunaan_bahan_material tpbm
    ON 
        dpbm.id_penggunaan_material = tpbm.id_penggunaan_material
    WHERE 
        tpbm.status = 'Aktif'
    GROUP BY 
        dpbm.id_bahan_material
) keluar ON bm.id_bahan_material = keluar.id_bahan_material
ORDER BY 
    bm.nama_bahan_material;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
