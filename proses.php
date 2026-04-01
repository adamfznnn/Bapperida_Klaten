<?php
ob_start();
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}

// Validasi Input
if (!isset($_POST['id_peg']) || empty($_POST['id_peg'][0])) {
    die("Error: Pilih minimal satu pegawai!");
}

// VALIDASI OVERLAPPING DINAS
$validation_errors = [];
for ($i = 0; $i < count($_POST['id_peg']); $i++) {
    $id_peg = mysqli_real_escape_string($conn, $_POST['id_peg'][$i]);
    $tgl_pergi = mysqli_real_escape_string($conn, $_POST['tgl_pergi'][$i]);
    $tgl_pulang = mysqli_real_escape_string($conn, $_POST['tgl_pulang'][$i]);
    
    // Cek overlapping dengan dinas sebelumnya
    $query_check = "SELECT s.no_sppd, s.id_surat, sp.tgl_pergi, sp.tgl_pulang, s.kota_tujuan, p.nama
                    FROM sppd_personel sp
                    JOIN sppd s ON sp.id_sppd = s.id_sppd
                    JOIN pegawai p ON sp.id_peg = p.id_peg
                    WHERE sp.id_peg = '$id_peg'
                    AND '$tgl_pergi' <= sp.tgl_pulang
                    AND '$tgl_pulang' >= sp.tgl_pergi";
    
    $result_check = mysqli_query($conn, $query_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        $conflict = mysqli_fetch_assoc($result_check);
        $validation_errors[] = "Pegawai <strong>" . htmlspecialchars($conflict['nama']) . "</strong> 
                                sudah memiliki jadwal dinas dari " . 
                                date('d/m/Y', strtotime($conflict['tgl_pergi'])) . " sampai " . 
                                date('d/m/Y', strtotime($conflict['tgl_pulang'])) . 
                                " (SPPD: {$conflict['id_surat']}, Tujuan: {$conflict['kota_tujuan']})";
    }
}

// Jika ada error validasi, tampilkan dan hentikan proses
if (count($validation_errors) > 0) {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error Validasi SPPD</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
                        <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Validasi Gagal!</h4>
                        <hr>
                        <p class="mb-3"><strong>Ada konflik jadwal dinas yang tidak bisa diproses:</strong></p>
                        <ul class="mb-0">
                            <?php foreach ($validation_errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="alert alert-info">
                        <p class="mb-0"><strong>Solusi:</strong></p>
                        <ul class="mb-0 mt-2">
                            <li>Pilih tanggal dinas yang tidak overlapping dengan jadwal sebelumnya</li>
                            <li>Atau gunakan pegawai lain untuk tanggal tersebut</li>
                            <li>Buka kembali form SPPD dan coba inputan yang berbeda</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="javascript:history.back()" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali & Edit
                        </a>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-file-alt me-2"></i>Form SPPD Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    ob_end_flush();
    exit;
}

$id_user     = $_SESSION['id_user'];
$no_sppd     = mysqli_real_escape_string($conn, $_POST['no_sppd']);
$tgl_sppd    = mysqli_real_escape_string($conn, $_POST['tgl_sppd']);
$type_dinas  = mysqli_real_escape_string($conn, $_POST['id_type']);
$transport   = mysqli_real_escape_string($conn, $_POST['transportasi']);
$keperluan   = mysqli_real_escape_string($conn, $_POST['keperluan']);
$kota_tujuan = mysqli_real_escape_string($conn, $_POST['kota_tujuan']);
$id_pptk     = mysqli_real_escape_string($conn, $_POST['id_pptk']);

$id_peg_array              = $_POST['id_peg'];
$tgl_pergi_array           = $_POST['tgl_pergi'];
$tgl_pulang_array          = $_POST['tgl_pulang'];
$nom_harian_array          = $_POST['nom_harian'] ?? [];
$nom_rep_array             = $_POST['nom_rep'] ?? [];
$biaya_tiket_array         = $_POST['biaya_tiket'] ?? [];
$biaya_penginapan_array    = $_POST['biaya_penginapan'] ?? [];
$biaya_transport_lokal_array = $_POST['biaya_transport_lokal'] ?? [];
$biaya_tol_array           = $_POST['biaya_tol'] ?? [];
$biaya_kontribusi_array    = $_POST['biaya_kontribusi'] ?? [];

// Generate Nomor 4 Digit
$q = mysqli_query($conn, "SELECT MAX(nomor_surat) AS max_no FROM sppd");
$d = mysqli_fetch_assoc($q);
$nomor_surat = ($d['max_no'] ?? 0) + 1;
$nomor_urut_4 = str_pad($nomor_surat, 4, '0', STR_PAD_LEFT);
$tahun = date('Y', strtotime($tgl_sppd));

$jenis_surat = $_POST['type_surat'] ?? 'tte';
$akhiran_manual = "";

if ($jenis_surat == "manual") {
    $akhiran_manual = "/M";
}

$id_reg = "$nomor_urut_4-[B/000.1.2.3/$no_sppd/$tahun/31]$akhiran_manual";

// Simpan Header SPPD
$query_sppd = "INSERT INTO sppd (nomor_surat, id_surat, no_sppd, tgl_sppd, id_type, transportasi, keperluan, kota_tujuan, id_pptk, id_user, tahun_surat, created_at) 
               VALUES ('$nomor_surat', '$id_reg', '$no_sppd', '$tgl_sppd', '$type_dinas', '$transport', '$keperluan', '$kota_tujuan', '$id_pptk', '$id_user', '$tahun', NOW())";

if (mysqli_query($conn, $query_sppd)) {
    $id_sppd = mysqli_insert_id($conn);

    // Simpan Personel (Looping)
    for ($i = 0; $i < count($id_peg_array); $i++) {
        $id_peg = mysqli_real_escape_string($conn, $id_peg_array[$i]);
        $tgl_p = mysqli_real_escape_string($conn, $tgl_pergi_array[$i]);
        $tgl_k = mysqli_real_escape_string($conn, $tgl_pulang_array[$i]);
        $n_har = floatval($nom_harian_array[$i] ?? 0);
        $n_rep = floatval($nom_rep_array[$i] ?? 0);
        $b_tiket = floatval($biaya_tiket_array[$i] ?? 0);
        $b_penginapan = floatval($biaya_penginapan_array[$i] ?? 0);
        $b_transport = floatval($biaya_transport_lokal_array[$i] ?? 0);
        $b_tol = floatval($biaya_tol_array[$i] ?? 0);
        $b_kontribusi = floatval($biaya_kontribusi_array[$i] ?? 0);

        mysqli_query($conn, "INSERT INTO sppd_personel (id_sppd, id_peg, tgl_pergi, tgl_pulang, nom_harian, nom_rep, biaya_tiket, biaya_penginapan, biaya_transport_lokal, biaya_tol, biaya_kontribusi, created_at) 
                             VALUES ('$id_sppd', '$id_peg', '$tgl_p', '$tgl_k', '$n_har', '$n_rep', '$b_tiket', '$b_penginapan', '$b_transport', '$b_tol', '$b_kontribusi', NOW())");
    }

    // REDIRECT KE PREVIEW
    header("Location: result.php?id=$id_sppd");
} else {
    echo "Gagal menyimpan data: " . mysqli_error($conn);
}
ob_end_flush();
?>