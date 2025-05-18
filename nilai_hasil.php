<?php
require "include/conn.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = (int)$_SESSION['user_id'];

// Ambil data alternatif dan kriteria
$alternatives = [];
$q = $db->query("SELECT * FROM saw_alternatives");
while ($row = $q->fetch_object()) {
    $alternatives[(int)$row->id_alternative] = $row->name;
}

$criterias = [];
$q = $db->query("SELECT * FROM saw_criterias");
while ($row = $q->fetch_object()) {
    $criterias[(int)$row->id_criteria] = $row->criteria;
}

// Ambil data nilai milik user yang login
$nilai = [];
$q = $db->query("SELECT * FROM saw_evaluations WHERE id_user = $id_user");
while ($row = $q->fetch_object()) {
    $nilai[(int)$row->id_alternative][(int)$row->id_criteria] = $row->value;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hasil Nilai</title>
    <?php require "layout/head.php"; ?>
</head>
<body>
<div id="app">
    <?php require "layout/sidebar.php"; ?>
    <div id="main" class="container mt-4">
        <h3>Data Nilai Alternatif per Kriteria</h3>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Alternatif / Kriteria</th>
                <?php foreach ($criterias as $c): ?>
                    <th><?= htmlspecialchars($c); ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($alternatives as $id_a => $name): ?>
                <tr>
                    <td><?= htmlspecialchars($name); ?></td>
                    <?php foreach ($criterias as $id_c => $c_name): ?>
                        <td><?= isset($nilai[$id_a][$id_c]) ? htmlspecialchars($nilai[$id_a][$id_c]) : '-'; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Tombol berdampingan kecil di bawah tabel -->
        <div class="d-flex justify-content-start gap-2 mb-3">
            <a href="nilai_input.php" class="btn btn-secondary btn-sm">Input/Ubah Nilai</a>
            <a href="hasil.php" class="btn btn-success btn-sm">Lihat Hasil</a>
        </div>
    </div>
</div>
<?php require "layout/js.php"; ?>
</body>
</html>
