<?php
include 'check_session.php';
include 'koneksi.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Query pencarian
$query = "SELECT * FROM pegawai 
          WHERE nama LIKE '%$search%' 
             OR nip LIKE '%$search%'
             OR INSTANSI LIKE '%$search%'
          ORDER BY id_peg ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pegawai - SIJADI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .table-container { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .table thead { background-color: #0d6efd; color: white; }
        .btn-action { padding: 2px 8px; font-size: 0.85rem; }
        .sticky-header { position: sticky; top: 0; z-index: 1000; background: white; padding: 15px 0; border-bottom: 2px solid #dee2e6; }
    </style>
</head>
<body>

<div class="container-fluid px-4 mt-4">
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-0"><i class="fas fa-users me-2"></i>Daftar Pegawai</h2>
                <p class="text-muted">Manajemen data personil Bapperida Klaten</p>
                <small class="text-secondary">User: <strong><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></strong></small>
            </div>
            <div class="d-flex gap-2">
                <a href="index.php" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Form
                </a>
                <a href="search.php" class="btn btn-info shadow-sm">
                    <i class="fas fa-search me-1"></i> Cari Data
                </a>
                <a href="tambah_pegawai.php" class="btn btn-success shadow-sm">
                    <i class="fas fa-user-plus me-1"></i> Tambah Pegawai
                </a>
                <a href="logout.php" class="btn btn-danger shadow-sm">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <form method="GET" class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama / NIP / Instansi..." 
                           value="<?= htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <?php if($search != ''): ?>
                        <a href="list_pegawai.php" class="btn btn-outline-secondary">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="table-responsive" style="max-height: 600px;">
            <table class="table table-hover table-bordered align-middle">
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nama / NIP</th>
                        <th>Jabatan / Status</th>
                        <th>Pangkat/Gol</th>
                        <th>Rekening</th>
                        <th>Kontak & Alamat</th>
                        <th>Instansi</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){ 
                ?>
                    <tr>
                        <td class="text-center fw-bold"><?= $row['id_peg']; ?></td>
                        <td>
                            <div class="fw-bold"><?= $row['nama']; ?></div>
                            <small class="text-muted">NIP: <?= $row['nip']; ?></small>
                        </td>
                        <td>
                            <div><?= $row['uraijab']; ?></div>
                            <span class="badge bg-info text-dark" style="font-size: 10px;"><?= $row['stsjab']; ?></span>
                        </td>
                        <td class="text-center">
                            <?= $row['pangkat']; ?><br>
                            <span class="badge bg-secondary"><?= $row['gol']; ?></span>
                        </td>
                        <td>
                            <small>
                                <strong><?= $row['bankrek']; ?></strong><br>
                                <?= $row['no_rek']; ?>
                            </small>
                        </td>
                        <td>
                            <small>
                                <i class="fas fa-phone text-success me-1"></i> <?= $row['no_hp']; ?><br>
                                <i class="fas fa-envelope text-primary me-1"></i> <?= $row['email']; ?><br>
                                <i class="fas fa-map-marker-alt text-danger me-1"></i> <?= $row['alamat']; ?>
                            </small>
                        </td>
                        <td><?= $row['INSTANSI']; ?></td>
                        <td class="text-center">
                            <div class="d-grid gap-1">
                                <a href="edit_pegawai.php?id_peg=<?= $row['id_peg']; ?>" class="btn btn-warning btn-action">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="hapus_pegawai.php?id_peg=<?= $row['id_peg']; ?>" 
                                   class="btn btn-danger btn-action"
                                   onclick="return confirm('Yakin ingin menghapus data <?= $row['nama']; ?>?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } 
                } else { ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                            Data pegawai tidak ditemukan
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>