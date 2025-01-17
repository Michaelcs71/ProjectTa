<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "
WITH bahan_baku_per_produk AS (
    SELECT 
        tpm.id_barang_jadi,
        dpm.id_bahan_material,
        dpm.jumlah AS jumlah_bahan,
        (
            SELECT 
                SUM(dpbm.harga_satuan * dpbm.jumlah) / SUM(dpbm.jumlah)
            FROM detail_pembelian_bahan_material dpbm
            WHERE dpbm.id_bahan_material = dpm.id_bahan_material
        ) AS harga_rata2
    FROM transaksi_penggunaan_bahan_material tpm
    LEFT JOIN detail_penggunaan_bahan_material dpm
        ON tpm.id_penggunaan_material = dpm.id_penggunaan_material
),
total_bahan_per_produk AS (
    SELECT 
        bb.id_barang_jadi,
        COALESCE(SUM(bb.jumlah_bahan * bb.harga_rata2), 0) AS total_bahan_baku
    FROM bahan_baku_per_produk bb
    GROUP BY bb.id_barang_jadi
),
total_overhead_bulanan AS (
    SELECT
        DATE_FORMAT(tp.tanggal, '%Y-%m') AS periode,
        COALESCE(SUM(dp.nilai_penyusutan), 0) AS total_overhead
    FROM transaksi_pengeluaran tp
    LEFT JOIN detail_pengeluaran dp 
        ON tp.id_pengeluaran = dp.id_pengeluaran
    GROUP BY DATE_FORMAT(tp.tanggal, '%Y-%m')
),
total_upah_bulanan AS (
    SELECT 
        DATE_FORMAT(tbm.tanggal, '%Y-%m') AS periode,
        dj.id_barang_jadi,
        COALESCE(SUM(dj.subtotal_upah), 0) AS total_upah
    FROM detail_barang_jadi_masuk dj
    LEFT JOIN transaksi_barang_jadi_masuk tbm
        ON dj.id_barang_masuk = tbm.id_barang_masuk
    GROUP BY DATE_FORMAT(tbm.tanggal, '%Y-%m'), dj.id_barang_jadi
),
produksi_bulanan AS (
    SELECT 
        DATE_FORMAT(tbm.tanggal, '%Y-%m') AS periode,
        dj.id_barang_jadi,
        COALESCE(SUM(dj.jumlah), 0) AS jumlah_produksi
    FROM detail_barang_jadi_masuk dj
    LEFT JOIN transaksi_barang_jadi_masuk tbm
        ON dj.id_barang_masuk = tbm.id_barang_masuk
    GROUP BY DATE_FORMAT(tbm.tanggal, '%Y-%m'), dj.id_barang_jadi
),
hpp_per_produk AS (
    SELECT 
        p.periode,
        p.id_barang_jadi,
        mbj.nama_barang,
        p.jumlah_produksi,
        COALESCE(tbp.total_bahan_baku, 0) AS total_bahan_baku,
        COALESCE(tu.total_upah, 0) AS total_upah,
        (COALESCE(toh.total_overhead, 0) / NULLIF(SUM(p.jumlah_produksi) OVER (PARTITION BY p.periode), 0)) * p.jumlah_produksi AS total_overhead,
        (
            COALESCE(tbp.total_bahan_baku, 0) +
            COALESCE(tu.total_upah, 0) +
            (COALESCE(toh.total_overhead, 0) / NULLIF(SUM(p.jumlah_produksi) OVER (PARTITION BY p.periode), 0)) * p.jumlah_produksi
        ) / NULLIF(p.jumlah_produksi, 0) AS hpp_per_unit
    FROM produksi_bulanan p
    LEFT JOIN total_bahan_per_produk tbp
        ON p.id_barang_jadi = tbp.id_barang_jadi
    LEFT JOIN total_upah_bulanan tu
        ON p.periode = tu.periode AND p.id_barang_jadi = tu.id_barang_jadi
    LEFT JOIN total_overhead_bulanan toh
        ON p.periode = toh.periode
    LEFT JOIN master_barang_jadi mbj
        ON p.id_barang_jadi = mbj.id_barang_jadi
)
SELECT 
    periode,
    id_barang_jadi,
    nama_barang,
    jumlah_produksi,
    total_bahan_baku,
    total_upah,
    total_overhead,
    hpp_per_unit
FROM hpp_per_produk
ORDER BY periode, id_barang_jadi;
");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}


echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
