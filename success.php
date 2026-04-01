<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}

// Ambil info dari GET parameter atau Session
$no_sppd = isset($_GET['no_sppd']) ? htmlspecialchars($_GET['no_sppd']) : (isset($_SESSION['last_sppd_no']) ? $_SESSION['last_sppd_no'] : '');
$id_sppd = isset($_GET['id_sppd']) ? intval($_GET['id_sppd']) : (isset($_SESSION['last_sppd_id']) ? intval($_SESSION['last_sppd_id']) : 0);
$registration_id = isset($_GET['registration_id']) ? htmlspecialchars($_GET['registration_id']) : (isset($_SESSION['last_registration_id']) ? $_SESSION['last_registration_id'] : '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhasil - SIJADI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .success-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 600px;
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
            animation: bounceIn 0.6s ease-out;
        }
        @keyframes bounceIn {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        h2 {
            color: #28a745;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .info-box {
            background: #f0f8ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: left;
        }
        .info-box p {
            margin: 10px 0;
            font-size: 14px;
        }
        .info-label {
            font-weight: 600;
            color: #667eea;
            display: inline-block;
            width: 160px;
        }
        .info-value {
            color: #333;
            word-break: break-all;
        }
        .code-box {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            margin-top: 10px;
        }
        .btn-container {
            margin-top: 30px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn-custom {
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary-custom {
            background: #f0f0f0;
            color: #333;
        }
        .btn-secondary-custom:hover {
            background: #e0e0e0;
        }
    </style>
</head>
<body>
<div class="success-container">
    <div class="success-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    
    <h2>Data SPPD Berhasil Disimpan!</h2>
    <p class="text-muted mb-4">Perjalanan dinas Anda telah tercatat dalam sistem</p>
    
    <div class="info-box">
        <p>
            <span class="info-label"><i class="fas fa-file-alt me-2"></i>Nomor SPPD:</span>
            <span class="info-value"><?php echo $no_sppd ?: 'N/A'; ?></span>
        </p>
        <p>
            <span class="info-label"><i class="fas fa-database me-2"></i>ID Database:</span>
            <span class="info-value"><?php echo $id_sppd ?: 'N/A'; ?></span>
        </p>
        <p>
            <span class="info-label"><i class="fas fa-id-card me-2"></i>ID Registrasi:</span>
            <span class="info-value">
                <div class="code-box"><?php echo $registration_id ?: $_SESSION['registration_id']; ?></div>
            </span>
        </p>
        <p style="margin-top: 15px; font-size: 13px; color: #666;">
            <i class="fas fa-info-circle me-1"></i>
            Simpan ID ini untuk referensi pencarian data Anda
        </p>
    </div>
    
    <div class="btn-container">
        <a href="index.php" class="btn btn-custom btn-primary-custom">
            <i class="fas fa-plus me-1"></i>Buat SPPD Baru
        </a>
        <a href="search.php" class="btn btn-custom btn-secondary-custom">
            <i class="fas fa-search me-1"></i>Cari Data
        </a>
    </div>
    
    <hr style="margin: 30px 0;">
    
    <p style="font-size: 13px; color: #999;">
        <i class="fas fa-lightbulb me-1"></i>
        PDF telah di-generate dan bisa diunduh dari browser Anda
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
