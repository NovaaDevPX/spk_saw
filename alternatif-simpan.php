<?php
require "include/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);

  if (!empty($name)) {
    // Reset auto_increment agar berurutan kembali
    $db->query("ALTER TABLE saw_alternatives AUTO_INCREMENT = 1");

    // Insert data baru
    $sql = "INSERT INTO saw_alternatives (name) VALUES ('$name')";

    if ($db->query($sql) === true) {
      header("Location: ./alternatif.php?status=success");
      exit;
    } else {
      echo "Error: " . $sql . "<br>" . $db->error;
    }
  } else {
    echo "Nama alternatif tidak boleh kosong.";
  }
}
