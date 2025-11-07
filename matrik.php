<!DOCTYPE html>
<html lang="en">
<?php
require "layout/head.php";
require "include/conn.php";
?>

<body>
  <div id="app">
    <?php require "layout/sidebar.php"; ?>
    <div id="main">
      <?php
      // tampilkan pesan jika ada ?msg=... pada URL
      if (isset($_GET['msg'])) {
        $msg = htmlspecialchars($_GET['msg']);
        $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'info';

        // Tentukan warna alert Bootstrap
        $class = 'alert-info';
        if ($type === 'success') $class = 'alert-success';
        if ($type === 'error') $class = 'alert-danger';
        if ($type === 'warning') $class = 'alert-warning';

        echo "
  <div class='alert {$class} alert-dismissible fade show' role='alert' style='margin:10px 0;'>
    {$msg}
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
  </div>";
      }
      ?>

      <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
          <i class="bi bi-justify fs-3"></i>
        </a>
      </header>

      <div class="page-heading">
        <h3>Matriks</h3>
      </div>

      <div class="page-content">
        <section class="row">
          <div class="col-12">
            <div class="card">

              <div class="card-header">
                <h4 class="card-title">Matriks Keputusan (X) &amp; Ternormalisasi (R)</h4>
              </div>

              <div class="card-content">
                <div class="card-body">
                  <p class="card-text">
                    Melakukan perhitungan normalisasi untuk mendapatkan matriks nilai ternormalisasi (R), dengan ketentuan:<br>
                    Jika atribut <b>benefit</b> maka digunakan rumus: Rij = ( Xij / max{Xij} )<br>
                    Jika atribut <b>cost</b> maka digunakan rumus: Rij = ( min{Xij} / Xij )
                  </p>
                </div>

                <button type="button" class="btn btn-outline-success btn-sm m-2" data-bs-toggle="modal" data-bs-target="#inlineForm">
                  Isi Nilai Alternatif
                </button>

                <div class="table-responsive">

                  <!-- ================== -->
                  <!-- MATRIX KEPUTUSAN X -->
                  <!-- ================== -->
                  <table class="table table-striped mb-0">
                    <caption>Matrik Keputusan (X)</caption>
                    <tr>
                      <th rowspan='2'>Alternatif</th>
                      <th colspan='6'>Kriteria</th>
                    </tr>
                    <tr>
                      <th>C1</th>
                      <th>C2</th>
                      <th>C3</th>
                      <th>C4</th>
                      <th colspan="2">C5</th>
                    </tr>
                    <?php
                    $sql = "SELECT
                              a.id_alternative,
                              b.name,
                              SUM(IF(a.id_criteria=1,a.value,0)) AS C1,
                              SUM(IF(a.id_criteria=2,a.value,0)) AS C2,
                              SUM(IF(a.id_criteria=3,a.value,0)) AS C3,
                              SUM(IF(a.id_criteria=4,a.value,0)) AS C4,
                              SUM(IF(a.id_criteria=5,a.value,0)) AS C5
                            FROM saw_evaluations a
                            JOIN saw_alternatives b USING(id_alternative)
                            GROUP BY a.id_alternative
                            ORDER BY a.id_alternative";
                    $result = $db->query($sql);

                    $X = [1 => [], 2 => [], 3 => [], 4 => [], 5 => []];
                    $alternatifNama = [];

                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_object()) {
                        $X[1][] = round($row->C1, 2);
                        $X[2][] = round($row->C2, 2);
                        $X[3][] = round($row->C3, 2);
                        $X[4][] = round($row->C4, 2);
                        $X[5][] = round($row->C5, 2);
                        $alternatifNama[$row->id_alternative] = $row->name;

                        echo "<tr class='center'>
                                <th>A<sub>{$row->id_alternative}</sub> {$row->name}</th>
                                <td>" . round($row->C1, 2) . "</td>
                                <td>" . round($row->C2, 2) . "</td>
                                <td>" . round($row->C3, 2) . "</td>
                                <td>" . round($row->C4, 2) . "</td>
                                <td>" . round($row->C5, 2) . "</td>
                                <td><a href='keputusan-hapus.php?id={$row->id_alternative}' class='btn btn-danger btn-sm'>Hapus</a></td>
                              </tr>\n";
                      }
                    } else {
                      echo "<tr><td colspan='7' class='text-danger text-center'>Belum ada data evaluasi.</td></tr>";
                    }
                    $result->free();
                    ?>
                  </table>


                  <!-- ======================= -->
                  <!-- MATRIX TERNORMALISASI R -->
                  <!-- ======================= -->
                  <table class="table table-striped mb-0 mt-4">
                    <caption>Matrik Ternormalisasi (R)</caption>
                    <tr>
                      <th rowspan='2'>Alternatif</th>
                      <th colspan='5'>Kriteria</th>
                    </tr>
                    <tr>
                      <th>C1</th>
                      <th>C2</th>
                      <th>C3</th>
                      <th>C4</th>
                      <th>C5</th>
                    </tr>
                    <?php
                    // Ambil atribut dan bobot kriteria
                    $krit = [];
                    $bobot = [];
                    $q = $db->query("SELECT id_criteria, attribute, weight FROM saw_criterias ORDER BY id_criteria");
                    while ($r = $q->fetch_object()) {
                      $krit[(int)$r->id_criteria] = $r->attribute; // 'benefit' atau 'cost'
                      $bobot[(int)$r->id_criteria] = (float)$r->weight; // simpan bobot asli 
                    }
                    $q->free();

                    // Ambil semua nilai evaluasi ke array values[id_alt][id_crit] = value
                    $values = [];
                    $q = $db->query("SELECT id_alternative, id_criteria, value FROM saw_evaluations");
                    while ($row = $q->fetch_object()) {
                      $values[(int)$row->id_alternative][(int)$row->id_criteria] = (float)$row->value;
                    }
                    $q->free();

                    if (empty($values)) {
                      echo "<tr><td colspan='6' class='text-danger text-center'>Belum ada data untuk dinormalisasi.</td></tr>";
                      $R = [];
                    } else {
                      $R = [];
                      foreach ($alternatifNama as $id_alt => $name_alt) {
                        $rowR = [];
                        for ($j = 1; $j <= 5; $j++) {
                          // Ambil nilai & bobot
                          $xij = isset($values[$id_alt][$j]) ? (float)$values[$id_alt][$j] : null;
                          $wj = isset($bobot[$j]) ? (float)$bobot[$j] : 0;

                          if ($xij === null) {
                            $rowR[$j] = 0;
                          } else {
                            // Terapkan rumus : (nilai / 5) * (bobot / 100)
                            $rVal = ($xij / 5) * ($wj / 100);

                            // Jika atribut cost, dibalik (semakin kecil semakin baik)
                            if (isset($krit[$j]) && $krit[$j] === 'cost') {
                              $rVal = (1 - ($xij / 5)) * ($wj / 100);
                            }

                            $rowR[$j] = $rVal;
                          }
                        }

                        // Simpan hasil normalisasi untuk alternatif ini
                        $R[$id_alt] = [$rowR[1], $rowR[2], $rowR[3], $rowR[4], $rowR[5]];

                        // Tampilkan hasil di tabel
                        echo "<tr class='center'>";
                        echo "<th>A{$id_alt} {$name_alt}</th>";
                        for ($j = 1; $j <= 5; $j++) {
                          $display = (isset($values[$id_alt][$j])) ? number_format($R[$id_alt][$j - 1], 2) : '-';
                          echo "<td>{$display}</td>";
                        }
                        echo "</tr>\n";
                      }
                    }
                    ?>
                  </table>

                  <!-- ======================= -->
                  <!-- NILAI AKHIR (P) -->
                  <!-- ======================= -->
                  <table class="table table-striped mb-0 mt-4">
                    <caption>Nilai Akhir (P)</caption>
                    <tr>
                      <th>Alternatif</th>
                      <th>Nilai Akhir (P)</th>
                    </tr>
                    <?php
                    if (!empty($R)) {
                      // Ambil bobot 
                      $bobotQuery = $db->query("SELECT id_criteria, weight FROM saw_criterias ORDER BY id_criteria");
                      $W = [];
                      while ($row = $bobotQuery->fetch_object()) {
                        $W[$row->id_criteria] = (float)$row->weight;
                      }
                      $bobotQuery->free();

                      // Hitung nilai akhir
                      $nilaiP = [];
                      foreach ($R as $id => $nilaiR) {
                        $V = 0;
                        for ($j = 1; $j <= 5; $j++) {
                          // dijumlahkan 
                          $V += $nilaiR[$j - 1];
                        }
                        $nilaiP[$id] = $V;
                      }

                      // Cek nilai yang sama hingga 2 desimal
                      $count2dec = [];
                      foreach ($nilaiP as $id => $val) {
                        $rounded2 = number_format($val, 2, '.', '');
                        $count2dec[$rounded2][] = $id;
                      }

                      // Tampilkan tabel hasil akhir
                      foreach ($nilaiP as $id => $val) {
                        $rounded2 = number_format($val, 2, '.', '');
                        if (count($count2dec[$rounded2]) > 1) {
                          $display = number_format($val, 3, '.', '');
                        } else {
                          $display = $rounded2;
                        }

                        echo "<tr class='center'>
              <th>A{$id} {$alternatifNama[$id]}</th>
              <td>{$display}</td>
            </tr>\n";
                      }
                    } else {
                      echo "<tr><td colspan='2' class='text-danger text-center'>Belum ada hasil perhitungan.</td></tr>";
                    }
                    ?>
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

  <!-- ========================== -->
  <!-- MODAL INPUT NILAI -->
  <!-- ========================== -->
  <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel33">Isi Nilai Kandidat </h4>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <i data-feather="x"></i>
          </button>
        </div>
        <form action="matrik-simpan.php" method="POST">
          <div class="modal-body">
            <label>Nama Alternatif:</label>
            <div class="form-group">
              <select class="form-control form-select" name="id_alternative" required>
                <?php
                $sql = 'SELECT id_alternative,name FROM saw_alternatives';
                $result = $db->query($sql);
                while ($row = $result->fetch_object()) {
                  echo '<option value="' . $row->id_alternative . '">' . $row->name . '</option>';
                }
                $result->free();
                ?>
              </select>
            </div>

            <label>Kriteria:</label>
            <div class="form-group">
              <select class="form-control form-select" name="id_criteria" required>
                <?php
                $sql = 'SELECT * FROM saw_criterias';
                $result = $db->query($sql);
                while ($row = $result->fetch_object()) {
                  echo '<option value="' . $row->id_criteria . '">' . $row->criteria . '</option>';
                }
                $result->free();
                ?>
              </select>
            </div>

            <label>Nilai:</label>
            <div class="form-group">
              <input
                type="number"
                name="value"
                placeholder="Masukkan nilai..."
                class="form-control"
                required
                min="0"
                max="5"
                step="0.1"
                oninput="if(this.value > 5) this.value = 5;">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="submit" class="btn btn-primary ml-1">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php require "layout/js.php"; ?>
</body>

</html>