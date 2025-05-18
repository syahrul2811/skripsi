<?php
require "include/conn.php";
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}
$id = $_GET['id'];
$sql = "SELECT * FROM saw_criterias WHERE id_criteria = '$id'";
$result = $db->query($sql);
$row = $result->fetch_assoc();
if (!$row) {
    die("Data tidak ditemukan");
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
            <h3>Edit Bobot Kriteria</h3>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Data</h4>
                    </div>
                    <div class="card-body">
                        <form action="bobot-edit-act.php" method="POST">
                            <input type="hidden" name="id_criteria" value="<?= htmlspecialchars($row['id_criteria']); ?>">
                            <div class="mb-3">
                                <label class="form-label">Kriteria</label>
                                <input type="text" class="form-control" name="criteria" value="<?= htmlspecialchars($row['criteria']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Weight</label>
                                <input type="number" step="0.01" class="form-control" name="weight" value="<?= htmlspecialchars($row['weight']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Attribute</label>
                                <select class="form-select" name="attribute" required>
                                    <option value="benefit" <?= $row['attribute'] == 'benefit' ? 'selected' : '' ?>>Benefit</option>
                                    <option value="cost" <?= $row['attribute'] == 'cost' ? 'selected' : '' ?>>Cost</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info btn-sm">Simpan</button>
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
