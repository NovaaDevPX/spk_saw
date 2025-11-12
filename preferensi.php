<!DOCTYPE html>
<html lang="en">
<?php
require "layout/head.php";
require "preferensi-fungsi.php";
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
        <h3>Hasil Perangkingan</h3>
      </div>

      <div class="page-content">
        <section class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Ranking Berdasarkan Nilai Akhir (P)</h4>
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped mb-0">
                    <thead>
                      <tr>
                        <th>Peringkat</th>
                        <th>Alternatif</th>
                        <th>Nilai Akhir (P)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      list($values, $alternatif) = getEvaluasi($db);
                      list($krit, $bobot) = getKriteria($db);

                      if (empty($values)) {
                        echo "<tr><td colspan='3' class='text-danger text-center'>Belum ada data evaluasi.</td></tr>";
                      } else {
                        $R = hitungNormalisasi($values, $krit, $bobot);
                        $P = hitungNilaiAkhir($R);
                        $ranking = perangkingan($P, $alternatif);
                        $duaKoma = [];
                        foreach ($ranking as $r) {
                          $duaKoma[] = number_format($r['nilai'], 2);
                        }
                        $count = array_count_values($duaKoma);

                        foreach ($ranking as $row) {
                          $formatted = $count[number_format($row['nilai'], 2)] > 1
                            ? number_format($row['nilai'], 3)
                            : number_format($row['nilai'], 2);

                          echo "<tr>
                                  <td>{$row['ranking']}</td>
                                  <td>{$row['alt_label']} {$row['name']}</td>
                                  <td>{$formatted}</td>
                                </tr>";
                        }
                      }
                      ?>
                    </tbody>
                  </table>
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