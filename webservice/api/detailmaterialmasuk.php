

<?php
include "../config.php";

if (isset($_GET['id'])) {
    $idBahanMaterial = $_GET['id'];
    $hasil = mysqli_query($koneksi, "SELECT 
    tpbm.tanggal AS tanggal_masuk, 
    dpbm.jumlah,
    dpbm.harga_satuan,
    dpbm.sub_total,
    ms.nama_supplier
FROM 
    detail_pembelian_bahan_material dpbm
LEFT JOIN 
    transaksi_pembelian_bahan_material tpbm
    ON dpbm.id_pembelian_material = tpbm.id_pembelian_material
LEFT JOIN 
    master_supplier ms
    ON tpbm.id_supplier = ms.id_supplier
WHERE 
    dpbm.id_bahan_material = '$idBahanMaterial'");

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
