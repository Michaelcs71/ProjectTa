<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "WITH bahan_baku_per_produk AS (
    SELECT 
        tpm.id_penggunaan_material,
        dpm.id_bahan_material,
        dpm.jumlah AS jumlah_bahan,
        (SELECT SUM(dpbm.harga_satuan * dpbm.jumlah) / SUM(dpbm.jumlah)
         FROM detail_pembelian_bahan_material dpbm
         WHERE dpbm.id_bahan_material = dpm.id_bahan_material) AS harga_rata2
    FROM transaksi_penggunaan_bahan_material tpm
    LEFT JOIN detail_penggunaan_bahan_material dpm
        ON tpm.id_penggunaan_material = dpm.id_penggunaan_material
),
produk_terkait AS (
    SELECT 
        dj.id_barang_jadi,
        dj.jumlah AS jumlah_produksi,
        dj.subtotal_upah,
        tbm.tanggal,
        DATE_FORMAT(tbm.tanggal, '%Y-%m') AS periode
    FROM detail_barang_jadi_masuk dj
    LEFT JOIN transaksi_barang_jadi_masuk tbm
        ON dj.id_barang_masuk = tbm.id_barang_masuk
),
total_bahan_per_produk AS (
    SELECT 
        pt.id_barang_jadi,
        pt.periode,
        SUM(bb.jumlah_bahan * bb.harga_rata2) AS total_bahan_baku
    FROM produk_terkait pt
    LEFT JOIN bahan_baku_per_produk bb
        ON pt.id_barang_jadi = bb.id_penggunaan_material
    GROUP BY pt.id_barang_jadi, pt.periode
),
total_overhead_bulanan AS (
    SELECT 
        DATE_FORMAT(tpo.tanggal, '%Y-%m') AS periode,
        SUM(dpo.biaya_overhead) AS total_overhead
    FROM detail_pengeluaran_overhead dpo
    LEFT JOIN transaksi_pengeluaran_overhead tpo
        ON dpo.id_pengeluaran_overhead = tpo.id_pengeluaran_overhead
    GROUP BY DATE_FORMAT(tpo.tanggal, '%Y-%m')
),
total_barang_jadi_bulanan AS (
    SELECT 
        DATE_FORMAT(tbm.tanggal, '%Y-%m') AS periode,
        SUM(dj.jumlah) AS total_barang_jadi
    FROM detail_barang_jadi_masuk dj
    LEFT JOIN transaksi_barang_jadi_masuk tbm
        ON dj.id_barang_masuk = tbm.id_barang_masuk
    GROUP BY DATE_FORMAT(tbm.tanggal, '%Y-%m')
),
overhead_per_unit AS (
    SELECT 
        tob.periode,
        tob.total_overhead / tbj.total_barang_jadi AS overhead_per_unit
    FROM total_overhead_bulanan tob
    LEFT JOIN total_barang_jadi_bulanan tbj
        ON tob.periode = tbj.periode
),
hpp_per_produk AS (
    SELECT 
        pt.id_barang_jadi,
        pt.periode,
        mbj.nama_barang,
        pt.jumlah_produksi,
        tbp.total_bahan_baku,
        SUM(pt.subtotal_upah) AS total_upah,
        (opu.overhead_per_unit * pt.jumlah_produksi) AS total_overhead,
        (tbp.total_bahan_baku + SUM(pt.subtotal_upah) + (opu.overhead_per_unit * pt.jumlah_produksi)) / pt.jumlah_produksi AS hpp_per_unit
    FROM produk_terkait pt
    LEFT JOIN total_bahan_per_produk tbp
        ON pt.id_barang_jadi = tbp.id_barang_jadi AND pt.periode = tbp.periode
    LEFT JOIN master_barang_jadi mbj
        ON pt.id_barang_jadi = mbj.id_barang_jadi
    LEFT JOIN overhead_per_unit opu
        ON pt.periode = opu.periode
    GROUP BY pt.id_barang_jadi, pt.periode
)
SELECT 
    periode,
    nama_barang,
    hpp_per_unit
FROM hpp_per_produk
ORDER BY periode, id_barang_jadi;");

$jsonRespon = array();
if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
