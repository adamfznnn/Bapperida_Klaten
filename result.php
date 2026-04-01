<?php
include 'koneksi.php';

// Ambil ID dari URL
$id_sppd = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : ''; 

if (empty($id_sppd)) {
    die("ID SPPD tidak ditemukan.");
}

// 1. Query Data Utama SPPD & PPTK
$query_sppd = "SELECT s.*, p.nama AS nama_pptk, p.nip AS nip_pptk 
               FROM sppd s 
               LEFT JOIN pegawai p ON s.id_pptk = p.id_peg 
               WHERE s.id_sppd = '$id_sppd'";
$result_sppd = mysqli_query($conn, $query_sppd);
$data = mysqli_fetch_assoc($result_sppd);

if (!$data) {
    die("Data SPPD tidak ditemukan di database.");
}

// Fungsi bantu format tanggal Indonesia
function tgl_indo($tanggal){
    $bulan = array (1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Input SPPD - SIJADI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .success-animation { color: #28a745; font-size: 80px; }
        .info-label { font-weight: bold; color: #495057; width: 160px; display: inline-block; }
        .card { border-radius: 15px; }
        .table thead { background-color: #f8f9fa; }
        .card-body p { margin: 0.5rem 0; }
        @media print {
            .no-print { display: none !important; }
            .card { border: none !important; box-shadow: none !important; border-radius: 0 !important; }
            body { background-color: white !important; }
            .card-body { padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="text-center mb-4 no-print">
                <i class="fas fa-check-circle success-animation"></i>
                <h2 class="fw-bold mt-2">Data Berhasil Disimpan!</h2>
                <p class="text-muted">ID Register: <strong><?php echo $data['id_surat']; ?></strong> telah tercatat ke dalam sistem.</p>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center p-3">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Ringkasan Perjalanan Dinas</h5>
                    <span class="badge bg-light text-primary">Status: Terverifikasi</span>
                </div>
                <div class="card-body p-4">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2"><span class="info-label">Type Dinas</span>: <?php echo $data['id_type']; ?></p>
                            <p class="mb-2"><span class="info-label">Nomor SPPD</span>: <?php echo $data['no_sppd']; ?></p>
                            <p class="mb-2"><span class="info-label">ID Register</span>: <strong><?php echo $data['id_surat']; ?></strong></p>
                            <p class="mb-2"><span class="info-label">Tgl Surat</span>: <?php echo tgl_indo($data['tgl_sppd']); ?></p>
                            <p class="mb-2"><span class="info-label">Keperluan Dinas</span>: <?php echo $data['keperluan']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="info-label">Jenis Transportasi</span>: <?php echo $data['transportasi']; ?></p>
                            <p class="mb-2"><span class="info-label">Kota Tujuan</span>: <?php echo $data['kota_tujuan']; ?></p>
                            <?php
                            $first_tgl = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tgl_pergi FROM sppd_personel WHERE id_sppd = '$id_sppd' ORDER BY tgl_pergi ASC LIMIT 1"));
                            $last_tgl = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tgl_pulang FROM sppd_personel WHERE id_sppd = '$id_sppd' ORDER BY tgl_pulang DESC LIMIT 1"));
                            ?>
                            <p class="mb-2"><span class="info-label">Tanggal Berangkat</span>: <?php echo tgl_indo($first_tgl['tgl_pergi']); ?></p>
                            <p class="mb-2"><span class="info-label">Tanggal Kembali</span>: <?php echo tgl_indo($last_tgl['tgl_pulang']); ?></p>
                            <p class="mb-0 mt-2"><span class="info-label">PPTK Penanggung Jawab</span>: <strong><?php echo $data['nama_pptk']; ?></strong><br><small class="ms-1">NIP. <?php echo $data['nip_pptk']; ?></small></p>
                        </div>
                    </div>
                    <hr>

                    <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="fas fa-users me-2"></i>Daftar Personel & Rincian Biaya</h6>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Pegawai</th>
                                    <th>Durasi Perjalanan</th>
                                    <th class="text-end">Harian</th>
                                    <th class="text-end">Representatif</th>
                                    <th class="text-end">Tiket</th>
                                    <th class="text-end">Penginapan</th>
                                    <th class="text-end">Transport</th>
                                    <th class="text-end">Tol & Parkir</th>
                                    <th class="text-end">Kontribusi</th>
                                    <th class="text-end">Sub-Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_anggaran = 0;
                                // 2. Query Data Personel berdasarkan id_sppd
                                $query_pers = "SELECT sp.*, p.nama, p.nip 
                                               FROM sppd_personel sp 
                                               JOIN pegawai p ON sp.id_peg = p.id_peg 
                                               WHERE sp.id_sppd = '$id_sppd'";
                                $result_pers = mysqli_query($conn, $query_pers);
                                
                                while ($p = mysqli_fetch_assoc($result_pers)) {
                                    // Hitung subtotal dengan SEMUA biaya
                                    $sub_total = $p['nom_harian'] + $p['nom_rep'] + 
                                                 $p['biaya_tiket'] + $p['biaya_penginapan'] + 
                                                 $p['biaya_transport_lokal'] + $p['biaya_tol'] + 
                                                 $p['biaya_kontribusi'];
                                    $total_anggaran += $sub_total;

                                    // Hitung selisih hari
                                    $tgl1 = new DateTime($p['tgl_pergi']);
                                    $tgl2 = new DateTime($p['tgl_pulang']);
                                    $jarak = $tgl1->diff($tgl2);
                                    $durasi = $jarak->days + 1; // +1 karena hari berangkat dihitung
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?php echo $p['nama']; ?></div>
                                        <small class="text-muted">NIP. <?php echo $p['nip']; ?></small>
                                    </td>
                                    <td>
                                        <?php echo date('d M', strtotime($p['tgl_pergi'])); ?> - <?php echo date('d M', strtotime($p['tgl_pulang'])); ?> 
                                        <span class="badge bg-secondary ms-1"><?php echo $durasi; ?> Hari</span>
                                    </td>
                                    <td class="text-end">Rp <?php echo number_format($p['nom_harian'], 0, ',', '.'); ?></td>
                                    <td class="text-end">Rp <?php echo number_format($p['nom_rep'], 0, ',', '.'); ?></td>
                                    <td class="text-end">Rp <?php echo number_format($p['biaya_tiket'], 0, ',', '.'); ?></td>
                                    <td class="text-end">Rp <?php echo number_format($p['biaya_penginapan'], 0, ',', '.'); ?></td>
                                    <td class="text-end">Rp <?php echo number_format($p['biaya_transport_lokal'], 0, ',', '.'); ?></td>
                                    <td class="text-end">Rp <?php echo number_format($p['biaya_tol'], 0, ',', '.'); ?></td>
                                    <td class="text-end">Rp <?php echo number_format($p['biaya_kontribusi'], 0, ',', '.'); ?></td>
                                    <td class="text-end fw-bold text-primary">Rp <?php echo number_format($sub_total, 0, ',', '.'); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot class="table-primary">
                                <tr>
                                    <th colspan="9" class="text-center text-uppercase">Total Anggaran Dikeluarkan</th>
                                    <th class="text-end h5 fw-bold">Rp <?php echo number_format($total_anggaran, 0, ',', '.'); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-5 d-flex gap-2 justify-content-center no-print">
                        <a href="index.php" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-plus me-2"></i>Input Baru
                        </a>
                        <a href="cetak_pdf.php?id=<?php echo $id_sppd; ?>" target="_blank" class="btn btn-danger px-4">
                            <i class="fas fa-file-pdf me-2"></i>Cetak PDF Resmi
                        </a>
                        <button onclick="window.print()" class="btn btn-dark px-4">
                            <i class="fas fa-print me-2"></i>Cetak Ringkasan
                        </button>
                        <a href="search.php" class="btn btn-primary px-4">
                            <i class="fas fa-database me-2"></i>Lihat Semua Data
                        </a>
                    </div>

                </div>
            </div>

            <p class="text-center mt-4 text-muted small no-print">
                &copy; 2026 SIJADI - Sistem Informasi Perjalanan Dinas
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>