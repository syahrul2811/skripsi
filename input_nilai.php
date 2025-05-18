<?php
require "include/conn.php";
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = (int)$_SESSION['user_id'];

// Ambil alternatif dan kriteria
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['nilai']) || !is_array($_POST['nilai'])) {
        die("Data nilai tidak valid.");
    }

    foreach ($_POST['nilai'] as $id_alt => $criteria_vals) {
        $id_alt = (int)$id_alt;
        foreach ($criteria_vals as $id_crit => $value) {
            $id_crit = (int)$id_crit;
            $value = $db->real_escape_string(trim($value));

            // Cek sudah ada atau belum, filter juga berdasarkan user
            $check = $db->query("SELECT * FROM saw_evaluations WHERE id_alternative = $id_alt AND id_criteria = $id_crit AND id_user = $id_user");
            if ($check === false) {
                die("Error cek data: " . $db->error);
            }

            if ($check->num_rows > 0) {
                $sql = "UPDATE saw_evaluations SET value = '$value' WHERE id_alternative = $id_alt AND id_criteria = $id_crit AND id_user = $id_user";
                if (!$db->query($sql)) {
                    die("Error update: " . $db->error);
                }
            } else {
                $sql = "INSERT INTO saw_evaluations (id_alternative, id_criteria, id_user, value) VALUES ($id_alt, $id_crit, $id_user, '$value')";
                if (!$db->query($sql)) {
                    die("Error insert: " . $db->error);
                }
            }
        }
    }

    header("Location: nilai_hasil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Input Nilai</title>
    <?php require "layout/head.php"; ?>
</head>
<body>
<div id="app">
    <?php require "layout/sidebar.php"; ?>
    <div id="main" class="container mt-4">
        <h3>Input Nilai Alternatif per Kriteria</h3>
        <form method="post" action="">
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
                            <td>
                                <input type="number" step="any" min="0" name="nilai[<?= $id_a ?>][<?= $id_c ?>]" required 
                                value="<?php 
                                    $val_q = $db->query("SELECT value FROM saw_evaluations WHERE id_alternative = $id_a AND id_criteria = $id_c AND id_user = $id_user");
                                    if ($val_q && $val_q->num_rows > 0) {
                                        echo htmlspecialchars($val_q->fetch_object()->value);
                                    }
                                ?>">
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <button class="btn btn-primary" type="submit">Simpan & Lihat Nilai</button>
        </form>
    </div>
</div>
<?php require "layout/js.php"; ?>
</body>
</html>
