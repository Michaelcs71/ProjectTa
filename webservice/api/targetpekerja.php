<?php
include "../config.php";

$id_pekerja = $_GET['id_pekerja'];

// Query barang berdasarkan pekerja
$query = "SELECT DISTINCT mbj.id_barang_jadi, mbj.nama_barang
          FROM master_barang_jadi mbj
          JOIN transaksi_penggunaan_bahan_material tpm 
          ON mbj.id_barang_jadi = tpm.id_barang_jadi
          WHERE tpm.id_pekerja = '$id_pekerja'";

$result = mysqli_query($koneksi, $query);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
