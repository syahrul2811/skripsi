<?php
require "include/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi sederhana
    if (empty($_POST['criteria']) || empty($_POST['weight']) || empty($_POST['attribute'])) {
        $error = "Semua field wajib diisi!";
    } else {
        $criteria = $_POST['criteria'];
        $weight = $_POST['weight'];
        $attribute = $_POST['attribute'];

        $sql = "INSERT INTO saw_criterias (criteria, weight, attribute) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sds", $criteria, $weight, $attribute);
        if ($stmt->execute()) {
            header("Location: bobot.php");
            exit;
        } else {
            $error = "Gagal menambahkan data: " . $db->error;
        }
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
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Tambah Bobot Kriteria</h3>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Tambah Bobot</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Kriteria</label>
                                <input type="text" name="criteria" class="form-control" required value="<?= isset($_POST['criteria']) ? htmlspecialchars($_POST['criteria']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Weight</label>
                                <input type="number" step="0.01" name="weight" class="form-control" required value="<?= isset($_POST['weight']) ? htmlspecialchars($_POST['weight']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Attribute</label>
                                <select name="attribute" class="form-select" required>
                                    <option value="">-- Pilih Atribut --</option>
                                    <option value="benefit" <?= (isset($_POST['attribute']) && $_POST['attribute'] == 'benefit') ? 'selected' : '' ?>>Benefit</option>
                                    <option value="cost" <?= (isset($_POST['attribute']) && $_POST['attribute'] == 'cost') ? 'selected' : '' ?>>Cost</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Tambah</button>
                            <a href="bobot.php" class="btn btn-secondary btn-sm">Batal</a>
                        </form>
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
