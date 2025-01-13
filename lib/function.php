<?php

function Insert_Data($table, $data)
{
    global $koneksi;

    // Buat string kolom dan nilai
    $columns = implode(", ", array_keys($data));
    $escaped_values = array_map(function ($value) use ($koneksi) {
        return "'" . mysqli_real_escape_string($koneksi, $value) . "'";
    }, array_values($data));
    $values = implode(", ", $escaped_values);

    // Buat query
    $sql = "INSERT INTO `$table` ($columns) VALUES ($values)";

    // Debugging: Cetak query
    echo $sql;

    // Eksekusi query
    if (mysqli_query($koneksi, $sql)) {
        return $koneksi->insert_id;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
        return false;
    }
}




function Insert_Transaksi($table, $data)
{
    global $koneksi;

    // Konversi array ke nilai SQL
    $columns = implode(", ", array_keys($data));
    $values = implode("', '", array_values($data));
    $sql = "INSERT INTO $table ($columns) VALUES ('$values')";

    if ($koneksi->query($sql) === TRUE) {
        return true;
    } else {
        die("Error: " . $sql . "<br>" . $koneksi->error);
    }
}


function Tampil_Data($namaApi)
{
    global $baseURL;
    // Option untuk file_get_contents disable pengecekkan ssl certificate
    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );
    // URL API internal
    $apiURL = 'http://localhost/ProjectTa/webservice/api/' . $namaApi . '.php';

    // Panggil API menggunakan file_get_contents
    $response = file_get_contents($apiURL, false, stream_context_create($arrContextOptions));

    // Lakukan sesuatu dengan respons dari API (misalnya: tampilkan atau olah data)

    return json_decode($response);
}

function Update_Data($table, $data, ...$custom)
{
    global $koneksi;

    // Mendapatkan struktur tabel
    $queryStruktur = "DESC $table";
    $getStruktur = mysqli_query($koneksi, $queryStruktur);

    if (!$getStruktur || mysqli_num_rows($getStruktur) == 0) {
        die("Error: Tidak dapat mengambil struktur tabel '$table'. Pastikan tabel ada.");
    }

    while ($fetchStruktur = mysqli_fetch_assoc($getStruktur)) {
        $arrStruktur[] = $fetchStruktur['Field'];
        $columnName[] = $fetchStruktur['Field'];
    }

    // Memilih kolom yang akan diupdate
    // Jika $custom kosong, gunakan seluruh kolom dalam tabel (kecuali kolom yang tidak perlu)
    $kolomfix = (is_array($custom) && empty($custom)) ? $columnName : $custom[0];

    // Membuat string kolom = value
    foreach ($kolomfix as $index => $value) {
        if (!isset($data[$index])) {
            die("Error: Data untuk kolom '$value' tidak ditemukan.");
        }

        // Escape data
        $kolomfix[$index] = $value . " = '" . mysqli_real_escape_string($koneksi, $data[$index]) . "'";
    }

    // Mengubah menjadi string
    $kolomUpdate = implode(", ", $kolomfix);

    // Mendefinisikan primary key
    $PKColumn = $arrStruktur[0]; // Mengasumsikan kolom pertama adalah primary key
    $PKValue = "'" . mysqli_real_escape_string($koneksi, $data[0]) . "'";
    $kondisi = $PKColumn . " = " . $PKValue;

    // Memberlakukan query update
    $queryUpdate = "UPDATE $table SET $kolomUpdate WHERE $kondisi";

    // Eksekusi query
    $updateData = mysqli_query($koneksi, $queryUpdate);
    if ($updateData) {
        $_SESSION['hasil'] = "Data berhasil diupdate";
    } else {
        $_SESSION['warning'] = "Data gagal diupdate: " . mysqli_error($koneksi);
    }
}

function Tampil_Data_Cetak($endpoint)
{
    $url = "http://localhost/ProjectTa/webservice/api/$endpoint.php";
    $json = file_get_contents($url);
    return json_decode($json, true);
}



// function Update_Data($table, $data, $where)
// {
//     global $koneksi;

//     // Bangun string SET untuk query
//     $set = [];
//     foreach ($data as $key => $value) {
//         $set[] = "$key = '$value'";
//     }
//     $set_query = implode(", ", $set);

//     // Bangun string WHERE untuk query
//     $where_clause = [];
//     foreach ($where as $key => $value) {
//         $where_clause[] = "$key = '$value'";
//     }
//     $where_query = implode(" AND ", $where_clause);

//     // Buat query SQL
//     $query = "UPDATE $table SET $set_query WHERE $where_query";

//     // Debugging query
//     echo "Query: " . $query;

//     // Eksekusi query
//     if (mysqli_query($koneksi, $query)) {
//         return true;
//     } else {
//         die("Error updating data: " . mysqli_error($koneksi));
//     }
// }
