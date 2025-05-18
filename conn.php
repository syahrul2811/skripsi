<?php
// Koneksi ke database
$host = 'localhost'; // Ganti jika server database berbeda
$user = 'root'; // Username database
$pass = ''; // Password database (kosong jika tidak ada)
$dbname = 'db_saw'; // Nama database kamu

// Membuat koneksi ke database
$db = new mysqli($host, $user, $pass, $dbname);

// Mengecek koneksi
if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}
?>
