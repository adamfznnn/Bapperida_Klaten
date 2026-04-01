<?php
include 'koneksi.php';

$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

$nama_bulan = [
  1=>"JANUARI",2=>"FEBRUARI",3=>"MARET",4=>"APRIL",5=>"MEI",6=>"JUNI",
  7=>"JULI",8=>"AGUSTUS",9=>"SEPTEMBER",10=>"OKTOBER",11=>"NOVEMBER",12=>"DESEMBER"
];

$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Rekapitulasi Perjalanan Dinas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-size: 12px; }
th, td { text-align: center; vertical-align: middle; }
.bg-minggu { background-color: #e6b8cf; }
.bg-dl { background-color: #ffc107; font-weight: bold; }
.table thead th { background:#f8f9fa; }
</style>
</head>
<body>

<div class="container-fluid mt-3">

<h5 class="text-center fw-bold">
REKAPITULASI HARIAN PERJALANAN DINAS APARATUR<br>
BULAN <?= $nama_bulan[(int)$bulan] ?> <?= $tahun ?>
</h5>

<!-- FILTER BULAN TAHUN -->
<form class="row g-2 mb-2">
  <div class="col-md-2">
    <select name="bulan" class="form-select form-select-sm">
      <?php foreach ($nama_bulan as $k=>$v): ?>
        <option value="<?= $k ?>" <?= ($k==$bulan?'selected':'') ?>><?= $v ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div class="col-md-2">
    <input type="number" name="tahun" class="form-control form-control-sm" value="<?= $tahun ?>">
  </div>
  <div class="col-md-2">
    <button class="btn btn-primary btn-sm">Tampilkan</button>
  </div>
</form>

<!-- LIVE SEARCH -->
<div class="row mb-2">
  <div class="col-md-4 ms-auto">
    <input type="text" id="search" class="form-control form-control-sm"
           placeholder="Cari Nama / NIP...">
  </div>
</div>

<div class="table-responsive">
<table class="table table-bordered table-sm">
<thead>
<tr>
  <th rowspan="3">NO</th>
  <th rowspan="3">NAMA</th>
  <th rowspan="3">STATUS</th>
  <th rowspan="3">NIP</th>
  <th colspan="<?= $jumlah_hari ?>">HARI / TANGGAL</th>
  <th rowspan="3">TOTAL DL</th>
</tr>
<tr>
<?php
for ($d=1; $d<=$jumlah_hari; $d++) {
  $hari = date('w', strtotime("$tahun-$bulan-$d"));
  $cls = ($hari==0) ? 'bg-minggu' : '';
  echo "<th class='$cls'>".strtoupper(date('D', strtotime("$tahun-$bulan-$d")))."</th>";
}
?>
</tr>
<tr>
<?php for ($d=1; $d<=$jumlah_hari; $d++) echo "<th>$d</th>"; ?>
</tr>
</thead>

<tbody id="rekap-data">
<!-- AJAX LOAD -->
</tbody>

</table>
</div>
</div>

<script>
const searchInput = document.getElementById('search');
const rekapData = document.getElementById('rekap-data');

function loadData(keyword = '') {
  fetch(`rekap_data.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&q=${encodeURIComponent(keyword)}`)
    .then(res => res.text())
    .then(html => rekapData.innerHTML = html);
}

// load awal
loadData();

// live search
searchInput.addEventListener('keyup', () => {
  loadData(searchInput.value);
});
</script>

</body>
</html>
