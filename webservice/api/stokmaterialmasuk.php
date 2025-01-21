<?php
include "../config.php";

// Set header agar respon berupa JSON
header('Content-Type: application/json; charset=utf-8');

// Ambil ID dari parameter
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $query = "SELECT id_pembelian_material, id_supplier, tanggal, total_biaya, status 
              FROM transaksi_pembelian_bahan_material 
              WHERE id_pembelian_material = '$id'";
    $result = mysqli_query($koneksi, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['error' => 'Data tidak ditemukan']);
    }
} else {
    // Jika tidak ada ID, kembalikan semua data
    $query = "SELECT id_pembelian_material FROM transaksi_pembelian_bahan_material";
    $result = mysqli_query($koneksi, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data, JSON_PRETTY_PRINT);
}
