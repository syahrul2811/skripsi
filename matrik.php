<?php
require "layout/head.php";
require "include/conn.php";

// Ambil data alternatif
$sql = "SELECT * FROM saw_alternatives";
$result = $db->query($sql);
if (!$result) {
    die("Query gagal: " . $db->error); // Menampilkan error jika query gagal
}

$alternatives = [];
$alt_map = [];
while ($row = $result->fetch_object()) {
    $alternatives[] = $row;
    $alt_map[$row->id_alternative] = $row;
}

// Ambil data criteria (sebelumnya kriteria)
$sql = "SELECT * FROM saw_criterias";
$result = $db->query($sql);
if (!$result) {
    die("Query gagal: " . $db->error); // Menampilkan error jika query gagal
}

$criterias = [];
while ($row = $result->fetch_object()) {
    $criterias[] = $row;
}

// Matriks Evaluasi
$sql = "SELECT a.id_alternative, a.name, c.criteria, e.value, c.attribute
        FROM saw_alternatives a
        JOIN saw_evaluations e ON a.id_alternative = e.id_alternative
        JOIN saw_criterias c ON e.id_criteria = c.id_criteria";
$result = $db->query($sql);
if (!$result) {
    die("Query gagal: " . $db->error); // Menampilkan error jika query gagal
}

$X = [];
while ($row = $result->fetch_object()) {
    $X[$row->id_alternative][$row->criteria] = $row->value;
}

// Normalisasi Matriks Rij dan Weighted Normalization
$R = [];
$weighted_R = [];

foreach ($criterias as $criteria) {  // Ganti kriteria dengan criteria
    $values = [];
    $norm_values = [];

    foreach ($alternatives as $alt) {
        $values[] = isset($X[$alt->id_alternative][$criteria->criteria]) ? $X[$alt->id_alternative][$criteria->criteria] : 0;
    }

    // Normalisasi berdasarkan atribut
    if ($criteria->attribute == 'benefit') {
        $max_value = max($values);
        foreach ($values as $value) {
            $norm_values[] = $max_value != 0 ? $value / $max_value : 0;
        }
    } else { // cost
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

// Matriks Ideal untuk TOPSIS
$ideal_positive = [];
$ideal_negative = [];

foreach ($criterias as $criteria) {  // Ganti kriteria dengan criteria
    $column_values = array_column($R, $criteria->criteria);
    if ($criteria->attribute == 'benefit') {
        $ideal_positive[$criteria->criteria] = max($column_values);
        $ideal_negative[$criteria->criteria] = min($column_values);
    } else {
        $ideal_positive[$criteria->criteria] = min($column_values);
        $ideal_negative[$criteria->criteria] = max($column_values);
    }
}

// Hitung Jarak Euclidean untuk TOPSIS
$d_plus = [];
$d_minus = [];

foreach ($alternatives as $alt) {
    $d_plus_val = 0;
    $d_minus_val = 0;

    foreach ($criterias as $criteria) {  // Ganti kriteria dengan criteria
        $r_val = $R[$alt->id_alternative][$criteria->criteria] ?? 0;
        $d_plus_val += pow($r_val - $ideal_positive[$criteria->criteria], 2);
        $d_minus_val += pow($r_val - $ideal_negative[$criteria->criteria], 2);
    }

    $d_plus[$alt->id_alternative] = sqrt($d_plus_val);
    $d_minus[$alt->id_alternative] = sqrt($d_minus_val);
}

// Hitung Nilai Preferensi TOPSIS
$preferensi = [];
foreach ($alternatives as $alt) {
    $plus = $d_plus[$alt->id_alternative];
    $minus = $d_minus[$alt->id_alternative];
    $preferensi[$alt->id_alternative] = ($plus + $minus) != 0 ? $minus / ($plus + $minus) : 0;
}

// Sort berdasarkan preferensi
arsort($preferensi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content -->
</head>
<body>
<?php require "layout/sidebar.php";?>
<div id="app">
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Perhitungan SAW & TOPSIS</h3>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Matrik Keputusan (X) & Ternormalisasi (R)</h4>
                        </div>
                        <div class="card-body">

                            <!-- Matrik Keputusan (X) -->
                            <table class="table table-striped">
                                <caption>Matrik Keputusan (X)</caption>
                                <thead>
                                <tr>
                                    <th>Alternatif</th>
                                    <?php foreach ($criterias as $criteria) {  // Ganti kriteria dengan criteria
                                        echo "<th>{$criteria->criteria}</th>";
                                    } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($alternatives as $alt) { ?>
                                    <tr>
                                        <td><?php echo $alt->name; ?></td>
                                        <?php foreach ($criterias as $criteria) {  // Ganti kriteria dengan criteria ?>
                                            <td><?php echo $X[$alt->id_alternative][$criteria->criteria] ?? 0; ?></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>

                            <!-- Matrik Ternormalisasi -->
                            <table class="table table-striped">
                                <caption>Matrik Ternormalisasi (R)</caption>
                                <thead>
                                <tr>
                                    <th>Alternatif</th>
                                    <?php foreach ($criterias as $criteria) {  // Ganti kriteria dengan criteria
                                        echo "<th>{$criteria->criteria}</th>";
                                    } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($alternatives as $alt) { ?>
                                    <tr>
                                        <td><?php echo $alt->name; ?></td>
                                        <?php foreach ($criterias as $criteria) {  // Ganti kriteria dengan criteria ?>
                                            <td><?php echo round($R[$alt->id_alternative][$criteria->criteria] ?? 0, 2); ?></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>

                            <!-- Ranking TOPSIS -->
                            <table class="table table-striped">
                                <caption>Hasil Ranking (TOPSIS)</caption>
                                <thead>
                                <tr>
                                    <th>Alternatif</th>
                                    <th>Preferensi</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($preferensi as $id => $preferensi_value) { ?>
                                    <tr>
                                        <td><?php echo $alt_map[$id]->name ?? 'Tidak Diketahui'; ?></td>
                                        <td><?php echo round($preferensi_value, 4); ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
</body>
</html>
