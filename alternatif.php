<?php
require "layout/head.php";
require "include/conn.php";

// Ambil data alternatif dan buat peta ID
$sql = "SELECT * FROM saw_alternatives";
$result = $db->query($sql);
$alternatives = [];
$alt_map = [];
while ($row = $result->fetch_object()) {
    $alternatives[] = $row;
    $alt_map[$row->id_alternative] = $row;
}

// Ambil kriteria
$sql = "SELECT * FROM saw_criterias";
$result = $db->query($sql);
$criterias = [];
while ($row = $result->fetch_object()) {
    $criterias[] = $row;
}

// Matriks Evaluasi (X)
$sql = "SELECT a.id_alternative, a.name, c.criteria, e.value, c.attribute
        FROM saw_alternatives a
        JOIN saw_evaluations e ON a.id_alternative = e.id_alternative
        JOIN saw_criterias c ON e.id_criteria = c.id_criteria";
$result = $db->query($sql);

$X = [];
while ($row = $result->fetch_object()) {
    $X[$row->id_alternative][$row->criteria] = $row->value;
}

// Normalisasi dan pembobotan
$R = [];
$weighted_R = [];
foreach ($criterias as $criteria) {
    $values = [];
    $norm_values = [];

    foreach ($alternatives as $alt) {
        $values[] = $X[$alt->id_alternative][$criteria->criteria] ?? 0;
    }

    if ($criteria->attribute == 'benefit') {
        $max_value = max($values);
        foreach ($values as $value) {
            $norm_values[] = $max_value != 0 ? $value / $max_value : 0;
        }
    } else {
        $min_value = min($values);
        foreach ($values as $value) {
            $norm_values[] = $value != 0 ? $min_value / $value : 0;
        }
    }

    foreach ($norm_values as $key => $norm_value) {
        $alt_id = $alternatives[$key]->id_alternative;
        $R[$alt_id][$criteria->criteria] = $norm_value;
        $weighted_R[$alt_id][$criteria->criteria] = $norm_value * $criteria->weight;
    }
}

// Ideal Positif dan Negatif (TOPSIS)
$ideal_positive = [];
$ideal_negative = [];

foreach ($criterias as $criteria) {
    $column_values = array_column($R, $criteria->criteria);
    if ($criteria->attribute == 'benefit') {
        $ideal_positive[$criteria->criteria] = max($column_values);
        $ideal_negative[$criteria->criteria] = min($column_values);
    } else {
        $ideal_positive[$criteria->criteria] = min($column_values);
        $ideal_negative[$criteria->criteria] = max($column_values);
    }
}

// Hitung D+ dan D-
$d_plus = [];
$d_minus = [];

foreach ($alternatives as $alt) {
    $d_plus_val = 0;
    $d_minus_val = 0;

    foreach ($criterias as $criteria) {
        $r_val = $R[$alt->id_alternative][$criteria->criteria] ?? 0;
        $d_plus_val += pow($r_val - $ideal_positive[$criteria->criteria], 2);
        $d_minus_val += pow($r_val - $ideal_negative[$criteria->criteria], 2);
    }

    $d_plus[$alt->id_alternative] = sqrt($d_plus_val);
    $d_minus[$alt->id_alternative] = sqrt($d_minus_val);
}

// Hitung preferensi TOPSIS
$preferensi = [];
foreach ($alternatives as $alt) {
    $plus = $d_plus[$alt->id_alternative];
    $minus = $d_minus[$alt->id_alternative];
    $preferensi[$alt->id_alternative] = ($plus + $minus) != 0 ? $minus / ($plus + $minus) : 0;
}
arsort($preferensi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Perhitungan SAW & TOPSIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require "layout/sidebar.php"; ?>
<div id="app">
    <div id="main">
        <div class="container mt-4">

            <h3 class="mb-4"></h3>

            <!-- Form Tambah Alternatif -->
            <form class="row g-3 mb-3" action="aksi_alternatif.php" method="post">
                <div class="col-auto">
                    <input type="text" name="name" class="form-control" placeholder="Nama Alternatif" required>
                </div>
                <div class="col-auto">
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                </div>
            </form>


           <!-- Hasil Ranking TOPSIS -->
<h4 class="mt-4">Hasil Ranking (TOPSIS)</h4>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Ekstrakurikuler</th>
            <th>Nilai Preferensi</th>
            <th>Ranking</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $rank = 1;
        foreach ($preferensi as $id => $val): ?>
            <tr>
                <td><?= $rank++; ?></td>
                <td><?= $alt_map[$id]->name ?? 'Tidak Diketahui'; ?></td>
                <td><?= round($val, 4); ?></td>
                <td><?= $rank - 1; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


        </div>
    </div>
</div>
</body>
</html>
