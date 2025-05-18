<?php
require "include/conn.php";
$id = $_GET['id'] ?? null;

if (!$id) {
    // Redirect ke halaman daftar kalau id tidak ada
    header("Location: ekstrakurikuler.php");
    exit;
}

// Ambil data alternatif berdasarkan ID
$sql = "SELECT * FROM saw_alternatives WHERE id_alternative = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "<div class='alert alert-danger text-center mt-5'>Data tidak ditemukan.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php require "layout/head.php";?>

    <body>
        <div id="app">
            <?php require "layout/sidebar.php";?>
            <div id="main" class="container my-5" style="max-width: 700px;">
                <header class="mb-4 d-flex justify-content-between align-items-center">
                    <h3>Edit Alternatif</h3>
                    <a href="ekstrakurikuler.php" class="btn btn-secondary btn-sm">Kembali</a>
                </header>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Form Edit Data Alternatif</h5>
                    </div>
                    <div class="card-body">
                        <form action="alternatif-edit-act.php" method="POST" novalidate>
                            <input type="hidden" name="id_alternative" value="<?= htmlspecialchars($row['id_alternative']); ?>">

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Alternatif <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($row['name']); ?>" required>
                                <div class="invalid-feedback">Nama alternatif wajib diisi.</div>
                            </div>

                        
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php require "layout/js.php";?>

        <script>
            // Bootstrap form validation
            (() => {
                'use strict';
                const forms = document.querySelectorAll('form');
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();
        </script>
    </body>
</html>
