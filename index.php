<?php
require "conn.php";
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$user_data = [];

if ($user_id) {
    $result = $db->query("SELECT name, photo FROM saw_users WHERE id_user = $user_id");
    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
    }
}

function get_total($table, $db) {
    $result = $db->query("SELECT COUNT(*) AS total FROM $table");
    return $result ? $result->fetch_assoc()['total'] : 0;
}

$jumlah_kriteria = get_total('saw_criterias', $db);
$jumlah_ekskul   = get_total('ekstrakurikuler', $db);
$jumlah_user     = get_total('saw_users', $db);
$jumlah_spk      = get_total('hasil_spk', $db);
?>

<!DOCTYPE html>
<html lang="en">
<?php require "layout/head.php"; ?>
<style>
  /* Reset box sizing */
  *, *::before, *::after {
    box-sizing: border-box;
  }

  body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f7fa;
    color: #333;
    overflow: hidden; /* Hide default scroll on body */
  }

  #app {
    display: flex;
    height: 100vh; /* Full viewport height */
  }

  /* Sidebar fixed di kiri */
  #app > aside {
    width: 250px;
    background: #2c3e50;
    color: #ecf0f1;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    padding: 20px 15px;
    overflow-y: auto; /* scroll jika konten sidebar tinggi */
    z-index: 1000;
  }

  /* Konten utama geser kanan sesuai lebar sidebar */
  .main-content {
    margin-left: 250px;
    flex-grow: 1;
    padding: 30px 40px;
    overflow-y: auto; /* scroll jika konten lebih tinggi */
    height: 100vh;
    background: #f4f7fa;
  }

  header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
  }

  .profile-info {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .profile-photo {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #5A9;
    box-shadow: 0 2px 8px rgba(90,153,0,0.3);
  }

  h1 {
    font-weight: 700;
    font-size: 1.8rem;
    color: #2c3e50;
  }

  p.subtitle {
    color: #666;
    margin-top: 4px;
  }

  .grid-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 24px;
    margin-top: 20px;
  }

  .card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    padding: 25px 20px;
    text-align: center;
    cursor: default;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
  }

  .card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
  }

  .card-icon {
    font-size: 3.4rem;
    margin-bottom: 15px;
  }

  .card-label {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 10px;
    color: #34495e;
  }

  .card-count {
    font-size: 2.8rem;
    font-weight: 700;
  }
</style>
<body>
  <div id="app">
    <?php require "layout/sidebar.php"; ?>

    <div class="main-content">
      <header>
        <h1>Dashboard Ekstrakurikuler</h1>
        <div class="profile-info">
          <?php if (!empty($user_data)): ?>
            <img src="uploads/<?= htmlspecialchars($user_data['photo']) ?>" alt="Foto Profil" class="profile-photo" />
            <div>
              <div style="font-weight:600; font-size:1rem; color:#2c3e50;"><?= htmlspecialchars($user_data['name']) ?></div>
              <small style="color:#7f8c8d;">Selamat datang!</small>
            </div>
          <?php else: ?>
            <div style="color:#7f8c8d;">User tidak ditemukan</div>
          <?php endif; ?>
        </div>
      </header>

      <p class="subtitle">Ringkasan data sistem pendukung keputusan ekstrakurikuler.</p>

      <div class="grid-cards">
        <?php
          $cards = [
            ['label' => 'Kriteria', 'count' => $jumlah_kriteria, 'icon' => 'bi-list-check', 'color' => '#27ae60'],
            ['label' => 'Ekstrakurikuler', 'count' => $jumlah_ekskul, 'icon' => 'bi-people-fill', 'color' => '#2980b9'],
            ['label' => 'Siswa', 'count' => $jumlah_user, 'icon' => 'bi-person-badge', 'color' => '#f39c12'],
            ['label' => 'User', 'count' => $jumlah_user, 'icon' => 'bi-person-circle', 'color' => '#8e44ad'],
            ['label' => 'Data SPK', 'count' => $jumlah_spk, 'icon' => 'bi-bar-chart-line-fill', 'color' => '#c0392b'],
          ];

          foreach ($cards as $card): ?>
            <div class="card" style="border-top: 6px solid <?= $card['color'] ?>;">
              <i class="bi <?= $card['icon'] ?> card-icon" style="color: <?= $card['color'] ?>;"></i>
              <div class="card-label"><?= htmlspecialchars($card['label']) ?></div>
              <div class="card-count" data-target="<?= $card['count'] ?>">0</div>
            </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

<?php require "layout/js.php"; ?>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.card-count');
    counters.forEach(counter => {
      const updateCount = () => {
        const target = +counter.getAttribute('data-target');
        let count = +counter.innerText;
        const increment = Math.ceil(target / 100);

        if (count < target) {
          count += increment;
          if (count > target) count = target;
          counter.innerText = count;
          setTimeout(updateCount, 20);
        } else {
          counter.innerText = target;
        }
      };
      updateCount();
    });
  });
</script>

</body>
</html>
