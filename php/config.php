<?php
// Konfigurasi database
$host = 'localhost'; // Host database (biasanya localhost)
$username = 'root'; // Username database (biasanya root)
$password = ''; // Password database (kosong)
$database = 'employee_management'; // Nama database

// Buat koneksi ke database
$conn = mysqli_connect($host, $username, $password, $database);

// Periksa koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}