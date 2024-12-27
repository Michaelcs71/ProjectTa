<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id_pengeluaran_overhead = $_GET['id'];
    $hasil = mysqli_query($koneksi, "SELECT dp.*, 
        ms.nama_overhead
    FROM 
        detail_pengeluaran_overhead dp
    LEFT JOIN 
        master_overhead ms ON ms.id_overhead = dp.id_overhead
    WHERE 
        id_pengeluaran_overhead = '$id_pengeluaran_overhead'");

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
