<?php
include 'koneksi.php';

// Query untuk mengambil semua data SPPD
// Kita gabungkan dengan tabel pegawai untuk mendapatkan nama PPTK
$sql = "SELECT s.*, p.nama AS nama_pptk 
        FROM sppd s 
        LEFT JOIN pegawai p ON s.id_pptk = p.id_peg 
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
        body { background-color: #f4f7f6; }
        .card { border-radius: 15px; border: none; }
        .table thead { background-color: #4e73df; color: white; }
        .btn-action { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
    </style>
</head>
<body>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Riwayat Perjalanan Dinas (SPPD)</h1>
                <a href="index.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah SPPD Baru
                </a>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tableSPPD" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Register</th>
                                    <th>No. SPPD</th>
                                    <th>Tanggal</th>
                                    <th>Kota Tujuan</th>
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
                                    <td><code><?= $row['id_surat']; ?></code></td>
                                    <td><?= $row['no_sppd']; ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['tgl_sppd'])); ?></td>
                                    <td><?= $row['kota_tujuan']; ?></td>
                                    <td>
                                        <small><?= (strlen($row['keperluan']) > 50) ? substr($row['keperluan'], 0, 50) . '...' : $row['keperluan']; ?></small>
                                    </td>
                                    <td><?= $row['nama_pptk']; ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="result.php?id=<?= $row['id_sppd']; ?>" class="btn btn-info btn-action text-white" title="Lihat Ringkasan">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="cetak_pdf.php?id=<?= $row['id_sppd']; ?>" target="_blank" class="btn btn-danger btn-action" title="Cetak PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <a href="hapus_sppd.php?id=<?= $row['id_sppd']; ?>" class="btn btn-outline-danger btn-action" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
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
            },
            "order": [[0, "desc"]] // Urutkan dari yang terbaru
        });
    });
</script>

</body>
</html>