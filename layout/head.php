<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPK - Mitra Kerja Telkomsel</title>

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <!-- 🔥 Gunakan path absolut -->
  <link rel="stylesheet" href="/spksaw-master/assets/css/bootstrap.css">
  <link rel="stylesheet" href="/spksaw-master/assets/vendors/iconly/bold.css">
  <link rel="stylesheet" href="/spksaw-master/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
  <link rel="stylesheet" href="/spksaw-master/assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="/spksaw-master/assets/css/app.css">
  <link rel="shortcut icon" href="/spksaw-master/assets/images/icon-tsel.webp" type="image/x-icon">
</head>

<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Pastikan user sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
  header("Location: /spksaw-master/login.php");
  exit;
}

// Dapatkan halaman saat ini
$currentPage = basename($_SERVER['PHP_SELF']);

// Daftar halaman yang hanya boleh diakses admin
$adminOnlyPages = ['alternatif.php', 'bobot.php', 'matrik.php'];

// Jika halaman ini termasuk halaman admin dan role bukan admin
if (in_array($currentPage, $adminOnlyPages) && $_SESSION['role'] !== 'admin') {
  // Redirect sesuai role
  if ($_SESSION['role'] === 'alternatif') {
    header("Location: /spksaw-master/index.php");
  } else {
    header("Location: /spksaw-master/login.php?error=unauthorized");
  }
  exit;
}
?>