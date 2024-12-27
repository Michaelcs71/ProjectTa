<?php
// definisikan koneksi ke database
$baseURL = "http://localhost/ProjectTa";
$server = "localhost";
$username = "root";
$password = "";
$database = "project_ta";

// Koneksi dan memilih database di server
$koneksi = new mysqli($server, $username, $password, $database);

// Check connection
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
