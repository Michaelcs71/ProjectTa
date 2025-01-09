<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "WITH bahan_baku AS (
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
),
tenaga_kerja AS (
    SELECT 
        COALESCE(SUM(tj.subtotal_upah), 0) AS total_upah
    FROM 
        transaksi_pengeluaran_overhead po
    LEFT JOIN 
        detail_barang_jadi_masuk tj 
        ON po.id_pengeluaran_overhead = tj.id_barang_masuk
),
overhead AS (
    SELECT 
        COALESCE(SUM(oh.biaya_overhead), 0) AS total_biaya_overhead
    FROM 
        transaksi_pengeluaran_overhead po
    LEFT JOIN 
        detail_pengeluaran_overhead oh 
        ON po.id_pengeluaran_overhead = oh.id_pengeluaran_overhead
),
pendapatan AS (
    SELECT 
        SUM(total_pendapatan) AS total_pendapatan
    FROM 
        transaksi_pendapatan
),
hpp AS (
    SELECT 
        'Biaya Bahan Baku' AS keterangan,
        total_bahan_material AS total
    FROM bahan_baku

    UNION ALL

    SELECT 
        'Biaya Tenaga Kerja' AS keterangan,
        total_upah AS total
    FROM tenaga_kerja

    UNION ALL

    SELECT 
        'Biaya Overhead' AS keterangan,
        total_biaya_overhead AS total
    FROM overhead

    UNION ALL

    SELECT 
        'Total HPP' AS keterangan,
        (COALESCE(total_bahan_material, 0) + COALESCE(total_upah, 0) + COALESCE(total_biaya_overhead, 0)) AS total
    FROM (
        SELECT 
            COALESCE(bb.total_bahan_material, 0) AS total_bahan_material,
            COALESCE(tk.total_upah, 0) AS total_upah,
            COALESCE(oh.total_biaya_overhead, 0) AS total_biaya_overhead
        FROM bahan_baku bb
        LEFT JOIN tenaga_kerja tk ON TRUE
        LEFT JOIN overhead oh ON TRUE
    ) AS combined

    UNION ALL

    SELECT 
        'Total Pendapatan' AS keterangan,
        total_pendapatan AS total
    FROM pendapatan
)
SELECT 
    keterangan,
    total
FROM hpp
ORDER BY keterangan;
");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
