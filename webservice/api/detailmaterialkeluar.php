

<?php
include "../config.php";

if (isset($_GET['id'])) {
    $idBahanMaterial = $_GET['id'];
    $hasil = mysqli_query($koneksi, "SELECT 
    tpbm.tanggal_pengambilan, 
    tpbm.estimasi_tanggal_selesai, 
    tpbm.target_jumlah, 
    dpbm.jumlah,
    ms.nama_pekerja
FROM 
    detail_penggunaan_bahan_material dpbm
LEFT JOIN 
    transaksi_penggunaan_bahan_material tpbm
    ON dpbm.id_penggunaan_material = tpbm.id_penggunaan_material
LEFT JOIN 
    master_pekerja ms
    ON tpbm.id_pekerja = ms.id_pekerja
WHERE 
    dpbm.id_bahan_material =  '$idBahanMaterial'");

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
