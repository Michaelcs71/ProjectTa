<?php
include "../config.php";

// Set header agar respon berupa JSON
header('Content-Type: application/json; charset=utf-8');

// Ambil ID dari parameter
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Query untuk mengambil detail berdasarkan ID
    $query = "SELECT *
              FROM transaksi_penggunaan_bahan_material 
              WHERE id_penggunaan_material = '$id'";
    $result = mysqli_query($koneksi, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['error' => 'Data tidak ditemukan']);
    }
} else {
    // Query untuk mengambil semua ID unik dari tabel
    $query = "SELECT id_penggunaan_material 
              FROM transaksi_penggunaan_bahan_material";
    $result = mysqli_query($koneksi, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data, JSON_PRETTY_PRINT);
}
