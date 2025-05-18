<?php
session_start();
require 'include/conn.php';

// Cek apakah user sudah login dan user_id tersedia
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php?error=Silakan login terlebih dahulu");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM saw_users WHERE id_user = $user_id";
$result = mysqli_query($db, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Gagal mengambil data user: " . mysqli_error($db));
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<?php require "layout/head.php"; ?>

<body>
    <div id="app">
        <?php require "layout/sidebar.php"; ?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3>Profil Pengguna</h3>
            </div>

            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Informasi Profil</h4>
                            </div>
                            <div class="card-body">
                                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select class="form-control" id="role" name="role">
                                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                            <option value="siswa" <?= $user['role'] == 'siswa' ? 'selected' : '' ?>>Siswa</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
    <label for="photo">Foto Profil</label>
    <input type="file" class="form-control" id="photo" name="photo">

    <?php
    // Ambil nama foto atau default jika kosong
    $photo = !empty($user['photo']) ? $user['photo'] : 'default.jpg';
    ?>

    <div class="mt-2">
        <img src="uploads/<?= htmlspecialchars($photo) ?>" alt="Foto Profil" width="150" class="img-thumbnail">
    </div>
</div>


                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php require "layout/footer.php"; ?>
        </div>
    </div>

    <?php require "layout/js.php"; ?>
</body>
</html>
