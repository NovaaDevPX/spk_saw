<?php
require "include/conn.php";

/**
 * Ambil data kriteria: atribut & bobot
 */
function getKriteria($db)
{
  $krit = [];
  $bobot = [];
  $q = $db->query("SELECT id_criteria, attribute, weight FROM saw_criterias ORDER BY id_criteria");
  while ($r = $q->fetch_object()) {
    $krit[$r->id_criteria] = $r->attribute;
    $bobot[$r->id_criteria] = $r->weight;
  }
  $q->free();
  return [$krit, $bobot];
}

/**
 * Ambil semua data evaluasi dalam bentuk array
 */
function getEvaluasi($db)
{
  $values = [];
  $alts = [];
  $altIndex = 1; // untuk A1, A2, dst.
  $altMap = [];  // mapping urutan ke id asli

  $q = $db->query("SELECT a.id_alternative, b.name, a.id_criteria, a.value 
                   FROM saw_evaluations a
                   JOIN saw_alternatives b USING(id_alternative)
                   ORDER BY a.id_alternative, a.id_criteria");

  while ($r = $q->fetch_object()) {
    // buat urutan baru jika belum ada
    if (!isset($altMap[$r->id_alternative])) {
      $altMap[$r->id_alternative] = $altIndex++;
      $alts[$altMap[$r->id_alternative]] = $r->name;
    }

    $idx = $altMap[$r->id_alternative];
    $values[$idx][$r->id_criteria] = $r->value;
  }
  $q->free();

  return [$values, $alts];
}

/**
 * Normalisasi dan hitung matriks R berdasarkan rumus
 */
function hitungNormalisasi($values, $krit, $bobot)
{
  $R = [];

  foreach ($values as $id_alt => $criteriaVals) {
    for ($j = 1; $j <= 5; $j++) {
      $xij = isset($criteriaVals[$j]) ? (float)$criteriaVals[$j] : 0;
      $wj = isset($bobot[$j]) ? (float)$bobot[$j] : 0;

      // Normalisasi berdasarkan skala 1–5 dan bobot (%)
      $rVal = ($xij / 5) * ($wj / 100);

      // Jika cost, dibalik
      if (isset($krit[$j]) && $krit[$j] === 'cost') {
        $rVal = (1 - ($xij / 5)) * ($wj / 100);
      }

      $R[$id_alt][$j] = $rVal;
    }
  }

  return $R;
}

/**
 * Hitung nilai akhir (P)
 */
function hitungNilaiAkhir($R)
{
  $P = [];

  foreach ($R as $id_alt => $nilaiR) {
    $P[$id_alt] = array_sum($nilaiR); // jumlahkan semua nilai kriteria
  }

  return $P;
}

/**
 * Urutkan perangkingan berdasarkan nilai P
 */
function perangkingan($P, $alternatif)
{
  arsort($P); // urutkan dari terbesar ke terkecil
  $rank = [];
  $no = 1;

  foreach ($P as $id_alt => $nilai) {
    $rank[] = [
      'ranking' => $no++,
      'alt_label' => "A{$id_alt}", // gunakan urutan dinamis
      'name' => $alternatif[$id_alt],
      'nilai' => number_format($nilai, 3)
    ];
  }

  return $rank;
}
