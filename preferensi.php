<!DOCTYPE html>
<html lang="en">
<?php
require "layout/head.php";
require "include/conn.php";
require "W.php";
require "R.php";

// Matriks R (ternormalisasi) dan vektor bobot W
$R = array(  // Matriks ternormalisasi R
    array(0.8, 0.6, 0.7),
    array(0.7, 0.8, 0.5),
    array(0.9, 0.7, 0.6),
    array(0.6, 0.9, 0.8)
);

$W = array(0.4, 0.3, 0.3); // Bobot kriteria

?>

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
        <h3>Nilai Preferensi (Vi) dengan Metode SAW dan TOPSIS</h3>
      </div>
      <div class="page-content">
        <section class="row">
          <div class="col-12">
            <div class="card">

              <div class="card-header">
                <h4 class="card-title">Tabel Nilai Preferensi (Vi)</h4>
              </div>
              <div class="card-content">
                <div class="card-body">
                  <p class="card-text">
                    Berikut adalah perhitungan nilai preferensi menggunakan metode SAW dan TOPSIS.
                  </p>
                </div>
                <div class="table-responsive">
                  <table class="table table-striped mb-0">
                    <caption>Nilai Preferensi (Vi)</caption>
                    <tr>
                      <th>No</th>
                      <th>Alternatif</th>
                      <th>Nilai SAW</th>
                      <th>Nilai TOPSIS</th>
                    </tr>
                    <?php

                    // SAW - Perhitungan nilai preferensi (Vi)
                    $P = array();  // Nilai preferensi SAW
                    $m = count($W);
                    $no = 0;
                    foreach ($R as $i => $r) {
                        for ($j = 0; $j < $m; $j++) {
                            $P[$i] = (isset($P[$i]) ? $P[$i] : 0) + $r[$j] * $W[$j]; // Penjumlahan bobot * matriks ternormalisasi
                        }
                    }

                    // TOPSIS - Perhitungan nilai preferensi (Vi)
                    $ideal_solution = array();
                    $negative_solution = array();

                    // Menentukan solusi ideal dan negatif
                    foreach ($R as $i => $r) {
                        for ($j = 0; $j < $m; $j++) {
                            if ($r[$j] > (isset($ideal_solution[$j]) ? $ideal_solution[$j] : -INF)) {
                                $ideal_solution[$j] = $r[$j];  // Solusi ideal (nilai terbaik)
                            }
                            if ($r[$j] < (isset($negative_solution[$j]) ? $negative_solution[$j] : INF)) {
                                $negative_solution[$j] = $r[$j]; // Solusi negatif (nilai terburuk)
                            }
                        }
                    }

                    // Menghitung jarak ke solusi ideal dan negatif
                    $distance_to_ideal = array();
                    $distance_to_negative = array();

                    foreach ($R as $i => $r) {
                        $distance_to_ideal[$i] = 0;
                        $distance_to_negative[$i] = 0;

                        for ($j = 0; $j < $m; $j++) {
                            $distance_to_ideal[$i] += pow($r[$j] - $ideal_solution[$j], 2);
                            $distance_to_negative[$i] += pow($r[$j] - $negative_solution[$j], 2);
                        }
                        $distance_to_ideal[$i] = sqrt($distance_to_ideal[$i]);
                        $distance_to_negative[$i] = sqrt($distance_to_negative[$i]);
                    }

                    // Menghitung nilai preferensi TOPSIS
                    $topsis_values = array();
                    foreach ($R as $i => $r) {
                        $topsis_values[$i] = $distance_to_negative[$i] / ($distance_to_ideal[$i] + $distance_to_negative[$i]);
                    }

                    // Menampilkan hasil
                    foreach ($P as $i => $saw_value) {
                        echo "<tr class='center'>
                                <td>" . (++$no) . "</td>
                                <td>A{$i}</td>
                                <td>{$saw_value}</td>
                                <td>{$topsis_values[$i]}</td>
                              </tr>";
                    }
                    ?>
                  </table>
                </div>
              </div>
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
