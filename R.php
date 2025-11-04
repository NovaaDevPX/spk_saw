<?php
// Ambil data nilai dari saw_evaluations
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

// Inisialisasi array untuk menyimpan nilai per kriteria
$X = array(1 => array(), 2 => array(), 3 => array(), 4 => array(), 5 => array());

// Simpan nilai ke array $X
while ($row = $result->fetch_object()) {
  array_push($X[1], round($row->C1, 2));
  array_push($X[2], round($row->C2, 2));
  array_push($X[3], round($row->C3, 2));
  array_push($X[4], round($row->C4, 2));
  array_push($X[5], round($row->C5, 2));
}
$result->free();

// Hitung max dan min per kriteria dengan pengecekan jika array kosong
$max = [];
$min = [];
for ($i = 1; $i <= 5; $i++) {
  if (!empty($X[$i])) {
    $max[$i] = max($X[$i]);
    $min[$i] = min($X[$i]);
  } else {
    // Default agar tidak error jika tidak ada data
    $max[$i] = 1;
    $min[$i] = 1;
  }
}

// Ambil matriks ternormalisasi R
$sql = "SELECT
          a.id_alternative,
          SUM(IF(a.id_criteria=1, IF(b.attribute='benefit', a.value/{$max[1]}, {$min[1]}/a.value),0)) AS C1,
          SUM(IF(a.id_criteria=2, IF(b.attribute='benefit', a.value/{$max[2]}, {$min[2]}/a.value),0)) AS C2,
          SUM(IF(a.id_criteria=3, IF(b.attribute='benefit', a.value/{$max[3]}, {$min[3]}/a.value),0)) AS C3,
          SUM(IF(a.id_criteria=4, IF(b.attribute='benefit', a.value/{$max[4]}, {$min[4]}/a.value),0)) AS C4,
          SUM(IF(a.id_criteria=5, IF(b.attribute='benefit', a.value/{$max[5]}, {$min[5]}/a.value),0)) AS C5
        FROM saw_evaluations a
        JOIN saw_criterias b USING(id_criteria)
        GROUP BY a.id_alternative
        ORDER BY a.id_alternative";

$result = $db->query($sql);

// Simpan hasil R
$R = array();
while ($row = $result->fetch_object()) {
  $R[$row->id_alternative] = array(
    round($row->C1, 2),
    round($row->C2, 2),
    round($row->C3, 2),
    round($row->C4, 2),
    round($row->C5, 2)
  );
}
$result->free();
