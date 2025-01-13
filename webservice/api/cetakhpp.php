<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";

$hasil = mysqli_query($koneksi, "
WITH overhead_detail AS (
    SELECT 
        DATE_FORMAT(tpo.tanggal, '%Y-%m') AS periode,
        dpo.id_overhead,
        moh.nama_overhead,
        SUM(dpo.biaya_overhead) AS total_biaya,  -- Alias disesuaikan
        NULL AS total_jumlah,  -- Tambahkan kolom dummy untuk konsistensi struktur
        NULL AS id_bahan_material,  -- Tambahkan kolom dummy
        NULL AS nama_bahan_material  -- Tambahkan kolom dummy
    FROM 
        transaksi_pengeluaran_overhead tpo
    LEFT JOIN 
        detail_pengeluaran_overhead dpo 
        ON tpo.id_pengeluaran_overhead = dpo.id_pengeluaran_overhead
    LEFT JOIN 
        master_overhead moh 
        ON dpo.id_overhead = moh.id_overhead
    WHERE 
        dpo.biaya_overhead IS NOT NULL  -- Hanya ambil data dengan nilai valid
    GROUP BY 
        DATE_FORMAT(tpo.tanggal, '%Y-%m'), 
        dpo.id_overhead, 
        moh.nama_overhead
),
bahan_baku_detail AS (
    SELECT 
        DATE_FORMAT(tpm.tanggal_pengambilan, '%Y-%m') AS periode,
        NULL AS id_overhead,  -- Kolom dummy untuk konsistensi
        NULL AS nama_overhead,  -- Kolom dummy
        SUM(dpm.jumlah * COALESCE((
            SELECT 
                SUM(dpbm.harga_satuan * dpbm.jumlah) / NULLIF(SUM(dpbm.jumlah), 0)
            FROM 
                detail_pembelian_bahan_material dpbm
            WHERE 
                dpbm.id_bahan_material = dpm.id_bahan_material
        ), 0)) AS total_biaya,  -- Alias disesuaikan agar sesuai
        SUM(dpm.jumlah) AS total_jumlah,
        dpm.id_bahan_material,
        mbm.nama_bahan_material
    FROM 
        transaksi_penggunaan_bahan_material tpm
    LEFT JOIN 
        detail_penggunaan_bahan_material dpm 
        ON tpm.id_penggunaan_material = dpm.id_penggunaan_material
    LEFT JOIN 
        master_bahan_material mbm 
        ON dpm.id_bahan_material = mbm.id_bahan_material
    WHERE 
        dpm.jumlah IS NOT NULL  -- Hanya ambil data dengan nilai valid
    GROUP BY 
        DATE_FORMAT(tpm.tanggal_pengambilan, '%Y-%m'), 
        dpm.id_bahan_material, 
        mbm.nama_bahan_material
)
SELECT 
    periode,
    id_overhead,
    nama_overhead,
    total_biaya,
    total_jumlah,
    id_bahan_material,
    nama_bahan_material
FROM 
    overhead_detail
WHERE 
    total_biaya IS NOT NULL  -- Filter hanya data valid
UNION ALL
SELECT 
    periode,
    id_overhead,
    nama_overhead,
    total_biaya,
    total_jumlah,
    id_bahan_material,
    nama_bahan_material
FROM 
    bahan_baku_detail
WHERE 
    total_biaya IS NOT NULL  -- Filter hanya data valid
ORDER BY 
    periode, 
    COALESCE(nama_overhead, nama_bahan_material);
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
