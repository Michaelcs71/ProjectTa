<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "WITH pendapatan AS (
    SELECT 
        DATE_FORMAT(tanggal_pendapatan, '%Y-%m') AS periode,
        COALESCE(SUM(total_pendapatan), 0) AS total_pendapatan
    FROM 
        transaksi_pendapatan
    GROUP BY 
        DATE_FORMAT(tanggal_pendapatan, '%Y-%m')
),
bahan_baku AS (
    SELECT 
        DATE_FORMAT(tpm.tanggal_pengambilan, '%Y-%m') AS periode,
        COALESCE(SUM(dpm.jumlah * (
            SELECT 
                SUM(dpbm.harga_satuan * dpbm.jumlah) / NULLIF(SUM(dpbm.jumlah), 0)
            FROM detail_pembelian_bahan_material dpbm
            WHERE dpbm.id_bahan_material = dpm.id_bahan_material
        )), 0) AS total_bahan_material
    FROM 
        transaksi_penggunaan_bahan_material tpm
    LEFT JOIN 
        detail_penggunaan_bahan_material dpm 
        ON tpm.id_penggunaan_material = dpm.id_penggunaan_material
    GROUP BY 
        DATE_FORMAT(tpm.tanggal_pengambilan, '%Y-%m')
),
tenaga_kerja AS (
    SELECT 
        DATE_FORMAT(po.tanggal, '%Y-%m') AS periode,
        COALESCE(SUM(tj.subtotal_upah), 0) AS total_upah
    FROM 
        transaksi_pengeluaran_overhead po
    LEFT JOIN 
        detail_barang_jadi_masuk tj 
        ON po.id_pengeluaran_overhead = tj.id_barang_masuk
    GROUP BY 
        DATE_FORMAT(po.tanggal, '%Y-%m')
),
overhead AS (
    SELECT 
        DATE_FORMAT(po.tanggal, '%Y-%m') AS periode,
        COALESCE(SUM(oh.biaya_overhead), 0) AS total_biaya_overhead
    FROM 
        transaksi_pengeluaran_overhead po
    LEFT JOIN 
        detail_pengeluaran_overhead oh 
        ON po.id_pengeluaran_overhead = oh.id_pengeluaran_overhead
    GROUP BY 
        DATE_FORMAT(po.tanggal, '%Y-%m')
),
barang_jadi AS (
    SELECT 
        DATE_FORMAT(tbm.tanggal, '%Y-%m') AS periode,
        COALESCE(SUM(dj.jumlah), 0) AS total_barang_jadi
    FROM 
        transaksi_barang_jadi_masuk tbm
    LEFT JOIN 
        detail_barang_jadi_masuk dj 
        ON tbm.id_barang_masuk = dj.id_barang_masuk
    GROUP BY 
        DATE_FORMAT(tbm.tanggal, '%Y-%m')
),
hpp AS (
    SELECT 
        periode,
        COALESCE(total_bahan_material, 0) AS total_bahan_baku,
        COALESCE(total_upah, 0) AS total_tenaga_kerja,
        COALESCE(total_biaya_overhead, 0) AS total_overhead,
        (COALESCE(total_bahan_material, 0) + COALESCE(total_upah, 0) + COALESCE(total_biaya_overhead, 0)) AS total_hpp
    FROM (
        SELECT 
            bb.periode AS periode,
            bb.total_bahan_material AS total_bahan_material,
            tk.total_upah AS total_upah,
            oh.total_biaya_overhead AS total_biaya_overhead
        FROM bahan_baku bb
        LEFT JOIN tenaga_kerja tk ON bb.periode = tk.periode
        LEFT JOIN overhead oh ON bb.periode = oh.periode

        UNION

        SELECT 
            tk.periode AS periode,
            bb.total_bahan_material AS total_bahan_material,
            tk.total_upah AS total_upah,
            oh.total_biaya_overhead AS total_biaya_overhead
        FROM tenaga_kerja tk
        LEFT JOIN bahan_baku bb ON tk.periode = bb.periode
        LEFT JOIN overhead oh ON tk.periode = oh.periode

        UNION

        SELECT 
            oh.periode AS periode,
            bb.total_bahan_material AS total_bahan_material,
            tk.total_upah AS total_upah,
            oh.total_biaya_overhead AS total_biaya_overhead
        FROM overhead oh
        LEFT JOIN bahan_baku bb ON oh.periode = bb.periode
        LEFT JOIN tenaga_kerja tk ON oh.periode = tk.periode
    ) AS combined
)
SELECT 
    p.periode,
    COALESCE(p.total_pendapatan, 0) AS total_pendapatan,
    COALESCE(h.total_hpp, 0) AS total_hpp,
    (COALESCE(p.total_pendapatan, 0) - COALESCE(h.total_hpp, 0)) AS laba_rugi_bersih
FROM pendapatan p
LEFT JOIN hpp h ON p.periode = h.periode
ORDER BY p.periode;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
