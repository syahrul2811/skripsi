<?php
session_start();
include 'include/conn.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Ambil data user untuk form awal
$query = "SELECT * FROM saw_users WHERE id_user = $user_id";
$result = mysqli_query($db, $query);
if (!$result) {
    die("Query error: " . mysqli_error($db));
}
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("Data user tidak ditemukan.");
}

// Proses update jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(mysqli_real_escape_string($db, $_POST['name']));
    $email = trim(mysqli_real_escape_string($db, $_POST['email']));

    // Proses upload foto
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileSize = $_FILES['photo']['size'];
        $fileType = $_FILES['photo']['type'];
        
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if (in_array($fileExtension, $allowedExtensions)) {
            // Generate unique name for file
            $newFileName = uniqid() . '.' . $fileExtension;
            $uploadPath = 'uploads/' . $newFileName;
            
            // Move file to the destination directory
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                // Hapus foto lama jika ada
                if (!empty($user['photo']) && file_exists('uploads/' . $user['photo'])) {
                    unlink('uploads/' . $user['photo']);
                }
                $photo = $newFileName;
            } else {
                $photo = $user['photo']; // Tetap pakai foto lama jika gagal upload
            }
        } else {
            $photo = $user['photo']; // Tetap pakai foto lama jika ekstensi tidak valid
        }
    } else {
        $photo = $user['photo']; // Tetap pakai foto lama jika tidak ada file baru
    }

    // Update data di database
    $updateQuery = "UPDATE saw_users SET name = '$name', email = '$email', photo = '$photo' WHERE id_user = $user_id";
    if (mysqli_query($db, $updateQuery)) {
        // Update data di session
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['photo'] = $photo;

        // Redirect ke halaman yang sama dengan parameter success
        header("Location: edit_profil.php?success=1");
        exit();
    } else {
        echo "Terjadi kesalahan saat memperbarui profil: " . mysqli_error($db);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require "layout/head.php"; ?>

<body>
    <div id="app">
        <?php require "layout/sidebar.php"; ?>

        <div id="main">
            <div class="page-heading">
                <h3>Edit Profil</h3>
            </div>
            <div class="page-content">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Profil berhasil diperbarui!</div>
                <?php endif; ?>
                <form action="edit_profil.php" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4 text-center">
                                    <?php
                                    $photoFile = (!empty($user['photo']) && file_exists('uploads/' . $user['photo']))
                                        ? 'uploads/' . $user['photo']
                                        : 'uploads/default.png';
                                    ?>
                                    <img src="<?php echo $photoFile; ?>" alt="Foto Profil" width="150" class="rounded-circle mb-3">
                                    <input type="file" name="photo" class="form-control mt-3">
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label for="name"><strong>Nama Lengkap:</strong></label>
                                        <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="email"><strong>Email:</strong></label>
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Perbarui Profil</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php require "layout/footer.php"; ?>
    </div>

    <?php require "layout/js.php"; ?>
</body>
</html>
