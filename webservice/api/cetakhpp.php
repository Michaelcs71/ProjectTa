<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";

$hasil = mysqli_query($koneksi, "WITH overhead_detail AS (
    WITH overhead_per_bulan AS (
        SELECT
            tp.tanggal AS tanggal_periode,
            dp.id_peralatan,
            dp.nilai_penyusutan,
            DATE_ADD(tp.tanggal, INTERVAL dp.bulan_ekonomis MONTH) AS akhir_periode_penyusutan
        FROM 
            transaksi_pengeluaran tp
        LEFT JOIN 
            detail_pengeluaran dp ON tp.id_pengeluaran = dp.id_pengeluaran
        WHERE 
            dp.nilai_penyusutan IS NOT NULL
            AND tp.status = 'Aktif'
    ),
    periode_biaya AS (
        SELECT DISTINCT
            DATE_FORMAT(DATE_ADD(opb.tanggal_periode, INTERVAL n.num MONTH), '%Y-%m') AS periode,
            SUM(opb.nilai_penyusutan) AS total_biaya
        FROM
            overhead_per_bulan opb
        CROSS JOIN (
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
        ) n
        WHERE 
            DATE_ADD(opb.tanggal_periode, INTERVAL n.num MONTH) <= opb.akhir_periode_penyusutan
        GROUP BY 
            DATE_FORMAT(DATE_ADD(opb.tanggal_periode, INTERVAL n.num MONTH), '%Y-%m')
    ),
    overhead_dengan_periode AS (
        SELECT 
            pb.periode,
            'Nilai Penyusutan Peralatan' AS nama_overhead,
            pb.total_biaya,
            NULL AS total_jumlah,
            NULL AS id_bahan_material,
            NULL AS nama_bahan_material,
            NULL AS id_pekerja,
            NULL AS nama_pekerja,
            NULL AS total_upah
        FROM 
            periode_biaya pb
    )
    SELECT * FROM overhead_dengan_periode

    UNION ALL

    SELECT 
        DATE_FORMAT(tpo.tanggal, '%Y-%m') AS periode,
        moh.nama_overhead,
        SUM(dpo.biaya_overhead) AS total_biaya,
        NULL AS total_jumlah,
        NULL AS id_bahan_material,
        NULL AS nama_bahan_material,
        NULL AS id_pekerja,
        NULL AS nama_pekerja,
        NULL AS total_upah
    FROM 
        transaksi_pengeluaran_overhead tpo
    LEFT JOIN 
        detail_pengeluaran_overhead dpo ON tpo.id_pengeluaran_overhead = dpo.id_pengeluaran_overhead
    LEFT JOIN 
        master_overhead moh ON dpo.id_overhead = moh.id_overhead
    WHERE 
        dpo.biaya_overhead IS NOT NULL
        AND tpo.status = 'Aktif'
    GROUP BY 
        DATE_FORMAT(tpo.tanggal, '%Y-%m'), 
        moh.nama_overhead
),
bahan_baku_detail AS (
    SELECT 
        DATE_FORMAT(tpm.tanggal_pengambilan, '%Y-%m') AS periode,
        NULL AS id_overhead,
        NULL AS nama_overhead,
        SUM(dpm.jumlah * COALESCE((
            SELECT 
                SUM(dpbm.harga_satuan * dpbm.jumlah) / NULLIF(SUM(dpbm.jumlah), 0)
            FROM 
                detail_pembelian_bahan_material dpbm
            WHERE 
                dpbm.id_bahan_material = dpm.id_bahan_material
        ), 0)) AS total_biaya,
        SUM(dpm.jumlah) AS total_jumlah,
        dpm.id_bahan_material,
        mbm.nama_bahan_material,
        NULL AS id_pekerja,
        NULL AS nama_pekerja,
        NULL AS total_upah
    FROM 
        transaksi_penggunaan_bahan_material tpm
    LEFT JOIN 
        detail_penggunaan_bahan_material dpm 
        ON tpm.id_penggunaan_material = dpm.id_penggunaan_material
    LEFT JOIN 
        master_bahan_material mbm 
        ON dpm.id_bahan_material = mbm.id_bahan_material
    WHERE 
        dpm.jumlah IS NOT NULL
        AND tpm.status = 'Aktif'
    GROUP BY 
        DATE_FORMAT(tpm.tanggal_pengambilan, '%Y-%m'), 
        dpm.id_bahan_material, 
        mbm.nama_bahan_material
),
upah_tenaga_kerja_detail AS (
    SELECT 
        DATE_FORMAT(tbm.tanggal, '%Y-%m') AS periode,
        NULL AS id_overhead,
        NULL AS nama_overhead,
        NULL AS total_biaya,
        NULL AS total_jumlah,
        NULL AS id_bahan_material,
        NULL AS nama_bahan_material,
        tbm.id_pekerja,
        mp.nama_pekerja,
        SUM(dbdm.subtotal_upah) AS total_upah
    FROM 
        transaksi_barang_jadi_masuk tbm
    LEFT JOIN 
        detail_barang_jadi_masuk dbdm 
        ON tbm.id_barang_masuk = dbdm.id_barang_masuk
    LEFT JOIN 
        master_pekerja mp 
        ON tbm.id_pekerja = mp.id_pekerja
    WHERE 
        dbdm.subtotal_upah IS NOT NULL
        AND tbm.status = 'Aktif'
    GROUP BY 
        DATE_FORMAT(tbm.tanggal, '%Y-%m'), 
        tbm.id_pekerja, 
        mp.nama_pekerja
)
SELECT 
    periode,
    nama_overhead,
    total_biaya,
    total_jumlah,
    id_bahan_material,
    nama_bahan_material,
    id_pekerja,
    nama_pekerja,
    total_upah
FROM 
    overhead_detail
WHERE 
    total_biaya IS NOT NULL
UNION ALL
SELECT 
    periode,
    nama_overhead,
    total_biaya,
    total_jumlah,
    id_bahan_material,
    nama_bahan_material,
    id_pekerja,
    nama_pekerja,
    total_upah
FROM 
    bahan_baku_detail
WHERE 
    total_biaya IS NOT NULL
UNION ALL
SELECT 
    periode,
    nama_overhead,
    total_biaya,
    total_jumlah,
    id_bahan_material,
    nama_bahan_material,
    id_pekerja,
    nama_pekerja,
    total_upah
FROM 
    upah_tenaga_kerja_detail
WHERE 
    total_upah IS NOT NULL
ORDER BY 
    periode, 
    COALESCE(nama_overhead, nama_bahan_material, nama_pekerja);


");


$jsonRespon = array();

if ($hasil) {
    if (mysqli_num_rows($hasil) > 0) {
        while ($row = mysqli_fetch_assoc($hasil)) {
            $jsonRespon[] = $row;
        }
    }
    echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
} else {
    echo json_encode(["error" => mysqli_error($koneksi)], JSON_PRETTY_PRINT);
}
