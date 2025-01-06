<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id_barang_jadi = $_GET['id'];
    $hasil = mysqli_query($koneksi, "SELECT 

    tbjm.tanggal_pendapatan,
    p.nama_platform,
    dbjm.total_barang
FROM 
    transaksi_pendapatan tbjm
JOIN 
    detail_pendapatan dbjm ON tbjm.id_pendapatan = dbjm.id_pendapatan
JOIN 
    master_barang_jadi mbj ON dbjm.id_barang_jadi = mbj.id_barang_jadi
JOIN 
    master_platform p ON tbjm.id_platform = p.id_platform
WHERE 
    mbj.id_barang_jadi = '$id_barang_jadi'
ORDER BY 
    tbjm.tanggal_pendapatan ASC");

    $jsonRespon = array();
    if (mysqli_num_rows($hasil) > 0) {
        while ($row = mysqli_fetch_assoc($hasil)) {
            $jsonRespon[] = $row;
        }
    }

    echo json_encode($jsonRespon, JSON_PRETTY_PRINT);
} else {
    echo json_encode(array('error' => 'ID not provided'));
}
