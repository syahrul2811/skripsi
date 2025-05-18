<?php
include 'include/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form pendaftaran
    $name = trim(mysqli_real_escape_string($db, $_POST['name']));
    $username = trim(mysqli_real_escape_string($db, $_POST['username']));
    $email = trim(mysqli_real_escape_string($db, $_POST['email']));
    $password = trim(mysqli_real_escape_string($db, $_POST['password']));

    // Hash password menggunakan MD5
    $password_md5 = md5($password);

    // Query untuk mengecek apakah username atau email sudah ada
    $checkQuery = "SELECT * FROM saw_users WHERE username = '$username' OR email = '$email'";
    $checkResult = $db->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Username atau email sudah terdaftar
        echo "Username atau email sudah terdaftar!";
    } else {
        // Jika tidak ada, simpan data pengguna baru
        $insertQuery = "INSERT INTO saw_users (name, username, email, password, role) 
                        VALUES ('$name', '$username', '$email', '$password_md5', 'user')";

        if ($db->query($insertQuery)) {
            // Pendaftaran berhasil
            header("Location: login.php?status=success");
            exit();
        } else {
            echo "Terjadi kesalahan saat mendaftar: " . $db->error;
        }
    }
}
?>
