<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id_barang_jadi = $_GET['id'];
    $hasil = mysqli_query($koneksi, "SELECT 
    tbjm.tanggal,
    p.nama_pekerja,
    dbjm.subtotal_upah,
    dbjm.jumlah
FROM 
    transaksi_barang_jadi_masuk tbjm
JOIN 
    detail_barang_jadi_masuk dbjm ON tbjm.id_barang_masuk = dbjm.id_barang_masuk
JOIN 
    master_barang_jadi mbj ON dbjm.id_barang_jadi = mbj.id_barang_jadi
JOIN 
    master_pekerja p ON tbjm.id_pekerja = p.id_pekerja
WHERE 
    mbj.id_barang_jadi = '$id_barang_jadi'
ORDER BY 
    tbjm.tanggal ASC");

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
