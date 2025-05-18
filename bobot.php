<!DOCTYPE html>
<html lang="en">
<?php
require "layout/head.php";
require "include/conn.php";
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
        <h3>Bobot Kriteria</h3>
      </div>

      <div class="page-content">
        <section class="row">
          <div class="col-12">
            <div class="card">

              <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Tabel Bobot Kriteria</h4>
                <a href="bobot-add.php" class="btn btn-success btn-sm">Tambah</a>
              </div>

              <div class="card-content">
                <div class="card-body">
                  <!-- Bisa ditambahkan konten lain di sini -->
                </div>

                <div class="table-responsive">
                  <table class="table table-striped table-bordered mb-0">

                    <thead class="table-dark text-center align-middle">
                      <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 8%;">Simbol</th>
                        <th>Kriteria</th>
                        <th style="width: 10%;">Bobot</th>
                        <th style="width: 10%;">Atribut</th>
                        <th style="width: 15%;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql = 'SELECT id_criteria, criteria, weight, attribute FROM saw_criterias';
                      $result = $db->query($sql);
                      $i = 0;
                      while ($row = $result->fetch_object()) {
                          $i++;
                          echo "<tr>
                            <td class='text-center'>{$i}</td>
                            <td class='text-center'>C{$i}</td>
                            <td>" . htmlspecialchars($row->criteria) . "</td>
                            <td class='text-center'>" . htmlspecialchars($row->weight) . "</td>
                            <td class='text-center'>" . htmlspecialchars($row->attribute) . "</td>
                            <td class='text-center'>
                              <a href='bobot-edit.php?id=" . urlencode($row->id_criteria) . "' class='btn btn-info btn-sm'>Edit</a>
                              <a href='bobot-delete.php?id=" . urlencode($row->id_criteria) . "' class='btn btn-danger btn-sm ms-1' onclick=\"return confirm('Yakin ingin menghapus data ini?');\">Hapus</a>
                            </td>
                          </tr>";
                      }
                      $result->free();
                      ?>
                    </tbody>
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
