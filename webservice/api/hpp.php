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
    WITH overhead_per_bulan AS (
    -- Menghasilkan data overhead berdasarkan bulan ekonomis
    SELECT
        po.tanggal AS tanggal_periode,
        DATE_ADD(po.tanggal, INTERVAL oh.bulan_ekonomis MONTH) AS akhir_periode_penyusutan,
        oh.nilai_penyusutan
    FROM 
        transaksi_pengeluaran po
    LEFT JOIN 
        detail_pengeluaran oh ON po.id_pengeluaran = oh.id_pengeluaran
),
periode_biaya AS (
    -- Membuat data per bulan dari awal hingga akhir periode penyusutan
    SELECT
        DATE_FORMAT(DATE_ADD(opb.tanggal_periode, INTERVAL n.num MONTH), '%Y-%m') AS periode,
        opb.nilai_penyusutan
    FROM
        overhead_per_bulan opb
    JOIN (
        SELECT 0 AS num
        UNION ALL SELECT 1
        UNION ALL SELECT 2
        UNION ALL SELECT 3
        UNION ALL SELECT 4
        UNION ALL SELECT 5
        UNION ALL SELECT 6
        UNION ALL SELECT 7
        UNION ALL SELECT 8
        UNION ALL SELECT 9
        UNION ALL SELECT 10
        UNION ALL SELECT 11
    ) n ON DATE_ADD(opb.tanggal_periode, INTERVAL n.num MONTH) <= opb.akhir_periode_penyusutan
),
total_biaya_overhead AS (
    -- Menyusun total overhead termasuk overhead lain
    SELECT
        p.periode,
        SUM(p.nilai_penyusutan) AS total_biaya
    FROM
        periode_biaya p
    GROUP BY
        p.periode
    UNION ALL
    SELECT
        DATE_FORMAT(po.tanggal, '%Y-%m') AS periode,
        COALESCE(SUM(oh.biaya_overhead), 0) AS total_biaya
    FROM 
        transaksi_pengeluaran_overhead po
    LEFT JOIN 
        detail_pengeluaran_overhead oh ON po.id_pengeluaran_overhead = oh.id_pengeluaran_overhead
    GROUP BY 
        DATE_FORMAT(po.tanggal, '%Y-%m')
)
-- Menggabungkan dan menghitung total biaya overhead per bulan
SELECT
    periode,
    SUM(total_biaya) AS total_biaya_overhead
FROM
    total_biaya_overhead
GROUP BY
    periode
ORDER BY
    periode
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
    COALESCE(total_bahan_material, 0) AS total_bahan_baku,
    COALESCE(total_upah, 0) AS total_tenaga_kerja,
    COALESCE(total_biaya_overhead, 0) AS total_overhead,
    COALESCE(total_barang_jadi, 0) AS total_barang_jadi,
    (COALESCE(total_bahan_material, 0) + COALESCE(total_upah, 0) + COALESCE(total_biaya_overhead, 0)) AS total_hpp
FROM (
    SELECT 
        bb.periode AS periode,
        bb.total_bahan_material AS total_bahan_material,
        tk.total_upah AS total_upah,
        oh.total_biaya_overhead AS total_biaya_overhead,
        bj.total_barang_jadi AS total_barang_jadi
    FROM bahan_baku bb
    LEFT JOIN tenaga_kerja tk ON bb.periode = tk.periode
    LEFT JOIN overhead oh ON bb.periode = oh.periode
    LEFT JOIN barang_jadi bj ON bb.periode = bj.periode

    UNION

    SELECT 
        tk.periode AS periode,
        bb.total_bahan_material AS total_bahan_material,
        tk.total_upah AS total_upah,
        oh.total_biaya_overhead AS total_biaya_overhead,
        bj.total_barang_jadi AS total_barang_jadi
    FROM tenaga_kerja tk
    LEFT JOIN bahan_baku bb ON tk.periode = bb.periode
    LEFT JOIN overhead oh ON tk.periode = oh.periode
    LEFT JOIN barang_jadi bj ON tk.periode = bj.periode

    UNION

    SELECT 
        oh.periode AS periode,
        bb.total_bahan_material AS total_bahan_material,
        tk.total_upah AS total_upah,
        oh.total_biaya_overhead AS total_biaya_overhead,
        bj.total_barang_jadi AS total_barang_jadi
    FROM overhead oh
    LEFT JOIN bahan_baku bb ON oh.periode = bb.periode
    LEFT JOIN tenaga_kerja tk ON oh.periode = tk.periode
    LEFT JOIN barang_jadi bj ON oh.periode = bj.periode

    UNION

    SELECT 
        bj.periode AS periode,
        bb.total_bahan_material AS total_bahan_material,
        tk.total_upah AS total_upah,
        oh.total_biaya_overhead AS total_biaya_overhead,
        bj.total_barang_jadi AS total_barang_jadi
    FROM barang_jadi bj
    LEFT JOIN bahan_baku bb ON bj.periode = bb.periode
    LEFT JOIN tenaga_kerja tk ON bj.periode = tk.periode
    LEFT JOIN overhead oh ON bj.periode = oh.periode
) AS combined
ORDER BY periode;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
