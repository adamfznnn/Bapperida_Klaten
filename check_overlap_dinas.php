<?php
include 'koneksi.php';

/**
 * API untuk mengecek apakah pegawai punya dinas yang overlapping
 * Dipanggil oleh AJAX dari form SPPD
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_peg']) && isset($_POST['tgl_pergi']) && isset($_POST['tgl_pulang'])) {
    
    $id_peg = mysqli_real_escape_string($conn, $_POST['id_peg']);
    $tgl_pergi = mysqli_real_escape_string($conn, $_POST['tgl_pergi']);
    $tgl_pulang = mysqli_real_escape_string($conn, $_POST['tgl_pulang']);
    
    // Validasi format tanggal
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_pergi) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_pulang)) {
        echo json_encode(['status' => 'error', 'message' => 'Format tanggal tidak valid']);
        exit;
    }
    
    // Cek apakah ada dinas yang overlapping dengan pegawai ini
    // Overlap terjadi jika:
    // tgl_pergi_baru <= tgl_pulang_lama AND tgl_pulang_baru >= tgl_pergi_lama
    
    $query = "SELECT sp.id_sppd, s.no_sppd, s.id_surat, sp.tgl_pergi, sp.tgl_pulang, s.kota_tujuan
              FROM sppd_personel sp
              JOIN sppd s ON sp.id_sppd = s.id_sppd
              WHERE sp.id_peg = '$id_peg'
              AND '$tgl_pergi' <= sp.tgl_pulang
              AND '$tgl_pulang' >= sp.tgl_pergi
              ORDER BY sp.tgl_pergi DESC";
    
    $result = mysqli_query($conn, $query);
    $overlaps = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $overlaps[] = [
            'no_sppd' => $row['no_sppd'],
            'id_surat' => $row['id_surat'],
            'tgl_pergi' => $row['tgl_pergi'],
            'tgl_pulang' => $row['tgl_pulang'],
            'kota_tujuan' => $row['kota_tujuan']
        ];
    }
    
    if (count($overlaps) > 0) {
        echo json_encode([
            'status' => 'conflict',
            'message' => 'Pegawai ini sudah memiliki jadwal dinas pada tanggal yang sama!',
            'overlaps' => $overlaps
        ]);
    } else {
        echo json_encode([
            'status' => 'ok',
            'message' => 'Tanggal dinas tidak ada konflik'
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
}
exit;
?>
