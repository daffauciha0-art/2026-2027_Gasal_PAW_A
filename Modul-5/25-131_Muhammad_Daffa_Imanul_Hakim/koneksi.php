<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "penjualan_db"; // Sesuaikan dengan nama database Anda

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>