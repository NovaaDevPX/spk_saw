<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dokumentasi SPK SAW</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: "Poppins", sans-serif;
      padding: 40px;
      line-height: 1.7;
    }
    h1, h2, h3 {
      color: #0d6efd;
      margin-top: 30px;
    }
    pre {
      background-color: #222;
      color: #eee;
      padding: 10px 15px;
      border-radius: 8px;
      overflow-x: auto;
    }
    code {
      background-color: #f1f1f1;
      padding: 3px 6px;
      border-radius: 4px;
    }
    .table {
      margin-top: 10px;
      background-color: #fff;
    }
    footer {
      margin-top: 60px;
      text-align: center;
      color: #777;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🧮 SPK SAW – Sistem Pendukung Keputusan</h1>
    <p>
      Sistem ini merupakan implementasi dari
      <strong>Simple Additive Weighting (SAW)</strong> untuk membantu pengambilan
      keputusan berbasis kriteria — misalnya dalam pemilihan mitra kerja terbaik,
      supplier terbaik, atau alternatif paling unggul berdasarkan sejumlah kriteria
      dengan bobot tertentu.
    </p>

    <hr />

    <h2>🚀 Fitur Utama</h2>
    <ul>
      <li>CRUD Alternatif (Tambah, Edit, Hapus)</li>
      <li>CRUD Kriteria dengan bobot dan jenis atribut (<code>benefit</code> atau <code>cost</code>)</li>
      <li>Penilaian (Evaluasi) tiap alternatif terhadap kriteria</li>
      <li>Perhitungan normalisasi otomatis (Matriks R)</li>
      <li>Perhitungan nilai preferensi (V) dan perangkingan</li>
      <li>Autodelete evaluasi saat alternatif dihapus</li>
      <li>Validasi input dan pesan alert responsif</li>
      <li>Login berbasis role (Admin & Alternatif)</li>
    </ul>

    <h2>🧩 Teknologi yang Digunakan</h2>
    <table class="table table-bordered">
      <tr><th>Komponen</th><th>Teknologi</th></tr>
      <tr><td>Backend</td><td>PHP Native</td></tr>
      <tr><td>Database</td><td>MySQL / MariaDB</td></tr>
      <tr><td>Frontend</td><td>HTML, CSS, Bootstrap 5</td></tr>
      <tr><td>Server</td><td>XAMPP / Laragon</td></tr>
      <tr><td>Metode</td><td>Simple Additive Weighting (SAW)</td></tr>
    </table>

    <h2>⚙️ Instalasi</h2>
    <h5>1️⃣ Clone Repository</h5>
    <pre><code>git clone https://github.com/username/spk-saw.git
cd spk-saw</code></pre>

    <h5>2️⃣ Buat Database</h5>
    <ol>
      <li>Buka phpMyAdmin</li>
      <li>Buat database bernama <code>db_dss</code></li>
      <li>Import file SQL: <code>database/db_dss.sql</code></li>
    </ol>

    <h5>3️⃣ Konfigurasi Koneksi Database</h5>
    <pre><code>// include/conn.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_dss";</code></pre>

    <h5>4️⃣ Jalankan Aplikasi</h5>
    <pre><code>http://localhost/spk-saw</code></pre>

    <h2>🔑 Login Awal</h2>
    <table class="table table-striped">
      <tr><th>Username</th><th>Password</th><th>Role</th></tr>
      <tr><td>admin</td><td>admin</td><td>admin</td></tr>
      <tr><td>alternatif</td><td>12345</td><td>alternatif</td></tr>
    </table>

    <h2>📊 Contoh Struktur Database</h2>
    <h5>Tabel saw_criterias</h5>
    <table class="table table-bordered">
      <tr><th>id_criteria</th><th>criteria</th><th>weight</th><th>attribute</th></tr>
      <tr><td>1</td><td>Kualitas Produk</td><td>2.5</td><td>benefit</td></tr>
      <tr><td>2</td><td>Pelayanan Pelanggan</td><td>2.8</td><td>benefit</td></tr>
      <tr><td>3</td><td>Inovasi Teknologi</td><td>1.5</td><td>benefit</td></tr>
      <tr><td>4</td><td>Harga Produk</td><td>2.0</td><td>cost</td></tr>
      <tr><td>5</td><td>Waktu Pengiriman</td><td>2.8</td><td>cost</td></tr>
    </table>

    <h2>🧠 Alur Perhitungan SAW</h2>
    <ol>
      <li>
        <strong>Membentuk Matriks Keputusan (X):</strong> Setiap alternatif dinilai
        berdasarkan kriteria.
      </li>
      <li>
        <strong>Normalisasi Matriks (R):</strong>
        <ul>
          <li><code>benefit</code> → Rij = Xij / max(Xij)</li>
          <li><code>cost</code> → Rij = min(Xij) / Xij</li>
        </ul>
      </li>
      <li>
        <strong>Hitung Nilai Preferensi (V):</strong>
        <pre><code>Vi = Σ (Rij × Wj)</code></pre>
      </li>
      <li>
        <strong>Perangkingan:</strong> Alternatif dengan nilai V tertinggi menjadi
        pilihan terbaik.
      </li>
    </ol>

    <h2>🧾 Struktur Folder</h2>
    <pre><code>spk-saw/
├── include/
│   ├── conn.php
├── database/
│   └── db_dss.sql
├── matrik.php
├── matrik-simpan.php
├── preferensi.php
├── alternatif.php
├── kriteria.php
└── index.php</code></pre>

    <h2>💬 Validasi dan Pesan</h2>
    <table class="table table-bordered">
      <tr><th>Situasi</th><th>Pesan Ditampilkan</th></tr>
      <tr><td>Nilai > 5</td><td>❌ Nilai harus di antara 0 sampai 5!</td></tr>
      <tr><td>Data duplikat</td><td>⚠️ Data ini sudah terisi!</td></tr>
      <tr><td>Berhasil simpan</td><td>✅ Data berhasil disimpan!</td></tr>
      <tr><td>Kesalahan server</td><td>❌ Terjadi kesalahan [error detail]</td></tr>
    </table>

    <h2>🤝 Kontribusi</h2>
    <ol>
      <li>Fork repository ini</li>
      <li>Buat branch baru: <code>git checkout -b fitur-baru</code></li>
      <li>Commit perubahan: <code>git commit -m "Menambahkan fitur baru"</code></li>
      <li>Push: <code>git push origin fitur-baru</code></li>
      <li>Buat Pull Request 🎉</li>
    </ol>

    <h2>🧑‍💻 Author</h2>
    <p>
      <strong>Ade Nova Wiguna</strong><br />
      💼 Frontend Developer<br />
      ☕ Code + Coffee = ❤️<br />
      📧 <a href="mailto:adenovawiguna@gmail.com">adenovawiguna@gmail.com</a><br />
      🌐 GitHub:
      <a href="https://github.com/NovaaaLv" target="_blank">@NovaaaLv</a>
    </p>

    <h2>📜 Lisensi</h2>
    <p>Proyek ini dirilis di bawah lisensi <strong>MIT</strong>. Silakan digunakan, dimodifikasi, dan dikembangkan untuk kebutuhan pembelajaran atau penelitian.</p>

    <footer>
      <hr />
      <p>© 2025 SPK SAW by Ade Nova Wiguna</p>
    </footer>
  </div>
</body>
</html>
