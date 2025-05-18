<?php
require "include/conn.php";

if (!isset($_POST['id_criteria'], $_POST['criteria'], $_POST['weight'], $_POST['attribute'])) {
    die("Data tidak lengkap");
}

$id = $_POST['id_criteria'];
$criteria = $_POST['criteria'];
$weight = $_POST['weight'];
$attribute = $_POST['attribute'];

$sql = "UPDATE saw_criterias SET criteria=?, weight=?, attribute=? WHERE id_criteria=?";
$stmt = $db->prepare($sql);
$stmt->bind_param("sdsi", $criteria, $weight, $attribute, $id);
if ($stmt->execute()) {
    header("Location: bobot.php");
    exit;
} else {
    echo "Gagal update data: " . $db->error;
}
