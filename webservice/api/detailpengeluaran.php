<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id_pengeluaran = $_GET['id'];
    $hasil = mysqli_query($koneksi, "SELECT dp.*, 
        ms.nama_peralatan
    FROM
        detail_pengeluaran dp
    LEFT JOIN
master_peralatan ms ON ms. id_peralatan = dp. id_peralatan
    WHERE
id_pengeluaran = '$id_pengeluaran'");

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
