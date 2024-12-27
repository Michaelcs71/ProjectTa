<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id_pendapatan = $_GET['id'];


    $hasil = mysqli_query($koneksi, "SELECT 
        dp.*, 
        ms.nama_barang
    FROM 
        detail_pendapatan dp
    LEFT JOIN 
        master_barang_jadi ms ON ms.id_barang_jadi = dp.id_barang_jadi
    WHERE 
        dp.id_pendapatan = '$id_pendapatan'");

    if (!$hasil) {
        die(json_encode(['error' => mysqli_error($koneksi)]));
    }

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
