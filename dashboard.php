<?php
include 'check_session.php';
include 'koneksi.php';

// Ambil statistik untuk user yang login
$id_user = $_SESSION['id_user'];

// Total SPPD yang dibuat oleh user ini
$query_total = "SELECT COUNT(*) as total FROM sppd WHERE id_user='$id_user'";
$result_total = mysqli_query($conn, $query_total);
$data_total = mysqli_fetch_assoc($result_total);
$total_sppd = $data_total['total'];

// Total personel dalam SPPD mereka
$query_personel = "SELECT COUNT(*) as total FROM sppd_personel sp 
                   JOIN sppd s ON sp.id_sppd = s.id_sppd 
                   WHERE s.id_user='$id_user'";
$result_personel = mysqli_query($conn, $query_personel);
$data_personel = mysqli_fetch_assoc($result_personel);
$total_personel = $data_personel['total'];

// SPPD terbaru
$query_recent = "SELECT s.*, t.nama_type FROM sppd s
                 LEFT JOIN type_dinas t ON s.id_type = t.id_type
                 WHERE s.id_user='$id_user'
                 ORDER BY s.created_at DESC
                 LIMIT 5";
$result_recent = mysqli_query($conn, $query_recent);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIJADI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-top: 20px;
            padding-bottom: 40px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .stat-label {
            font-size: 16px;
            color: #666;
            font-weight: 500;
        }
        .data-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .data-title {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9ff;
        }
        .badge-status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
        }
        .badge-success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold"><i class="fas fa-file-alt me-2"></i>SIJADI Dashboard</span>
        <div class="d-flex gap-2 align-items-center">
            <div class="text-white small me-3">
                <i class="fas fa-user-circle me-1"></i>
                <strong><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></strong>
            </div>
            <a href="index.php" class="btn btn-outline-light btn-sm fw-bold">
                <i class="fas fa-home me-1"></i> Home
            </a>
            <a href="search.php" class="btn btn-outline-light btn-sm fw-bold">
                <i class="fas fa-search me-1"></i> Cari
            </a>
            <a href="logout.php" class="btn btn-outline-danger btn-sm fw-bold">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="text-white mb-4"><i class="fas fa-chart-line me-2"></i>Dashboard</h2>
    
    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-icon text-primary">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="stat-number"><?php echo $total_sppd; ?></div>
                <div class="stat-label">Total SPPD Dibuat</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-icon text-success">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo $total_personel; ?></div>
                <div class="stat-label">Total Personel</div>
            </div>
        </div>
    </div>
    
    <!-- Info User -->
    <div class="data-card">
        <div class="data-title">
            <i class="fas fa-user"></i>Informasi Pengguna
        </div>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p><strong>ID Registrasi:</strong> <code><?php echo htmlspecialchars($_SESSION['registration_id']); ?></code></p>
            </div>
        </div>
    </div>
    
    <!-- SPPD Terbaru -->
    <div class="data-card">
        <div class="data-title">
            <i class="fas fa-history"></i>SPPD Terbaru
        </div>
        
        <?php if (mysqli_num_rows($result_recent) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No SPPD</th>
                            <th>Tgl SPPD</th>
                            <th>Jenis Dinas</th>
                            <th>Kota Tujuan</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_recent)): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['no_sppd']); ?></strong></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tgl_sppd'])); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_type'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($row['kota_tujuan']); ?></td>
                                <td><small><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></small></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i>Belum ada SPPD yang dibuat
            </div>
        <?php endif; ?>
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
