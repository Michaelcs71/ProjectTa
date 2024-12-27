<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id_pembelian_perlengkapan = $_GET['id'];
    $hasil = mysqli_query($koneksi, "SELECT dp.*, 
        ms.nama_perlengkapan
    FROM 
        detail_pembelian_perlengkapan dp
    LEFT JOIN 
        master_perlengkapan ms ON ms.id_perlengkapan = dp.id_perlengkapan
    WHERE 
        id_pembelian_perlengkapan = '$id_pembelian_perlengkapan'");

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
