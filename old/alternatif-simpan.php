<?php
require "include/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);

  if (!empty($name)) {
    // Ambil semua id_alternative yang ada
    $result = $db->query("SELECT id_alternative FROM saw_alternatives ORDER BY id_alternative ASC");

    $missingId = 1; // default mulai dari 1
    $lastId = 0;

    if ($result->num_rows > 0) {
      $existingIds = [];
      while ($row = $result->fetch_assoc()) {
        $existingIds[] = (int)$row['id_alternative'];
      }

      // Cari ID yang hilang (gap)
      for ($i = 1; $i <= max($existingIds); $i++) {
        if (!in_array($i, $existingIds)) {
          $missingId = $i;
          break;
        }
      }

      // Jika tidak ada gap, pakai id terakhir + 1
      if (in_array($missingId, $existingIds)) {
        $missingId = max($existingIds) + 1;
      }
    }

    // Insert data baru dengan ID yang ditentukan
    $stmt = $db->prepare("INSERT INTO saw_alternatives (id_alternative, name) VALUES (?, ?)");
    $stmt->bind_param("is", $missingId, $name);

    if ($stmt->execute()) {
      header("Location: ./alternatif.php?status=success&id=$missingId");
      exit;
    } else {
      echo "Error: " . $stmt->error;
    }

    $stmt->close();
  } else {
    echo "Nama alternatif tidak boleh kosong.";
  }
}
