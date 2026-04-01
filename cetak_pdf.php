<?php
include 'koneksi.php';
require('fpdf/fpdf.php');

$id_sppd = mysqli_real_escape_string($conn, $_GET['id']);

// Fungsi Terbilang
function terbilang($nilai) {
    $nilai = abs($nilai);
    $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
    if ($nilai < 12) return " ".$huruf[$nilai];
    if ($nilai < 20) return terbilang($nilai - 10)." Belas";
    if ($nilai < 100) return terbilang(floor($nilai / 10))." Puluh".terbilang($nilai % 10);
    if ($nilai < 200) return " Seratus".terbilang($nilai - 100);
    if ($nilai < 1000) return terbilang(floor($nilai / 100))." Ratus".terbilang($nilai % 100);
    if ($nilai < 2000) return " Seribu".terbilang($nilai - 1000);
    if ($nilai < 1000000) return terbilang(floor($nilai / 1000))." Ribu".terbilang($nilai % 1000);
    if ($nilai < 1000000000) return terbilang(floor($nilai / 1000000))." Juta".terbilang($nilai % 1000000);
}

// Data Utama
$d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT s.*, p.nama, p.nip FROM sppd s JOIN pegawai p ON s.id_pptk = p.id_peg WHERE s.id_sppd = '$id_sppd'"));

$pdf = new FPDF('L','mm','A4');  // Landscape untuk tabel lebih lebar
$pdf->AddPage();
$pdf->SetMargins(5,10,5);  // Margin seimbang untuk centering tabel

// KOP
$pdf->Image('img/logo.png',5,10,16);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6,'PEMERINTAH KABUPATEN KLATEN',0,1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6,'BADAN PERENCANAAN PEMBANGUNAN, RISET DAN INOVASI DAERAH',0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,5,'Jln. Pemuda Nomor 294 (Gedung Pemda II), Klaten, Jawa Tengah 57424',0,1,'C');
$pdf->Cell(0,5,'Telepon 0272-321046 Psw 314,318 Faksimile 0272-328730',0,1,'C');
$pdf->Cell(0,5,'Laman https://bapperida.klaten.go.id/ , email : bapperida@klaten.go.id',0,1,'C');
$pdf->Ln(2);

// Garis pembatas
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.5);
$pdf->Line(5,$pdf->GetY(),292,$pdf->GetY());
$pdf->Ln(2);

$pdf->SetFont('Arial','BU',12);
$pdf->Cell(0,6,'DAFTAR PENERIMAAN PENGELUARAN PERJALANAN DINAS',0,1,'C');
$pdf->Ln(3);

// Data Header Info - Layout simple satu kolom per baris
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,5,'TYPE DINAS',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,': '.$d['id_type'],0,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'JENIS TRANSPORTASI',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,5,': '.$d['transportasi'],0,1);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,5,'NO. SPPD',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,': '.$d['no_sppd'],0,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'TANGGAL BERANGKAT',0,0);
$pdf->SetFont('Arial','',10);
$first_tgl_pergi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tgl_pergi FROM sppd_personel WHERE id_sppd = '$id_sppd' ORDER BY tgl_pergi ASC LIMIT 1"));
$pdf->Cell(0,5,': '.date('d M Y', strtotime($first_tgl_pergi['tgl_pergi'])),0,1);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,5,'TANGGAL SPPD',0,0);
$pdf->SetFont('Arial','',10);
$tanggal_indo = date('d M Y', strtotime($d['tgl_sppd']));
$pdf->Cell(40,5,': '.$tanggal_indo,0,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'TANGGAL KEMBALI',0,0);
$pdf->SetFont('Arial','',10);
$last_tgl_pulang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tgl_pulang FROM sppd_personel WHERE id_sppd = '$id_sppd' ORDER BY tgl_pulang DESC LIMIT 1"));
$pdf->Cell(0,5,': '.date('d M Y', strtotime($last_tgl_pulang['tgl_pulang'])),0,1);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,5,'KEPERLUAN DINAS',0,0);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0,5,': '.$d['keperluan']);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,5,'TUJUAN',0,0);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0,5,': '.$d['kota_tujuan']);

$pdf->Ln(2);

// Garis pembatas di bawah
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.5);
$pdf->Line(5,$pdf->GetY(),292,$pdf->GetY());
$pdf->Ln(2);

// Header Tabel dengan semua kolom biaya
$pdf->SetFont('Arial','B',10);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.3);
$pdf->SetFillColor(255,255,255);
$pdf->SetX(5);

$pdf->Cell(5,9,'No',1,0,'C',true);
$pdf->Cell(40,9,'Nama',1,0,'C',true);
$pdf->Cell(32,9,'Jabatan',1,0,'C',true);
$pdf->Cell(8,9,'Hari',1,0,'C',true);
$pdf->Cell(21,9,'No. Rek',1,0,'C',true);
$pdf->Cell(22,9,'Harian',1,0,'C',true);
$pdf->Cell(22,9,'Rep',1,0,'C',true);
$pdf->Cell(20,9,'Tiket',1,0,'C',true);
$pdf->Cell(20,9,'Penginapan',1,0,'C',true);
$pdf->Cell(20,9,'Transport',1,0,'C',true);
$pdf->Cell(18,9,'Tol',1,0,'C',true);
$pdf->Cell(19,9,'Kontribusi',1,0,'C',true);
$pdf->Cell(20,9,'Total',1,0,'C',true);
$pdf->Cell(20,9,'Bank',1,1,'C',true);

// Isi Tabel dengan text wrapping
$pdf->SetFont('Arial','',9);
$pdf->SetFillColor(255,255,255);
$no=1; $total_harian = 0; $total_rep = 0; $total_tiket = 0; $total_penginapan = 0; $total_transport = 0; $total_tol = 0; $total_kontribusi = 0; $total_semua = 0;
$q_p = mysqli_query($conn, "SELECT sp.*, p.nama, p.uraijab, p.no_rek, p.bankrek FROM sppd_personel sp JOIN pegawai p ON sp.id_peg = p.id_peg WHERE sp.id_sppd = '$id_sppd'");
while($p = mysqli_fetch_assoc($q_p)){
    // Hitung subtotal ALL costs
    $sub_total = $p['nom_harian'] + $p['nom_rep'] + $p['biaya_tiket'] + $p['biaya_penginapan'] + $p['biaya_transport_lokal'] + $p['biaya_tol'] + $p['biaya_kontribusi'];
    
    // Hitung selisih hari
    $tgl1 = new DateTime($p['tgl_pergi']);
    $tgl2 = new DateTime($p['tgl_pulang']);
    $jarak = $tgl1->diff($tgl2);
    $durasi = $jarak->days + 1;
    
    // Simpan posisi awal
    $y_awal = $pdf->GetY();
    $x_awal = 5;
    
    // STEP 1: Hitung tinggi dari wrapped content (invisible draw)
    $pdf->SetDrawColor(255, 255, 255); // invisible
    
    $nama_text = $p['nama'];
    $jabatan_text = $p['uraijab'];
    
    $pdf->SetXY($x_awal + 5, $y_awal);
    if(strlen($nama_text) > 20) {
        $nama_lines = explode("\n", wordwrap($nama_text, 15, "\n"));
        $pdf->MultiCell(40, 4, implode("\n", $nama_lines), 0, 'L');
    } else {
        $pdf->Cell(40, 12, '', 0, 0, 'L');
    }
    $y_nama_temp = $pdf->GetY();
    
    $pdf->SetXY($x_awal + 45, $y_awal);
    if(strlen($jabatan_text) > 15) {
        $jabatan_lines = explode("\n", wordwrap($jabatan_text, 12, "\n"));
        $pdf->MultiCell(32, 4, implode("\n", $jabatan_lines), 0, 'L');
    } else {
        $pdf->Cell(32, 12, '', 0, 0, 'L');
    }
    $y_jabatan_temp = $pdf->GetY();
    
    $tinggi = max($y_nama_temp, $y_jabatan_temp) - $y_awal;
    if($tinggi < 12) $tinggi = 12;
    
    // STEP 2: Reset Y dan draw semua kolom dengan border
    $pdf->SetY($y_awal);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.3);
    
    // Kolom NO
    $pdf->SetXY($x_awal, $y_awal);
    $pdf->Cell(5, $tinggi, $no++, 1, 0, 'C');
    
    // Kolom NAMA - dengan border (top, left, right only)
    $pdf->SetXY($x_awal + 5, $y_awal);
    $pdf->SetFont('Arial','',9);
    $nama_text = $p['nama'];
    if(strlen($nama_text) > 20) {
        $nama_lines = explode("\n", wordwrap($nama_text, 15, "\n"));
        $pdf->MultiCell(40, 4, implode("\n", $nama_lines), 0, 'L');
    } else {
        $pdf->Cell(40, $tinggi, $nama_text, 0, 0, 'L');
    }
    // Manual border untuk Nama (top, left, right - no bottom)
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.3);
    $pdf->Line($x_awal + 5, $y_awal, $x_awal + 45, $y_awal); // top
    $pdf->Line($x_awal + 5, $y_awal, $x_awal + 5, $y_awal + $tinggi); // left
    $pdf->Line($x_awal + 45, $y_awal, $x_awal + 45, $y_awal + $tinggi); // right
    
    // Kolom JABATAN - dengan border (top, left, right only)
    $pdf->SetXY($x_awal + 45, $y_awal);
    $jabatan_text = $p['uraijab'];
    if(strlen($jabatan_text) > 15) {
        $jabatan_lines = explode("\n", wordwrap($jabatan_text, 12, "\n"));
        $pdf->MultiCell(32, 4, implode("\n", $jabatan_lines), 0, 'L');
    } else {
        $pdf->Cell(32, $tinggi, $jabatan_text, 0, 0, 'L');
    }
    // Manual border untuk Jabatan (top, left, right - no bottom)
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.3);
    $pdf->Line($x_awal + 45, $y_awal, $x_awal + 77, $y_awal); // top
    $pdf->Line($x_awal + 45, $y_awal, $x_awal + 45, $y_awal + $tinggi); // left
    $pdf->Line($x_awal + 77, $y_awal, $x_awal + 77, $y_awal + $tinggi); // right
    
    // Kolom Hari
    $pdf->SetXY($x_awal + 77, $y_awal);
    $pdf->Cell(8, $tinggi, $durasi, 1, 0, 'C');
    
    // Kolom No. Rek
    $pdf->SetXY($x_awal + 85, $y_awal);
    $pdf->Cell(21, $tinggi, substr($p['no_rek'], 0, 12), 1, 0, 'C');
    
    // Kolom Harian
    $pdf->SetXY($x_awal + 106, $y_awal);
    $pdf->Cell(22, $tinggi, number_format($p['nom_harian'],0,'',''), 1, 0, 'R');
    
    // Kolom Rep
    $pdf->SetXY($x_awal + 128, $y_awal);
    $pdf->Cell(22, $tinggi, number_format($p['nom_rep'],0,'',''), 1, 0, 'R');
    
    // Kolom Tiket
    $pdf->SetXY($x_awal + 150, $y_awal);
    $pdf->Cell(20, $tinggi, number_format($p['biaya_tiket'],0,'',''), 1, 0, 'R');
    
    // Kolom Penginapan
    $pdf->SetXY($x_awal + 170, $y_awal);
    $pdf->Cell(20, $tinggi, number_format($p['biaya_penginapan'],0,'',''), 1, 0, 'R');
    
    // Kolom Transport
    $pdf->SetXY($x_awal + 190, $y_awal);
    $pdf->Cell(20, $tinggi, number_format($p['biaya_transport_lokal'],0,'',''), 1, 0, 'R');
    
    // Kolom Tol
    $pdf->SetXY($x_awal + 210, $y_awal);
    $pdf->Cell(18, $tinggi, number_format($p['biaya_tol'],0,'',''), 1, 0, 'R');
    
    // Kolom Kontribusi
    $pdf->SetXY($x_awal + 228, $y_awal);
    $pdf->Cell(19, $tinggi, number_format($p['biaya_kontribusi'],0,'',''), 1, 0, 'R');
    
    // Kolom Total
    $pdf->SetXY($x_awal + 247, $y_awal);
    $pdf->Cell(20, $tinggi, number_format($sub_total,0,'',''), 1, 0, 'R');
    
    // Kolom Bank - dengan proper border (top, left, right, bottom)
    $pdf->SetXY($x_awal + 267, $y_awal);
    $pdf->SetFont('Arial','',8);
    $pdf->MultiCell(20, 4, substr($p['bankrek'], 0, 20), 0, 'C');
    // Manual border untuk Bank (top, left, right, bottom)
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.3);
    $pdf->Line($x_awal + 267, $y_awal, $x_awal + 287, $y_awal); // top
    $pdf->Line($x_awal + 267, $y_awal, $x_awal + 267, $y_awal + $tinggi); // left
    $pdf->Line($x_awal + 287, $y_awal, $x_awal + 287, $y_awal + $tinggi); // right
    $pdf->Line($x_awal + 267, $y_awal + $tinggi, $x_awal + 287, $y_awal + $tinggi); // bottom
    $pdf->SetFont('Arial','',9);
    
    // Pindah ke baris berikutnya
    $pdf->SetY($y_awal + $tinggi);
    
    // Akumulasi total
    $total_harian += $p['nom_harian'];
    $total_rep += $p['nom_rep'];
    $total_tiket += $p['biaya_tiket'];
    $total_penginapan += $p['biaya_penginapan'];
    $total_transport += $p['biaya_transport_lokal'];
    $total_tol += $p['biaya_tol'];
    $total_kontribusi += $p['biaya_kontribusi'];
    $total_semua += $sub_total;
}

// Baris Total
$pdf->SetFont('Arial','B',9);
$pdf->SetX(5);
$y_total = $pdf->GetY();
$x_awal = 5;
$tinggi = 12;

$pdf->SetXY($x_awal, $y_total);
$pdf->Cell(5, $tinggi, '', 1, 0, 'C');

$pdf->SetXY($x_awal + 5, $y_total);
$pdf->Cell(40, $tinggi, '', 1, 0, 'C');

$pdf->SetXY($x_awal + 45, $y_total);
$pdf->Cell(32, $tinggi, 'JUMLAH', 1, 0, 'C');

$pdf->SetXY($x_awal + 77, $y_total);
$pdf->Cell(8, $tinggi, '', 1, 0, 'C');

$pdf->SetXY($x_awal + 85, $y_total);
$pdf->Cell(21, $tinggi, '', 1, 0, 'C');

$pdf->SetXY($x_awal + 106, $y_total);
$pdf->Cell(22, $tinggi, number_format($total_harian,0,'',''), 1, 0, 'R');

$pdf->SetXY($x_awal + 128, $y_total);
$pdf->Cell(22, $tinggi, number_format($total_rep,0,'',''), 1, 0, 'R');

$pdf->SetXY($x_awal + 150, $y_total);
$pdf->Cell(20, $tinggi, number_format($total_tiket,0,'',''), 1, 0, 'R');

$pdf->SetXY($x_awal + 170, $y_total);
$pdf->Cell(20, $tinggi, number_format($total_penginapan,0,'',''), 1, 0, 'R');

$pdf->SetXY($x_awal + 190, $y_total);
$pdf->Cell(20, $tinggi, number_format($total_transport,0,'',''), 1, 0, 'R');

$pdf->SetXY($x_awal + 210, $y_total);
$pdf->Cell(18, $tinggi, number_format($total_tol,0,'',''), 1, 0, 'R');

$pdf->SetXY($x_awal + 228, $y_total);
$pdf->Cell(19, $tinggi, number_format($total_kontribusi,0,'',''), 1, 0, 'R');

$pdf->SetXY($x_awal + 247, $y_total);
$pdf->Cell(20, $tinggi, number_format($total_semua,0,'',''), 1, 0, 'R');

$pdf->SetXY($x_awal + 267, $y_total);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(0.3);
$pdf->Line($x_awal + 267, $y_total, $x_awal + 287, $y_total); // top
$pdf->Line($x_awal + 267, $y_total, $x_awal + 267, $y_total + $tinggi); // left
$pdf->Line($x_awal + 287, $y_total, $x_awal + 287, $y_total + $tinggi); // right
$pdf->Line($x_awal + 267, $y_total + $tinggi, $x_awal + 287, $y_total + $tinggi); // bottom

$pdf->Ln(12);

// ID Register
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,5,'ID-Reg : '.$d['id_surat'],0,1);
$pdf->Ln(2);

// Terbilang
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,5,'Terbilang : '.trim(terbilang($total_semua)).' Rupiah',0,1);

$pdf->Ln(8);

// TTD
$pdf->SetFont('Arial','',10);
$y_ttd = $pdf->GetY() + 5;
$x_ttd = 242;
$w_ttd = 50;

// Tanggal
$pdf->SetXY($x_ttd, $y_ttd);
$pdf->Cell($w_ttd, 5, 'Klaten, '.date('d F Y', strtotime($d['tgl_sppd'])), 0, 1, 'C');

// PPTK Label
$pdf->SetXY($x_ttd, $y_ttd + 8);
$pdf->SetFont('Arial','',10);
$pdf->Cell($w_ttd, 5, 'PPTK', 0, 1, 'C');

// Nama dengan spasi ke bawah
$pdf->SetXY($x_ttd, $y_ttd + 20);
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell($w_ttd, 3.5, $d['nama'], 0, 'C');

// Garis Tanda Tangan dibawah nama
$y_after_nama = $pdf->GetY();
$pdf->SetXY($x_ttd, $y_after_nama);
$pdf->SetLineWidth(0.5);
$pdf->SetDrawColor(0,0,0);
$pdf->Line($x_ttd, $y_after_nama, $x_ttd + $w_ttd, $y_after_nama);

// NIP
$pdf->SetXY($x_ttd, $y_after_nama + 2);
$pdf->SetFont('Arial','',9);
$pdf->Cell($w_ttd, 4, 'NIP. '.$d['nip'], 0, 1, 'C');

$pdf->Output('I','SPPD_'.$d['no_sppd'].'.pdf');
?>