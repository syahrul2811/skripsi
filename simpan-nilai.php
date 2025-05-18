<?php
require "include/conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nilai'])) {
    $nilai = $_POST['nilai'];

    foreach ($nilai as $id_alternative => $kriterias) {
        foreach ($kriterias as $id_criteria => $value) {
            $id_alternative = (int)$id_alternative;
            $id_criteria = (int)$id_criteria;
            $value = floatval($value);

            // Hapus nilai lama
            $db->query("DELETE FROM saw_evaluations 
                        WHERE id_alternative = $id_alternative 
                        AND id_criteria = $id_criteria");

            // Simpan nilai baru
            $stmt = $db->prepare("INSERT INTO saw_evaluations (id_alternative, id_criteria, value)
                                  VALUES (?, ?, ?)");
            $stmt->bind_param("iid", $id_alternative, $id_criteria, $value);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: input_nilai.php?status=sukses");
    exit;
} else {
    header("Location: input_nilai.php");
    exit;
}
?>
