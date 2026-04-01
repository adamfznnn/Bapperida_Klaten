<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Update Sistem SPPD - SIJADI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-toolbox me-2"></i>Update Sistem Perhitungan Biaya SPPD</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i><strong>Update Berhasil Dilakukan!</strong>
                    </div>

                    <h5 class="mt-4 mb-3"><i class="fas fa-list me-2"></i>Perubahan yang Dilakukan:</h5>
                    <ol>
                        <li>
                            <strong>Database Update</strong>
                            <p>Menambahkan kolom-kolom berikut ke tabel <code>sppd_personel</code>:</p>
                            <ul>
                                <li><code>biaya_tiket</code></li>
                                <li><code>biaya_penginapan</code></li>
                                <li><code>biaya_transport_lokal</code></li>
                                <li><code>biaya_tol</code></li>
                                <li><code>biaya_kontribusi</code></li>
                            </ul>
                        </li>
                        <li>
                            <strong>File proses.php</strong>
                            <p>Update untuk menerima dan menyimpan semua data biaya dari form.</p>
                        </li>
                        <li>
                            <strong>File result.php</strong>
                            <p>Update untuk menampilkan semua kolom biaya dan menghitung subtotal dengan formula:</p>
                            <code>Sub-Total = Harian + Representatif + Tiket + Penginapan + Transport + Tol + Kontribusi</code>
                        </li>
                        <li>
                            <strong>File cetak_pdf.php</strong>
                            <p>Update untuk menampilkan semua biaya dalam laporan PDF.</p>
                        </li>
                    </ol>

                    <h5 class="mt-4 mb-3"><i class="fas fa-info-circle me-2"></i>Fitur Baru:</h5>
                    <div class="alert alert-info">
                        <ul class="mb-0">
                            <li>Subtotal biaya sekarang menghitung <strong>SEMUA biaya yang diinput</strong> (tidak hanya uang harian)</li>
                            <li>Mencakup biaya tiket, penginapan, transport lokal, tol/parkir, dan kontribusi diklat</li>
                            <li>Total anggaran akan menampilkan penjumlahan dari semua biaya semua pegawai</li>
                            <li>Laporan PDF juga menampilkan detail lengkap semua biaya</li>
                        </ul>
                    </div>

                    <h5 class="mt-4 mb-3"><i class="fas fa-cogs me-2"></i>Cara Penggunaan:</h5>
                    <div class="panel">
                        <p><strong>1. Input Data SPPD Seperti Biasa</strong></p>
                        <p>Masuk ke <a href="index.php" class="btn btn-sm btn-primary"><i class="fas fa-file-alt me-1"></i>Form Input SPPD</a></p>
                        
                        <p class="mt-3"><strong>2. Isi Semua Biaya di Form</strong></p>
                        <p>Pada bagian "Rincian Personel & Biaya", Anda sekarang dapat mengisi:</p>
                        <ul>
                            <li>Uang Harian (otomatis dari kota)</li>
                            <li>Uang Representatif (manual)</li>
                            <li>Biaya Tiket</li>
                            <li>Biaya Penginapan</li>
                            <li>Transport Lokal</li>
                            <li>Biaya Tol & Parkir</li>
                            <li>Kontribusi Diklat</li>
                        </ul>
                        
                        <p class="mt-3"><strong>3. Simpan Data</strong></p>
                        <p>Klik tombol "Simpan Seluruh Data SPPD"</p>
                        
                        <p class="mt-3"><strong>4. Lihat Hasil</strong></p>
                        <p>Hasil akan menampilkan tabel dengan:</p>
                        <ul>
                            <li>Semua detail biaya per pegawai</li>
                            <li>Sub-Total yang sudah termasuk semua biaya</li>
                            <li>Grand Total untuk seluruh anggaran</li>
                        </ul>
                    </div>

                    <h5 class="mt-4 mb-3"><i class="fas fa-bug me-2"></i>Catatan Penting:</h5>
                    <div class="alert alert-warning">
                        <ul class="mb-0">
                            <li>Jika Anda melihat error "Duplicate column name", itu berarti kolom sudah ada (normal)</li>
                            <li>Data lama akan otomatis memiliki nilai biaya = 0</li>
                            <li>Update ini tidak menghapus data apapun</li>
                            <li>Anda dapat terus mengedit dan menambah data seperti biasa</li>
                        </ul>
                    </div>

                    <div class="mt-5 text-center">
                        <a href="index.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-arrow-right me-2"></i>Kembali ke Form Input SPPD
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
