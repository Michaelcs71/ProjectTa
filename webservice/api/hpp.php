<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "WITH bahan_baku AS (
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
    WHERE 
        tpm.status = 'Aktif' -- Kondisi tambahan status
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
        AND tbm.status = 'Aktif' -- Kondisi tambahan status       
    GROUP BY 
        DATE_FORMAT(tbm.tanggal, '%Y-%m')
),
overhead AS (
    WITH overhead_per_bulan AS (
        SELECT
            po.tanggal AS tanggal_periode,
            DATE_ADD(po.tanggal, INTERVAL oh.bulan_ekonomis MONTH) AS akhir_periode_penyusutan,
            oh.nilai_penyusutan
        FROM 
            transaksi_pengeluaran po
        LEFT JOIN 
            detail_pengeluaran oh ON po.id_pengeluaran = oh.id_pengeluaran
        WHERE 
            po.status = 'Aktif' -- Kondisi tambahan status
    ),
    periode_biaya AS (
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
        WHERE 
            po.status = 'Aktif' -- Kondisi tambahan status
        GROUP BY 
            DATE_FORMAT(po.tanggal, '%Y-%m')
    )
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
    WHERE 
        tbm.status = 'Aktif' -- Kondisi tambahan status
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
ORDER BY periode;
");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
