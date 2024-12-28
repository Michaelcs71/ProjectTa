<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/webservice/config.php";
include $_SERVER['DOCUMENT_ROOT'] . "/ProjectTa/lib/function.php";

$baseURL = "http://localhost/ProjectTa"; // Pastikan URL ini sesuai dengan path proyek Anda
date_default_timezone_set('Asia/Jakarta');
$time = date('Y-m-d H:i:s'); // Atau format waktu lain sesuai kebutuhan Anda


if (isset($_POST['insert_pekerja'])) {
    $data = array(
        'nama_pekerja' => mysqli_real_escape_string($koneksi, $_POST['nama_pekerja']),
        'no_telpon' => mysqli_real_escape_string($koneksi, $_POST['notelp']),
        'alamat' => mysqli_real_escape_string($koneksi, $_POST['alamat']),
        'status' => mysqli_real_escape_string($koneksi, $_POST['status']),
    );

    // Call the Insert_Data function to insert data
    Insert_Data("master_pekerja", $data);
    header("Location: " . $baseURL . "/index.php?link=pekerja");
    exit();
}

if (isset($_POST['insert_bahanmaterial'])) {
    $data = array(
        'nama_bahan_material' => mysqli_real_escape_string($koneksi, $_POST['nama_bahanmaterial']),
        'id_satuan' => mysqli_real_escape_string($koneksi, $_POST['nama_satuan']),
        'status' => mysqli_real_escape_string($koneksi, $_POST['status']),
    );

    // Call the Insert_Data function to insert data
    Insert_Data("master_bahan_material", $data);
    header("Location: " . $baseURL . "/index.php?link=bahanmaterial");
    exit();
}

if (isset($_POST['insert_barangjadi'])) {
    $data = array(
        'nama_barang' => mysqli_real_escape_string($koneksi, $_POST['nama_barangjadi']),
        'harga_terendah' => mysqli_real_escape_string($koneksi, $_POST['hargaterendah']),
        'persentase_upah' => mysqli_real_escape_string($koneksi, $_POST['perupah']),
        'status' => mysqli_real_escape_string($koneksi, $_POST['status']),
        'date_created' => $time,
    );

    // Call the Insert_Data function to insert data
    Insert_Data("master_barang_jadi", $data);
    header("Location: " . $baseURL . "/index.php?link=barangjadi");
    exit();
}

if (isset($_POST['insert_supplier'])) {
    $data = array(
        'nama_supplier' => mysqli_real_escape_string($koneksi, $_POST['nama_supplier']),
        'no_telepon' => mysqli_real_escape_string($koneksi, $_POST['notelepon']),
        'alamat' => mysqli_real_escape_string($koneksi, $_POST['alamat']),
        'email' => mysqli_real_escape_string($koneksi, $_POST['email']),
        'status' => mysqli_real_escape_string($koneksi, $_POST['status']),
    );

    // Call the Insert_Data function to insert data
    Insert_Data("master_supplier", $data);
    header("Location: " . $baseURL . "/index.php?link=supplier");
    exit();
}

if (isset($_POST['insert_kategori'])) {
    $data = array(
        'nama_kategori' => mysqli_real_escape_string($koneksi, $_POST['nama_kategori']),
        'deskripsi' => mysqli_real_escape_string($koneksi, $_POST['deskripsi']),
        'status' => mysqli_real_escape_string($koneksi, $_POST['status']),
    );

    // Call the Insert_Data function to insert data
    Insert_Data("master_kategori", $data);
    header("Location: " . $baseURL . "/index.php?link=kategori");
    exit();
}

if (isset($_POST['insert_platform'])) {
    $data = array(

        'nama_platform' => mysqli_real_escape_string($koneksi, $_POST['nama_platform']),
        'status' => mysqli_real_escape_string($koneksi, $_POST['status']),
        'date_created' => $time,
    );

    // Call the Insert_Data function to insert data
    Insert_Data("master_platform", $data);
    header("Location: " . $baseURL . "/index.php?link=platform");
    exit();
}

if (isset($_POST['insert_satuan'])) {
    $data = array(
        'nama_satuan' => mysqli_real_escape_string($koneksi, $_POST['nama_satuan']),
        'status' => mysqli_real_escape_string($koneksi, $_POST['status']),
        'date_created' => $time,
    );

    // Call the Insert_Data function to insert data
    Insert_Data("master_satuan", $data);
    header("Location: " . $baseURL . "/index.php?link=satuan");
    exit();
}

if (isset($_POST['insert_overhead'])) {
    $data = array(
        'nama_overhead' => mysqli_real_escape_string($koneksi, $_POST['nama_overhead']),
        'status' => mysqli_real_escape_string($koneksi, $_POST['status']),
        'date_created' => $time,
    );

    // Call the Insert_Data function to insert data
    Insert_Data("master_overhead", $data);
    header("Location: " . $baseURL . "/index.php?link=overhead");
    exit();
}

if (isset($_POST['insert_akun'])) {
    $data = array(
        'id_akun' => mysqli_real_escape_string($koneksi, $_POST['id_akun']),
        'nama_akun' => mysqli_real_escape_string($koneksi, $_POST['nama_akun']),
        'status' => mysqli_real_escape_string($koneksi, $_POST['status']),
        'date_created' => $time,
    );

    // Call the Insert_Data function to insert data
    Insert_Data("master_akun", $data);
    header("Location: " . $baseURL . "/index.php?link=akun");
    exit();
}

if (isset($_POST['insert_perlengkapan'])) {
    $data = array(

        'nama_perlengkapan' => mysqli_real_escape_string($koneksi, $_POST['nama_perlengkapan']),
        'id_satuan' => mysqli_real_escape_string($koneksi, $_POST['nama_satuan']),
        'status' => mysqli_real_escape_string($koneksi, $_POST['status']),
        'date_created' => $time,
    );

    // Call the Insert_Data function to insert data
    Insert_Data("master_perlengkapan", $data);
    header("Location: " . $baseURL . "/index.php?link=perlengkapan");
    exit();
}

if (isset($_POST['insert_peralatan'])) {
    $data = array(

        'nama_peralatan' => mysqli_real_escape_string($koneksi, $_POST['nama_peralatan']),
        'id_satuan' => mysqli_real_escape_string($koneksi, $_POST['nama_satuan']),
        'status' => mysqli_real_escape_string($koneksi, $_POST['status']),
        'date_created' => $time,
    );

    // Call the Insert_Data function to insert data
    Insert_Data("master_peralatan", $data);
    header("Location: " . $baseURL . "/index.php?link=peralatan");
    exit();
}



if (isset($_POST['insert_pendapatan'])) {
    // Data untuk transaksi pengeluaran
    $transaksiData = array(
        'id_platform' => mysqli_real_escape_string($koneksi, $_POST['nama_platform']),
        'total_pendapatan' => mysqli_real_escape_string($koneksi, $_POST['total_pendapatan']),
        'tanggal_pendapatan' => mysqli_real_escape_string($koneksi, $_POST['tanggal_pendapatan']),
        'id_akun' => mysqli_real_escape_string($koneksi, $_POST['nama_akun']),
        'date_created' => $time,
    );

    // Insert data ke tabel transaksi_pengeluaran
    Insert_Data("transaksi_pendapatan", $transaksiData);

    // Ambil ID transaksi terakhir yang telah diinput
    $id_pendapatan = mysqli_insert_id($koneksi);

    // Loop untuk setiap barang dalam detail_pengeluaran
    foreach ($_POST['nama_barang'] as $index => $nama_barang) {
        $detailData = array(
            'id_pendapatan' => $id_pendapatan,
            'id_barang_jadi' => mysqli_real_escape_string($koneksi, $nama_barang),
            'total_barang' => mysqli_real_escape_string($koneksi, $_POST['total_barang'][$index]),
        );

        // Insert data ke tabel detail_pengeluaran
        Insert_Data("detail_pendapatan", $detailData);
    }

    // Redirect setelah insert selesai
    header("Location: " . $baseURL . "/index.php?link=pendapatan");
    exit();
}

if (isset($_POST['insert_pembelian'])) {
    // Ambil data dari form untuk tabel transaksi_pengeluaran
    $dataTransaksi = [
        'id_supplier' => $_POST['nama_supplier'],
        'tanggal' => $_POST['tanggal_pembelian'][0], // Ambil tanggal pertama
        'total_biaya' => $_POST['total_pembelian'],
        'id_akun' => $_POST['nama_akun'],
        'date_created' => date('Y-m-d H:i:s')
    ];

    // Insert ke tabel transaksi_pengeluaran
    $id_pengeluaran = Insert_Data('transaksi_pengeluaran', $dataTransaksi);

    if ($id_pengeluaran) {
        // Ambil data dari form untuk tabel detail_pengeluaran
        $nama_peralatan = $_POST['nama_peralatan'];
        $jumlah_barang = $_POST['jumlah_barang'];
        $harga_barang = $_POST['harga_barang'];
        $subtotal = $_POST['subtotal'];
        $bulan_ekonomis = $_POST['bulan_ekonomis'];
        $nilai_penyusutan = $_POST['nilai_penyusutan'];
        $tanggal_akhir = $_POST['tanggal_akhir'];

        // Loop melalui data barang
        for ($i = 0; $i < count($nama_peralatan); $i++) {
            $dataDetail = [
                'id_pengeluaran' => $id_pengeluaran,
                'id_peralatan' => $nama_peralatan[$i],
                'jumlah' => $jumlah_barang[$i],
                'harga_satuan' => $harga_barang[$i],
                'sub_total' => $subtotal[$i],
                'bulan_ekonomis' => $bulan_ekonomis[$i],
                'nilai_penyusutan' => $nilai_penyusutan[$i],
                'akhir_periode_penyusutan' => $tanggal_akhir[$i]
            ];

            // Insert ke tabel detail_pengeluaran
            if (!Insert_Data('detail_pengeluaran', $dataDetail)) {
                echo "Error saat menyimpan detail_pengeluaran.";
            }
        }

        echo "Data berhasil disimpan!";
    } else {
        echo "Error saat menyimpan transaksi_pengeluaran.";
    }
    header("Location: " . $baseURL . "/index.php?link=pembelian");
    exit();
}

if (isset($_POST['insert_pembelian_perlengkapan'])) {
    // Data untuk transaksi pengeluaran
    $transaksiData = array(
        'id_supplier' => mysqli_real_escape_string($koneksi, $_POST['nama_supplier']),
        'tanggal' => mysqli_real_escape_string($koneksi, $_POST['tanggal_pembelian']),
        'total' => mysqli_real_escape_string($koneksi, $_POST['total_pembelian']),
        'id_akun' => mysqli_real_escape_string($koneksi, $_POST['nama_akun']),
        'date_created' => $time,
    );

    // Insert data ke tabel transaksi_pengeluaran
    Insert_Data("transaksi_pembelian_perlengkapan", $transaksiData);

    // Ambil ID transaksi terakhir yang telah diinput
    $id_pengeluaran = mysqli_insert_id($koneksi);

    // Loop untuk setiap barang dalam detail_pengeluaran
    foreach ($_POST['nama_perlengkapan'] as $index => $nama_barang) {
        $detailData = array(
            'id_pembelian_perlengkapan' => $id_pengeluaran,
            'id_perlengkapan' => mysqli_real_escape_string($koneksi, $nama_barang),
            'jumlah' => mysqli_real_escape_string($koneksi, $_POST['jumlah_barang'][$index]),
            'harga_satuan' => mysqli_real_escape_string($koneksi, $_POST['harga_barang'][$index]),
            'sub_total' => mysqli_real_escape_string($koneksi, $_POST['subtotal'][$index]),
        );

        // Insert data ke tabel detail_pengeluaran
        Insert_Data("detail_pembelian_perlengkapan", $detailData);
    }

    // Redirect setelah insert selesai
    header("Location: " . $baseURL . "/index.php?link=stokperlengkapan");
    exit();
}


if (isset($_POST['insert_pembelian_overhead'])) {
    // Data untuk transaksi pengeluaran
    $transaksiData = array(
        'tanggal' => mysqli_real_escape_string($koneksi, $_POST['tanggal_pembelian']),
        'total' => mysqli_real_escape_string($koneksi, $_POST['total_pembelian']),
        'id_akun' => mysqli_real_escape_string($koneksi, $_POST['nama_akun']),
        'date_created' => $time,
    );

    // Insert data ke tabel transaksi_pengeluaran
    Insert_Data("transaksi_pengeluaran_overhead", $transaksiData);

    // Ambil ID transaksi terakhir yang telah diinput
    $id_pengeluaran = mysqli_insert_id($koneksi);

    // Loop untuk setiap barang dalam detail_pengeluaran
    foreach ($_POST['nama_overhead'] as $index => $nama_barang) {
        $detailData = array(
            'id_pengeluaran_overhead' => $id_pengeluaran,
            'id_overhead' => mysqli_real_escape_string($koneksi, $nama_barang),
            'biaya_overhead' => mysqli_real_escape_string($koneksi, $_POST['harga_barang'][$index]),
            'keterangan' => mysqli_real_escape_string($koneksi, $_POST['keterangan'][$index]),
        );

        // Insert data ke tabel detail_pengeluaran
        Insert_Data("detail_pengeluaran_overhead", $detailData);
    }

    // Redirect setelah insert selesai
    header("Location: " . $baseURL . "/index.php?link=bop");
    exit();
}

if (isset($_POST['insert_stokmasuk'])) {
    // Data untuk transaksi pengeluaran
    $transaksiData = array(
        'id_supplier' => mysqli_real_escape_string($koneksi, $_POST['nama_supplier']),
        'id_akun' => mysqli_real_escape_string($koneksi, $_POST['nama_akun']),
        'tanggal' => mysqli_real_escape_string($koneksi, $_POST['tanggal_pembelian']),
        'total_biaya' => mysqli_real_escape_string($koneksi, $_POST['total_pembelian']),
        'date_created' => $time,
    );

    // Insert data ke tabel transaksi_pengeluaran
    Insert_Data("transaksi_pembelian_bahan_material", $transaksiData);

    // Ambil ID transaksi terakhir yang telah diinput
    $id_pembelian_material = mysqli_insert_id($koneksi);

    // Loop untuk setiap barang dalam detail_pengeluaran
    foreach ($_POST['nama_bahan_material'] as $index => $nama_bahan_material) {
        $detailData = array(
            'id_pembelian_material' => $id_pembelian_material,
            'id_bahan_material' => mysqli_real_escape_string($koneksi, $nama_bahan_material),
            'jumlah' => mysqli_real_escape_string($koneksi, $_POST['jumlah_barang'][$index]),
            'harga_satuan' => mysqli_real_escape_string($koneksi, $_POST['harga_barang'][$index]),
            'sub_total' => mysqli_real_escape_string($koneksi, $_POST['subtotal'][$index]),
        );

        // Insert data ke tabel detail_pengeluaran
        Insert_Data("detail_pembelian_bahan_material", $detailData);
    }

    // Redirect setelah insert selesai
    header("Location: " . $baseURL . "/index.php?link=stokmaterial");
    exit();
}

if (isset($_POST['insert_stokkeluar'])) {
    // Data umum untuk transaksi
    $transaksiData = array(
        'id_pekerja' => mysqli_real_escape_string($koneksi, $_POST['nama_pekerja']),
        'id_akun' => mysqli_real_escape_string($koneksi, $_POST['nama_akun']),
        'tanggal_pengambilan' => mysqli_real_escape_string($koneksi, $_POST['tanggal_pengambilan']),
        'estimasi_tanggal_selesai' => mysqli_real_escape_string($koneksi, $_POST['estimasi_tanggal_jadi']),
        'target_jumlah' => mysqli_real_escape_string($koneksi, $_POST['target_jumlah']),
        'date_created' => $time,
    );

    // Insert data ke tabel transaksi_pengeluaran
    Insert_Data("transaksi_penggunaan_bahan_material", $transaksiData);

    // Ambil ID transaksi terakhir yang telah diinput
    $id_penggunaan_material = mysqli_insert_id($koneksi);

    // Loop untuk setiap barang dalam detail_pengeluaran
    foreach ($_POST['nama_bahan_material'] as $index => $nama_bahan_material) {
        $detailData = array(
            'id_penggunaan_material' => $id_penggunaan_material,
            'id_bahan_material' => mysqli_real_escape_string($koneksi, $nama_bahan_material),
            'jumlah' => mysqli_real_escape_string($koneksi, $_POST['jumlah_barang'][$index]),
        );

        // Insert data ke tabel detail_pengeluaran
        Insert_Data("detail_penggunaan_bahan_material", $detailData);
    }

    // Redirect setelah insert selesai
    header("Location: " . $baseURL . "/index.php?link=stokmaterial");
    exit();
}

if (isset($_POST['insert_stokbarangjadi'])) {
    // Data umum untuk transaksi
    $transaksiData = array(
        'id_pekerja' => mysqli_real_escape_string($koneksi, $_POST['nama_pekerja']),
        'id_akun' => mysqli_real_escape_string($koneksi, $_POST['nama_akun']),
        'tanggal' => mysqli_real_escape_string($koneksi, $_POST['tanggal']),
        'total_upah' => mysqli_real_escape_string($koneksi, $_POST['total_upah']),
        'keterangan' => mysqli_real_escape_string($koneksi, $_POST['keterangan']),
        'date_created' => $time,
    );

    // Insert data ke tabel transaksi_pengeluaran
    Insert_Data("transaksi_barang_jadi_masuk", $transaksiData);

    // Ambil ID transaksi terakhir yang telah diinput
    $id_barang_masuk = mysqli_insert_id($koneksi);

    // Loop untuk setiap barang dalam detail_pengeluaran
    foreach ($_POST['nama_barang'] as $index => $nama_barang) {
        $detailData = array(
            'id_barang_masuk' => $id_barang_masuk,
            'id_barang_jadi' => mysqli_real_escape_string($koneksi, $nama_barang),
            'jumlah' => mysqli_real_escape_string($koneksi, $_POST['jumlah_barang'][$index]),
            'subtotal_upah' => mysqli_real_escape_string($koneksi, $_POST['subtotal'][$index]),
        );

        // Insert data ke tabel detail_pengeluaran
        Insert_Data("detail_barang_jadi_masuk", $detailData);
    }

    // Redirect setelah insert selesai
    header("Location: " . $baseURL . "/index.php?link=stokbarang");
    exit();
}
