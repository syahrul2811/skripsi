<?php
session_start();
require "include/conn.php";

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data kriteria
$criterias = [];
$weights = [];
$attributes = [];
$q = $db->query("SELECT * FROM saw_criterias");
while ($row = $q->fetch_object()) {
    $criterias[$row->id_criteria] = $row->criteria;
    $weights[$row->id_criteria] = $row->weight;
    $attributes[$row->id_criteria] = $row->attribute;
}

// Ambil data alternatif
$alternatives = [];
$q = $db->query("SELECT * FROM saw_alternatives");
while ($row = $q->fetch_object()) {
    $alternatives[$row->id_alternative] = $row->name;
}

// Ambil data penilaian user ini saja
$matrix = [];
$q = $db->query("SELECT * FROM saw_evaluations WHERE id_user = $user_id");
if ($q->num_rows == 0) {
    // Jika data penilaian kosong, alert kecil lalu redirect ke input_nilai.php
    echo "<script>
        alert('Data penilaian belum tersedia. Silakan isi penilaian terlebih dahulu.');
        window.location.href = 'input_nilai.php';
    </script>";
    exit;
}
while ($row = $q->fetch_object()) {
    $matrix[$row->id_alternative][$row->id_criteria] = $row->value;
}

// ========== PERHITUNGAN SAW ==========
$saw_result = [];
$normalized = [];
foreach ($criterias as $id_c => $_) {
    $col = array_column($matrix, $id_c);
    foreach ($matrix as $id_a => $vals) {
        if ($attributes[$id_c] == 'benefit') {
            $normalized[$id_a][$id_c] = $matrix[$id_a][$id_c] / max($col);
        } else {
            $normalized[$id_a][$id_c] = min($col) / $matrix[$id_a][$id_c];
        }
    }
}
foreach ($normalized as $id_a => $criteria_vals) {
    $score = 0;
    foreach ($criteria_vals as $id_c => $val) {
        $score += $val * $weights[$id_c];
    }
    $saw_result[$id_a] = $score;
}
arsort($saw_result);

// ========== PERHITUNGAN TOPSIS ==========
$topsis_result = [];
$divisors = [];
foreach ($criterias as $id_c => $_) {
    $sum_sq = 0;
    foreach ($matrix as $id_a => $vals) {
        $sum_sq += pow($matrix[$id_a][$id_c], 2);
    }
    $divisors[$id_c] = sqrt($sum_sq);
}

$norm_matrix = [];
foreach ($matrix as $id_a => $vals) {
    foreach ($vals as $id_c => $val) {
        $norm_matrix[$id_a][$id_c] = $val / $divisors[$id_c];
    }
}

$weighted_matrix = [];
foreach ($norm_matrix as $id_a => $vals) {
    foreach ($vals as $id_c => $val) {
        $weighted_matrix[$id_a][$id_c] = $val * $weights[$id_c];
    }
}

$ideal_pos = $ideal_neg = [];
foreach ($criterias as $id_c => $_) {
    $vals = array_column($weighted_matrix, $id_c);
    if ($attributes[$id_c] == 'benefit') {
        $ideal_pos[$id_c] = max($vals);
        $ideal_neg[$id_c] = min($vals);
    } else {
        $ideal_pos[$id_c] = min($vals);
        $ideal_neg[$id_c] = max($vals);
    }
}

foreach ($weighted_matrix as $id_a => $vals) {
    $d_pos = $d_neg = 0;
    foreach ($vals as $id_c => $val) {
        $d_pos += pow($val - $ideal_pos[$id_c], 2);
        $d_neg += pow($val - $ideal_neg[$id_c], 2);
    }
    $d_pos = sqrt($d_pos);
    $d_neg = sqrt($d_neg);
    $topsis_result[$id_a] = $d_neg / ($d_pos + $d_neg);
}
arsort($topsis_result);
?>

<!DOCTYPE html>
<html lang="en">
<?php require "layout/head.php"; ?>
<style>
@media print {
  #app > aside, button, a.btn {
    display: none !important;
  }
  #main {
    margin: 0;
    width: 100%;
  }
}
</style>
<body>
<div id="app">
    <?php require "layout/sidebar.php"; ?>
    <div id="main" class="container mt-4">
        <h3>Hasil Perhitungan SAW dan TOPSIS</h3>
        <hr>

        <h5>Ranking Metode SAW</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Rank</th>
                    <th>Ekstrakurikuler</th>
                    <th>Skor SAW</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; foreach ($saw_result as $id => $score): ?>
                <tr>
                    <td><?= $rank++; ?></td>
                    <td><?= htmlspecialchars($alternatives[$id]); ?></td>
                    <td><?= round($score, 4); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h5 class="mt-4">Ranking Metode TOPSIS</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Rank</th>
                    <th>Ekstrakurikuler</th>
                    <th>Skor TOPSIS</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; foreach ($topsis_result as $id => $score): ?>
                <tr>
                    <td><?= $rank++; ?></td>
                    <td><?= htmlspecialchars($alternatives[$id]); ?></td>
                    <td><?= round($score, 4); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h5 class="mt-4">Kesimpulan</h5>
        <p>Metode SAW dan TOPSIS menghasilkan ranking yang bisa dibandingkan. Ekstrakurikuler terbaik menurut masing-masing metode ditampilkan paling atas di tabel masing-masing.</p>
    </div>
</div>

<?php require "layout/js.php"; ?>
</body>
</html>
