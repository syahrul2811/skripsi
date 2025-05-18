<?php
// reset-password-act.php

// Cek apakah form telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Simulasi pengecekan email di database (ganti ini dengan koneksi database nyata)
    // Misalnya: cek apakah email ada di tabel user
    // Contoh statis:
    $daftar_email_terdaftar = ["user@example.com", "admin@al-amanah.sch.id"];

    if (in_array($email, $daftar_email_terdaftar)) {
        // Simulasi pembuatan token reset password
        $token = bin2hex(random_bytes(32)); // Token unik
        $reset_link = "https://example.com/reset-password.php?token=" . $token;

        // Simulasi mengirim email
        echo "<h3>Link reset password telah dikirim ke email: $email</h3>";
        echo "<p><strong>Link:</strong> <a href='$reset_link'>$reset_link</a></p>";
        echo "<p><a href='index.php'>Kembali ke login</a></p>";
    } else {
        echo "<h3>Email tidak ditemukan dalam sistem.</h3>";
        echo "<p><a href='forgot-password.php'>Coba lagi</a></p>";
    }
} else {
    header("Location: forgot-password.php");
    exit;
}
?>
