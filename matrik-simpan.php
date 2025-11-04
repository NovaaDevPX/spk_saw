<?php
require "include/conn.php";

$id_alternative = $_POST['id_alternative'];
$id_criteria = $_POST['id_criteria'];
$value = $_POST['value'];

// Validasi nilai
if ($value < 0 || $value > 5) {
  header("Location: matrik.php?msg=Nilai harus di antara 0 sampai 5!&type=warning");
  exit;
}

// Cek duplikat
$checkQuery = "SELECT * FROM saw_evaluations WHERE id_alternative = '$id_alternative' AND id_criteria = '$id_criteria'";
$checkResult = $db->query($checkQuery);

if ($checkResult->num_rows > 0) {
  header("Location: matrik.php?msg=Data ini sudah terisi!&type=warning");
  exit;
}

// Simpan data baru
$sql = "INSERT INTO saw_evaluations (id_alternative, id_criteria, value)
        VALUES ('$id_alternative', '$id_criteria', '$value')";
$result = $db->query($sql);

if ($result === true) {
  header("Location: matrik.php?msg=Data berhasil disimpan!&type=success");
} else {
  header("Location: matrik.php?msg=Terjadi kesalahan pada server!&type=error");
}
exit;
