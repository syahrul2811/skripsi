<?php
// Include koneksi database
include 'include/conn.php';

// Mulai session
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: login.php?error=Silakan%20login%20terlebih%20dahulu");
    exit;
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = trim(mysqli_real_escape_string($db, $_POST['name']));
    $email = trim(mysqli_real_escape_string($db, $_POST['email']));
    $role = $_POST['role'];

    // Cek apakah foto baru di-upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = $_FILES['photo'];
        $photo_name = $user_id . "_" . basename($photo['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $photo_name;

        // Pastikan file adalah gambar
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($image_file_type, $allowed_types)) {
            if (move_uploaded_file($photo['tmp_name'], $target_file)) {
                // Berhasil upload foto
                $update_photo = $photo_name;
            } else {
                $update_photo = null; // Gagal upload foto
            }
        } else {
            $update_photo = null; // Jika file bukan gambar
        }
    } else {
        $update_photo = null; // Tidak ada perubahan foto
    }

    // Update data di database
    $query = "UPDATE saw_users SET name = '$name', email = '$email', role = '$role'";

    if ($update_photo) {
        $query .= ", photo = '$update_photo'"; // Jika ada foto baru
    }

    $query .= " WHERE id_user = '$user_id'";

    // Eksekusi query
    if (mysqli_query($db, $query)) {
        // Berhasil update
        $_SESSION['name'] = $name; // Update session
        $_SESSION['role'] = $role; // Update session

        header("Location: profil.php?success=Profil%20berhasil%20diupdate");
    } else {
        // Gagal update
        $error_message = "Gagal memperbarui profil: " . mysqli_error($db);
        header("Location: profil.php?error=" . urlencode($error_message));
    }
    exit;
} else {
    // Jika tidak ada POST data
    header("Location: profil.php");
    exit;
}
?>
