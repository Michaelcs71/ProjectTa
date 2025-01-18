<?php
include "../config.php";

if (!isset($_GET['id_pekerja']) || !isset($_GET['id_barang'])) {
    echo json_encode(['error' => 'Invalid parameters']);
    exit;
}

$id_pekerja = $_GET['id_pekerja'];
$id_barang = $_GET['id_barang'];

$query = "SELECT SUM(target_jumlah) AS target_jumlah
FROM transaksi_penggunaan_bahan_material
WHERE id_pekerja = '$id_pekerja' AND id_barang_jadi = '$id_barang'";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . mysqli_error($koneksi)]);
    exit;
}

$data = mysqli_fetch_assoc($result);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

if ($data) {
    echo json_encode($data);
} else {
    echo json_encode(['target_jumlah' => 0]); // Nilai default
}
