<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id_barang_masuk = $_GET['id'];
    $hasil = mysqli_query($koneksi, "SELECT dp.*, 
        ms.nama_barang
    FROM
        detail_barang_jadi_masuk dp
    LEFT JOIN
master_barang_jadi ms ON ms.id_barang_jadi = dp.id_barang_jadi
    WHERE
id_barang_masuk = '$id_barang_masuk'");

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
