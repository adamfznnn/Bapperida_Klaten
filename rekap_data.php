<?php
include 'koneksi.php';

$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];
$q = $_GET['q'] ?? '';

$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

$where = '';
if ($q != '') {
  $safe = mysqli_real_escape_string($conn, $q);
  $where = "WHERE nama LIKE '%$safe%' OR nip LIKE '%$safe%'";
}

$qPeg = mysqli_query($conn,"
  SELECT * FROM pegawai
  $where
  ORDER BY id_peg ASC
");

while ($p = mysqli_fetch_assoc($qPeg)) {

  $total_dl = 0;
  echo "<tr>";
  echo "<td>$p[id_peg]</td>";
  echo "<td class='text-start'>$p[nama]</td>";
  echo "<td>".($p['status'] ?? 'ASN')."</td>";
  echo "<td>$p[nip]</td>";

  for ($d=1; $d<=$jumlah_hari; $d++) {
    $tgl = "$tahun-$bulan-".str_pad($d,2,'0',STR_PAD_LEFT);

    $cek = mysqli_query($conn,"
      SELECT 1 FROM sppd_personel
      WHERE id_peg='$p[id_peg]'
      AND '$tgl' BETWEEN tgl_pergi AND tgl_pulang
    ");

    if (mysqli_num_rows($cek) > 0) {
      echo "<td class='bg-dl'>DL</td>";
      $total_dl++;
    } else {
      echo "<td></td>";
    }
  }

  echo "<td class='fw-bold'>$total_dl</td>";
  echo "</tr>";
}
