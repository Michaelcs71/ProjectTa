<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "WITH bahan_baku AS (
    SELECT 
        DATE_FORMAT(tpm.tanggal_pengambilan, '%Y-%m') AS periode,
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
    GROUP BY 
        DATE_FORMAT(tpm.tanggal_pengambilan, '%Y-%m')
),
tenaga_kerja AS (
    SELECT 
        DATE_FORMAT(tbm.tanggal, '%Y-%m') AS periode, 
        COALESCE(SUM(dbdm.subtotal_upah), 0) AS total_upah 
    FROM 
        transaksi_barang_jadi_masuk tbm
    LEFT JOIN 
        detail_barang_jadi_masuk dbdm 
        ON tbm.id_barang_masuk = dbdm.id_barang_masuk
    WHERE 
        dbdm.subtotal_upah IS NOT NULL              
    GROUP BY 
        DATE_FORMAT(tbm.tanggal, '%Y-%m')
),
overhead AS (
    -- Bagian overhead
    SELECT
        periode,
        SUM(total_biaya) AS total_biaya_overhead
    FROM (
        SELECT
            DATE_FORMAT(po.tanggal, '%Y-%m') AS periode,
            COALESCE(SUM(oh.nilai_penyusutan), 0) AS total_biaya
        FROM transaksi_pengeluaran po
        LEFT JOIN detail_pengeluaran oh 
            ON po.id_pengeluaran = oh.id_pengeluaran
        GROUP BY DATE_FORMAT(po.tanggal, '%Y-%m')
        UNION ALL
        SELECT
            DATE_FORMAT(po.tanggal, '%Y-%m') AS periode,
            COALESCE(SUM(oh.biaya_overhead), 0) AS total_biaya
        FROM transaksi_pengeluaran_overhead po
        LEFT JOIN detail_pengeluaran_overhead oh 
            ON po.id_pengeluaran_overhead = oh.id_pengeluaran_overhead
        GROUP BY DATE_FORMAT(po.tanggal, '%Y-%m')
    ) AS total_biaya_overhead
    GROUP BY periode
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
)
SELECT 
    periode,
    total_bahan_material AS total_bahan_baku,
    total_upah AS total_tenaga_kerja,
    total_biaya_overhead AS total_overhead,
    total_barang_jadi,
    (total_bahan_material + total_upah + total_biaya_overhead) AS total_hpp
FROM (
    SELECT 
        bb.periode AS periode,
        bb.total_bahan_material,
        tk.total_upah,
        oh.total_biaya_overhead,
        bj.total_barang_jadi
    FROM bahan_baku bb
    JOIN tenaga_kerja tk ON bb.periode = tk.periode
    JOIN overhead oh ON bb.periode = oh.periode
    JOIN barang_jadi bj ON bb.periode = bj.periode
) AS combined
WHERE 
    total_bahan_material > 0 
    AND total_upah > 0 
    AND total_biaya_overhead > 0
ORDER BY periode;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
