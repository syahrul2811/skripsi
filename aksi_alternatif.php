<?php
require "include/conn.php";

if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $name = $_POST['name'];

    // Update data
    $stmt = $db->prepare("UPDATE saw_alternatives SET name=? WHERE id_alternative=?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();

    header("Location: ekstrakurikuler.php");
    exit;
}

if(isset($_POST['hapus'])){
    $id = $_POST['id'];
    $stmt = $db->prepare("DELETE FROM saw_alternatives WHERE id_alternative=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: ekstrakurikuler.php");
    exit;
}

if(isset($_POST['tambah'])){
    $name = $_POST['name'];
    $stmt = $db->prepare("INSERT INTO saw_alternatives(name) VALUES(?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();

    header("Location: ekstrakurikuler.php");
    exit;
}
?>
