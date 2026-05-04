<?php
$host = "localhost";    // Biasanya localhost
$user = "root";         // Username default XAMPP
$pass = "";             // Password default XAMPP biasanya kosong
$db   = "toko_buku";    // Nama database yang kita buat di awal

// Proses koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Kalau mau mastiin koneksi jalan, bisa tambahin echo (tapi hapus kalau sudah jalan)
// echo "Koneksi Berhasil!"; 
?>