<?php
require "layout/head.php";
require "include/conn.php";

// Ambil data ekstrakurikuler (alternatif)
$sql = "SELECT * FROM saw_alternatives";
$result = $db->query($sql);
$alternatives = [];
while ($row = $result->fetch_object()) {
    $alternatives[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Ekstrakurikuler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require "layout/sidebar.php"; ?>
<div id="app">
    <div id="main">
        <div class="container mt-4">

            <h3 class="mb-4">Data Ekstrakurikuler</h3>

            <!-- Form Tambah Ekstrakurikuler -->
            <form class="row g-3 mb-3" action="aksi_alternatif.php" method="post">
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control" placeholder="Nama Ekstrakurikuler" required>
                </div>
                <div class="col-auto">
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                </div>
            </form>

            <!-- Tabel Ekstrakurikuler -->
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 20%">Ekstrakurikuler</th>
                        <th style="width: 15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
<?php $no = 1; foreach ($alternatives as $alt): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($alt->name); ?></td>
        <td>
            <form action="aksi_alternatif.php" method="post" class="d-inline">
                <input type="hidden" name="id" value="<?= $alt->id_alternative; ?>">
                <button type="submit" name="hapus" class="btn btn-sm btn-danger me-2" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
            </form>
            <a href="alternatif-edit.php?id=<?= $alt->id_alternative; ?>" class="btn btn-sm btn-warning">Edit</a>
        </td>
    </tr>
<?php endforeach; ?>
<?php if (empty($alternatives)): ?>
    <tr><td colspan="3" class="text-center">Belum ada data ekstrakurikuler</td></tr>
<?php endif; ?>
</tbody>

            </table>

        </div>
    </div>
</div>
</body>
</html>
