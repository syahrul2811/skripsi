<?php
// Include file koneksi ke database
include 'include/conn.php';

// Mulai session
session_start();

// Cek apakah user sudah login
if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    header("Location: index.php");
    exit;
}

// Jika form login disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form login
    $username = trim(mysqli_real_escape_string($db, $_POST['username']));
    $password = trim(mysqli_real_escape_string($db, $_POST['password']));
    $password_md5 = md5($password);

    // Cek data dari database
    $query = "SELECT * FROM saw_users WHERE username = '$username' AND password = '$password_md5'";
    $result = $db->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // ✅ Set session lengkap
        $_SESSION['user_id'] = $user['id_user'];   // Ini yang sebelumnya hilang
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'] ?? '';
        $_SESSION['role'] = $user['role'] ?? 'siswa';
        $_SESSION['status'] = 'login';

        // Redirect ke halaman utama
        header("Location: index.php");
        exit;
    } else {
        // Login gagal
        $error_message = "Username atau Password salah!";
        header("Location: login.php?error=" . urlencode($error_message));
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
