<!DOCTYPE html>
<html lang="en">
<?php
require "layout/head.php";
require "include/conn.php";
require "W.php";
require "R.php";
?>

<body>
  <div id="app">
    <?php require "layout/sidebar.php"; ?>
    <div id="main">
      <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
          <i class="bi bi-justify fs-3"></i>
        </a>
      </header>
      <div class="page-heading">
        <h3>Nilai Preferensi (P)</h3>
      </div>
      <div class="page-content">
        <section class="row">
          <div class="col-12">
            <div class="card">

              <div class="card-header">
                <h4 class="card-title">Tabel Nilai Preferensi (P)</h4>
              </div>
              <div class="card-content">
                <div class="card-body">
                  <p class="card-text">
                    Nilai preferensi (P) merupakan penjumlahan dari perkalian matriks ternormalisasi R dengan vektor bobot W.
                  </p>
                </div>
                <div class="table-responsive">
                  <?php
                  if (!empty($R)) {
                    // Ambil bobot dari tabel kriteria
                    $sql = "SELECT id_criteria, weight FROM saw_criterias ORDER BY id_criteria";
                    $result = $db->query($sql);
                    $W = array();
                    $totalWeight = 0;

                    while ($row = $result->fetch_object()) {
                      $W[$row->id_criteria] = $row->weight;
                      $totalWeight += $row->weight;
                    }
                    $result->free();

                    // Konversi bobot ke proporsi (misal total 100 → jadi 0–1)
                    foreach ($W as $key => $value) {
                      $W[$key] = $value / $totalWeight;
                    }

                    // Hitung nilai preferensi (P)
                    $V = array();
                    foreach ($R as $id_alt => $nilai) {
                      $V[$id_alt] =
                        ($nilai[0] * $W[1]) +
                        ($nilai[1] * $W[2]) +
                        ($nilai[2] * $W[3]) +
                        ($nilai[3] * $W[4]) +
                        ($nilai[4] * $W[5]);
                    }

                    // Urutkan nilai P dari terbesar ke terkecil untuk ranking
                    arsort($V);

                    // Cek nilai duplikat 2 desimal
                    $check = [];
                    foreach ($V as $id_alt => $val) {
                      $rounded2 = number_format($val, 2, '.', '');
                      $check[$rounded2][] = $id_alt;
                    }
                  ?>

                    <table class="table table-striped mb-0 mt-4">
                      <caption>Nilai Preferensi (P) & Ranking</caption>
                      <tr>
                        <th>Ranking</th>
                        <th>Alternatif</th>
                        <th>Nilai P</th>
                      </tr>
                      <?php
                      $rank = 1;
                      foreach ($V as $id_alt => $nilai_v) {
                        $sql = "SELECT name FROM saw_alternatives WHERE id_alternative = $id_alt";
                        $alt = $db->query($sql)->fetch_object();

                        $rounded2 = number_format($nilai_v, 2, '.', '');
                        // Jika duplikat 2 desimal, tampilkan 3 desimal
                        if (count($check[$rounded2]) > 1) {
                          $display = number_format($nilai_v, 3, '.', '');
                        } else {
                          $display = $rounded2;
                        }

                        echo "<tr>
                          <td>$rank</td>
                          <td>A$id_alt - $alt->name</td>
                          <td>$display</td>
                        </tr>";
                        $rank++;
                      }
                      ?>
                    </table>

                  <?php
                  } else {
                    echo "<table class='table table-striped mb-0 mt-4'>
                      <tr>
                          <td colspan='3' class='text-center text-danger'>Belum ada data Nilai Preferensi (P).</td>
                      </tr>
                    </table>";
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php require "layout/footer.php"; ?>
    </div>
  </div>
  <?php require "layout/js.php"; ?>
</body>

</html>