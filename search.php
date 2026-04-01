<?php
include 'check_session.php';
include 'koneksi.php';

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where = "WHERE s.no_sppd LIKE '%$search%' 
              OR s.kota_tujuan LIKE '%$search%' 
              OR s.keperluan LIKE '%$search%'";
} else {
    $where = "";
}

$sql = "SELECT s.*, p.nama AS nama_pptk 
        FROM sppd s 
        LEFT JOIN pegawai p ON s.id_pptk = p.id_peg
        $where
        ORDER BY s.created_at DESC";

$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar SPPD - SIJADI</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
body {
    background: linear-gradient(135deg, #667eea, #764ba2);
    min-height: 100vh;
}

.header-box {
    background: white;
    padding: 20px 30px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.card-custom {
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.table thead {
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: white;
}

.btn-action {
    padding: 0.3rem 0.6rem;
    font-size: 0.8rem;
}

.search-box input {
    border-radius: 30px;
    padding: 10px 20px;
}

.search-box button {
    border-radius: 30px;
}
</style>
</head>

<body>

<div class="container py-4">

<!-- HEADER -->
<div class="header-box d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-file-alt me-2 text-primary"></i>Riwayat SPPD</h4>
        <small class="text-muted">Sistem Informasi Perjalanan Dinas</small>
    </div>

    <div class="d-flex gap-2">
        <a href="index.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah
        </a>
        <a href="dashboard.php" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- SEARCH -->
<div class="card card-custom mb-4">
<div class="card-body">
<form method="GET" class="row g-2 search-box">
    <div class="col-md-10">
        <input type="text" name="search" class="form-control"
        placeholder="Cari No SPPD, Kota Tujuan, atau Keperluan..."
        value="<?= htmlspecialchars($search); ?>">
    </div>
    <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Cari
        </button>
    </div>
</form>
</div>
</div>

<!-- TABLE -->
<div class="card card-custom">
<div class="card-body">
<div class="table-responsive">
<table class="table table-bordered table-hover align-middle" id="tableSPPD">
<thead>
<tr>
    <th>No</th>
    <th>ID Register</th>
    <th>No SPPD</th>
    <th>Tanggal</th>
    <th>Kota</th>
    <th>Keperluan</th>
    <th>PPTK</th>
    <th class="text-center">Aksi</th>
</tr>
</thead>
<tbody>

<?php 
$no = 1;
while($row = mysqli_fetch_assoc($query)) :
?>
<tr>
<td><?= $no++; ?></td>
<td><code><?= $row['id_sppd']; ?></code></td>
<td><?= htmlspecialchars($row['no_sppd']); ?></td>
<td><?= date('d/m/Y', strtotime($row['tgl_sppd'])); ?></td>
<td><?= htmlspecialchars($row['kota_tujuan']); ?></td>
<td>
<small>
<?= (strlen($row['keperluan']) > 50) 
? substr($row['keperluan'],0,50).'...' 
: htmlspecialchars($row['keperluan']); ?>
</small>
</td>
<td><?= htmlspecialchars($row['nama_pptk'] ?? '-'); ?></td>
<td class="text-center">
<div class="btn-group">
<a href="result.php?id=<?= $row['id_sppd']; ?>" 
class="btn btn-info btn-action text-white">
<i class="fas fa-eye"></i>
</a>

<a href="cetak_pdf.php?id=<?= $row['id_sppd']; ?>" 
target="_blank" 
class="btn btn-danger btn-action">
<i class="fas fa-file-pdf"></i>
</a>

<a href="hapus_sppd.php?id=<?= $row['id_sppd']; ?>" 
onclick="return confirm('Yakin hapus data ini?')" 
class="btn btn-outline-danger btn-action">
<i class="fas fa-trash"></i>
</a>
</div>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>
</div>
</div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tableSPPD').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
        }
    });
});
</script>

</body>
</html>