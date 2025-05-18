<?php
require 'include/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data penilaian
    foreach ($_POST['minat'] as $id_alternative => $minat) {
        $minat = $_POST['minat'][$id_alternative];
        $relevansi = $_POST['relevansi'][$id_alternative];
        $pengembangan = $_POST['pengembangan'][$id_alternative];
        $pembina = $_POST['pembina'][$id_alternative];
        $berprestasi = $_POST['berprestasi'][$id_alternative];
        

        // Query untuk menyimpan penilaian
        $sql = "INSERT INTO saw_evaluations (id_alternative, id_criteria, value) VALUES
                (?, 1, ?), (?, 2, ?), (?, 3, ?), (?, 4, ?), (?, 5, ?)
                ON DUPLICATE KEY UPDATE value = VALUES(value)";

        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            die('Query prepare failed: ' . $db->error);
        }

        $stmt->bind_param('ididididid',
            $id_alternative, $minat,
            $id_alternative, $tujuan,
            $id_alternative, $pengembangan,
            $id_alternative, $dukungan,
            $id_alternative, $prestasi
        );

        if (!$stmt->execute()) {
            die('Query execution failed: ' . $stmt->error);
        }
    }

    // Redirect setelah proses selesai
    header('Location: matrik.php');
    exit;
}
?>
