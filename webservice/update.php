<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";
$time = date('Y-m-d H:i:s'); // Atau format waktu lain sesuai kebutuhan Anda



if (isset($_POST['update_statuspekerja'])) {

    $data = array(
        mysqli_real_escape_string($koneksi, $_POST['id_pekerja']),
        mysqli_real_escape_string($koneksi, $_POST['nama_pekerja']),
        mysqli_real_escape_string($koneksi, $_POST['alamat']),
        mysqli_real_escape_string($koneksi, $_POST['no_telpon']),
        mysqli_real_escape_string($koneksi, $_POST['status']),
    );

    // Call the Insert_Data function to insert data
    Update_Data("master_pekerja", $data,);
    header("Location: " . $baseURL . "/index.php?link=pekerja");
}

if (isset($_POST['update_bahanmaterial'])) {

    $data = array(
        mysqli_real_escape_string($koneksi, $_POST['id_bahan_material']),
        mysqli_real_escape_string($koneksi, $_POST['nama_bahan_material']),
        mysqli_real_escape_string($koneksi, $_POST['status']),
    );

    // Call the Insert_Data function to insert data
    Update_Data("master_bahan_material", $data,);
    header("Location: " . $baseURL . "/index.php?link=bahanmaterial");
}

if (isset($_POST['update_barangjadi'])) {

    $data = array(
        mysqli_real_escape_string($koneksi, $_POST['id_barang_jadi']),
        mysqli_real_escape_string($koneksi, $_POST['nama_barang']),
        mysqli_real_escape_string($koneksi, $_POST['status']),
    );

    // Call the Insert_Data function to insert data
    Update_Data("master_barang_jadi", $data,);
    header("Location: " . $baseURL . "/index.php?link=barangjadi");
}

if (isset($_POST['update_supplier'])) {

    $data = array(
        mysqli_real_escape_string($koneksi, $_POST['id_supplier']),
        mysqli_real_escape_string($koneksi, $_POST['nama_supplier']),
        mysqli_real_escape_string($koneksi, $_POST['alamat']),
        mysqli_real_escape_string($koneksi, $_POST['email']),
        mysqli_real_escape_string($koneksi, $_POST['notelepon']),
        mysqli_real_escape_string($koneksi, $_POST['status']),
    );

    // Call the Insert_Data function to insert data
    Update_Data("master_supplier", $data,);
    header("Location: " . $baseURL . "/index.php?link=supplier");
}

if (isset($_POST['update_kategori'])) {

    $data = array(
        mysqli_real_escape_string($koneksi, $_POST['id_kategori']),
        mysqli_real_escape_string($koneksi, $_POST['nama_kategori']),
        mysqli_real_escape_string($koneksi, $_POST['deskripsi']),
        mysqli_real_escape_string($koneksi, $_POST['status']),
    );

    // Call the Insert_Data function to insert data
    Update_Data("master_kategori", $data,);
    header("Location: " . $baseURL . "/index.php?link=kategori");
}

if (isset($_POST['update_platform'])) {

    $data = array(
        mysqli_real_escape_string($koneksi, $_POST['id_platform']),
        mysqli_real_escape_string($koneksi, $_POST['nama_platform']),
        mysqli_real_escape_string($koneksi, $_POST['status']),
    );

    // Call the Insert_Data function to insert data
    Update_Data("master_platform", $data,);
    header("Location: " . $baseURL . "/index.php?link=platform");
}

if (isset($_POST['update_satuan'])) {

    $data = array(
        mysqli_real_escape_string($koneksi, $_POST['id_satuan']),
        mysqli_real_escape_string($koneksi, $_POST['nama_satuan']),
        mysqli_real_escape_string($koneksi, $_POST['status']),
        'date_created' => $time,
    );

    // Call the Insert_Data function to insert data
    Update_Data("master_satuan", $data,);
    header("Location: " . $baseURL . "/index.php?link=satuan");
}

if (isset($_POST['update_overhead'])) {

    $data = array(
        mysqli_real_escape_string($koneksi, $_POST['id_overhead']),
        mysqli_real_escape_string($koneksi, $_POST['nama_overhead']),
        mysqli_real_escape_string($koneksi, $_POST['status']),
        isset($_POST['date_created']) ? mysqli_real_escape_string($koneksi, $_POST['date_created']) : $time,
    );

    // Call the Insert_Data function to insert data
    Update_Data("master_overhead", $data,);
    header("Location: " . $baseURL . "/index.php?link=overhead");
}

if (isset($_POST['update_akun'])) {

    $data = array(
        mysqli_real_escape_string($koneksi, $_POST['id_akun']),
        mysqli_real_escape_string($koneksi, $_POST['nama_akun']),
        mysqli_real_escape_string($koneksi, $_POST['status']),
        'date_created' => $time,
    );

    // Call the Insert_Data function to insert data
    Update_Data("master_akun", $data,);
    header("Location: " . $baseURL . "/index.php?link=akun");
}

if (isset($_POST['update_perlengkapan'])) {


    // Ambil id_satuan berdasarkan nama_satuan
    $nama_satuan = mysqli_real_escape_string($koneksi, $_POST['nama_satuan']);
    $query = "SELECT id_satuan FROM master_satuan WHERE nama_satuan = '$nama_satuan'";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id_satuan = $row['id_satuan'];

        // Susun data untuk diupdate
        $data = array(
            $_POST['id_perlengkapan'], // ID primary key (index 0)
            $_POST['nama_perlengkapan'], // Nama perlengkapan (index 1)
            $id_satuan, // ID satuan (index 2)
            $_POST['status'], // Status (index 3)
            $time, // Date created (index 4)
        );

        Update_Data("master_perlengkapan", $data);

        // Redirect setelah update
        header("Location: " . $baseURL . "/index.php?link=perlengkapan");
        exit;
    } else {
        echo "Satuan dengan nama '$nama_satuan' tidak ditemukan!";
    }
}

if (isset($_POST['update_peralatan'])) {


    // Ambil id_satuan berdasarkan nama_satuan
    $nama_satuan = mysqli_real_escape_string($koneksi, $_POST['nama_satuan']);
    $query = "SELECT id_satuan FROM master_satuan WHERE nama_satuan = '$nama_satuan'";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id_satuan = $row['id_satuan'];

        // Susun data untuk diupdate
        $data = array(
            $_POST['id_peralatan'], // ID primary key (index 0)
            $_POST['nama_peralatan'], // Nama perlengkapan (index 1)
            $id_satuan, // ID satuan (index 2)
            $_POST['status'], // Status (index 3)
            $time, // Date created (index 4)
        );

        Update_Data("master_peralatan", $data);

        // Redirect setelah update
        header("Location: " . $baseURL . "/index.php?link=peralatan");
        exit;
    } else {
        echo "Satuan dengan nama '$nama_satuan' tidak ditemukan!";
    }
}



if (isset($_POST['update_status_pendapatan'])) {
    // Ambil data dari form
    $id_pendapatan = mysqli_real_escape_string($koneksi, $_POST['id_pendapatan']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    // Data untuk diperbarui
    $data = [
        'status' => $status
    ];

    // Kondisi untuk update
    $conditions = [
        'id_pendapatan' => $id_pendapatan
    ];

    // Panggil fungsi general untuk update data
    Update_Data_Status("transaksi_pendapatan", $data, $conditions);

    // Redirect setelah update berhasil
    header("Location: " . $baseURL . "/index.php?link=pendapatan");
    exit();
}
