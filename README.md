# 🧮 SPK SAW – Sistem Pendukung Keputusan (Metode Simple Additive Weighting)

Sistem ini merupakan implementasi dari **metode Simple Additive Weighting (SAW)** untuk membantu proses pengambilan keputusan berbasis kriteria.  
Contohnya digunakan untuk **pemilihan mitra kerja terbaik**, **supplier terbaik**, atau **alternatif terbaik** berdasarkan sejumlah kriteria dengan bobot tertentu.

---

## 🚀 Fitur Utama

✅ **CRUD Alternatif**  
Menambahkan, mengedit, dan menghapus data alternatif (misalnya nama perusahaan).

✅ **CRUD Kriteria**  
Menentukan kriteria penilaian, bobot, dan jenis atribut (`benefit` atau `cost`).

✅ **Penilaian / Evaluasi Alternatif**  
Memberikan nilai tiap alternatif terhadap setiap kriteria (rentang nilai 0–5).

✅ **Normalisasi Otomatis (Matriks R)**  
Sistem menghitung nilai normalisasi berdasarkan jenis atribut.

✅ **Perhitungan Nilai Preferensi (V)**  
Menampilkan hasil akhir (ranking) berdasarkan total bobot terhitung.

✅ **Autodelete Evaluasi**  
Ketika data alternatif dihapus, seluruh evaluasi terkait otomatis ikut terhapus.

✅ **Validasi Input**  
- Nilai tidak boleh lebih dari **5**.  
- Tidak boleh memasukkan **nilai ganda** untuk kombinasi alternatif–kriteria yang sama.  
- Menampilkan pesan notifikasi (alert) dengan warna sesuai status.

✅ **Login Role-based**
- `admin` → memiliki akses penuh.  
- `alternatif` → akses terbatas.

---

## 🧩 Teknologi yang Digunakan

| Komponen | Teknologi |
|-----------|------------|
| Backend | PHP Native |
| Database | MySQL / MariaDB |
| Frontend | HTML, CSS, Bootstrap 5 |
| Server | XAMPP / Laragon |
| Metode | Simple Additive Weighting (SAW) |

---

## ⚙️ Instalasi

### 1️⃣ Clone Repository
```bash
git clone https://github.com/username/spk-saw.git
cd spk-saw
```

### 2️⃣ Buat Database
1. Buka **phpMyAdmin**
2. Buat database baru bernama:
   ```
   db_dss
   ```
3. Import file SQL berikut:
   ```
   database/db_dss.sql
   ```

### 3️⃣ Konfigurasi Koneksi Database
Edit file:  
```
include/conn.php
```
Sesuaikan dengan pengaturan lokal Anda:
```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_dss";
```

### 4️⃣ Jalankan Aplikasi
Jalankan di browser:
```
http://localhost/spk-saw
```

---

## 🔑 Login Awal

| Username | Password | Role |
|-----------|-----------|------|
| admin | admin | admin |
| alternatif | 12345 | alternatif |

---

## 📊 Contoh Struktur Database

### Tabel `saw_criterias`
| id_criteria | criteria             | weight | attribute |
|--------------|----------------------|---------|------------|
| 1 | Kualitas Produk | 2.5 | benefit |
| 2 | Pelayanan Pelanggan | 2.8 | benefit |
| 3 | Inovasi Teknologi | 1.5 | benefit |
| 4 | Harga Produk | 2.0 | cost |
| 5 | Waktu Pengiriman | 2.8 | cost |

### Tabel `saw_alternatives`
| id_alternative | name |
|----------------|------|
| 1 | PT Alpha Tech |
| 2 | PT Beta Solusindo |
| ... | ... |

### Tabel `saw_evaluations`
| id_alternative | id_criteria | value |
|----------------|--------------|--------|
| 1 | 1 | 4.5 |
| 1 | 2 | 3.2 |
| ... | ... | ... |

---

## 🧠 Alur Perhitungan SAW

1️⃣ **Membentuk matriks keputusan (X)**  
   Setiap alternatif dinilai berdasarkan setiap kriteria.

2️⃣ **Normalisasi matriks (R)**  
   - Jika atribut *benefit*:  
     `Rij = Xij / Xmax`
   - Jika atribut *cost*:  
     `Rij = Xmin / Xij`

3️⃣ **Hitung nilai preferensi (V)**  
   ```
   Vi = Σ (Rij × Wj)
   ```
   Di mana:  
   - `Wj` = bobot kriteria ke-j  
   - `Rij` = nilai normalisasi alternatif ke-i pada kriteria ke-j

4️⃣ **Perangkingan**  
   Alternatif dengan nilai `V` tertinggi menjadi pilihan terbaik.

---

## 🧾 Struktur Folder

```
spk-saw/
├── include/
│   ├── conn.php             # Koneksi database
├── database/
│   └── db_dss.sql           # File SQL database
├── matrik.php               # Normalisasi matriks
├── matrik-simpan.php        # Simpan evaluasi
├── preferensi.php           # Perhitungan nilai V
├── alternatif.php           # CRUD Alternatif
├── kriteria.php             # CRUD Kriteria
└── index.php                # Halaman utama (login)
```

---

## 💬 Pesan Kesalahan dan Validasi

| Situasi | Pesan Ditampilkan |
|----------|------------------|
| Nilai > 5 | ❌ "Nilai harus di antara 0 sampai 5!" |
| Duplikat data alternatif–kriteria | ⚠️ "Data ini sudah terisi!" |
| Berhasil simpan data | ✅ "Data berhasil disimpan!" |
| Gagal koneksi database | ❌ "Terjadi kesalahan: [error detail]" |

---

## 🤝 Kontribusi

1. Fork repository ini  
2. Buat branch baru:  
   ```bash
   git checkout -b fitur-baru
   ```
3. Commit perubahan:  
   ```bash
   git commit -m "Menambahkan fitur baru"
   ```
4. Push branch:  
   ```bash
   git push origin fitur-baru
   ```
5. Buat Pull Request 🎉

---

## 🧑‍💻 Author

**Ade Nova Wiguna**  
💼 Frontend Developer  
☕ Code + Coffee = ❤️  
📧 Email: adenovawiguna@gmail.com  
🌐 GitHub: [@NovaaaLv](https://github.com/NovaaaLv)

---

## 📜 Lisensi

Proyek ini dirilis di bawah lisensi **MIT**.  
Silakan digunakan, dimodifikasi, dan dikembangkan untuk kebutuhan pembelajaran atau penelitian.
