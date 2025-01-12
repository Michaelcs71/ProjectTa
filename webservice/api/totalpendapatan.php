<?php
include "../config.php";

$hasil = mysqli_query($koneksi, "SELECT SUM(total_pendapatan) AS total_pendapatan FROM transaksi_pendapatan");
$jsonRespon = array();

if (mysqli_num_rows($hasil) > 0) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $jsonRespon[] = $row;
    }
} else {
    $jsonRespon[] = array('total_pendapatan' => 0);
}

$response = array(
    'data' => $jsonRespon,
);

echo json_encode($response, JSON_PRETTY_PRINT);
