<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "WITH laba_rugi AS (
    SELECT 
        (p.total_pendapatan - h.total_hpp) AS total_laba_rugi
    FROM (
        SELECT 
            COALESCE(SUM(total_pendapatan), 0) AS total_pendapatan
        FROM 
            transaksi_pendapatan
    ) p,
    (
        SELECT 
            (COALESCE(total_bahan_material, 0) + COALESCE(total_upah, 0) + COALESCE(total_biaya_overhead, 0)) AS total_hpp
        FROM (
            SELECT 
                COALESCE(SUM(dpm.jumlah * (
                    SELECT 
                        SUM(dpbm.harga_satuan * dpbm.jumlah) / SUM(dpbm.jumlah)
                    FROM detail_pembelian_bahan_material dpbm
                    WHERE dpbm.id_bahan_material = dpm.id_bahan_material
                )), 0) AS total_bahan_material
            FROM 
                transaksi_penggunaan_bahan_material tpm
            LEFT JOIN 
                detail_penggunaan_bahan_material dpm 
                ON tpm.id_penggunaan_material = dpm.id_penggunaan_material
        ) bb,
        (
            SELECT 
                COALESCE(SUM(tj.subtotal_upah), 0) AS total_upah
            FROM 
                transaksi_pengeluaran_overhead po
            LEFT JOIN 
                detail_barang_jadi_masuk tj 
                ON po.id_pengeluaran_overhead = tj.id_barang_masuk
        ) tk,
        (
            SELECT 
                COALESCE(SUM(oh.biaya_overhead), 0) AS total_biaya_overhead
            FROM 
                transaksi_pengeluaran_overhead po
            LEFT JOIN 
                detail_pengeluaran_overhead oh 
                ON po.id_pengeluaran_overhead = oh.id_pengeluaran_overhead
        ) oh
    ) h
)
SELECT 
    total_laba_rugi AS totalRg
FROM laba_rugi;");
$jsonRespon = array();

if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
} else {
    $jsonRespon[] = array('totalRg' => 0);
}

$response = array(
    'data' => $jsonRespon,
);

echo json_encode($response, JSON_PRETTY_PRINT);
