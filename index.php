<?php
include 'check_session.php';
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form SPPD Multi-Personel - SIJADI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .personel-item {
            transition: all 0.3s ease;
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header {
            font-weight: bold;
            background-color: #e9ecef !important;
        }

        .input-group-text {
            min-width: 250px;
        }

        .btn-custom-sm {
            padding: 6px;
            font-size: 12px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            text-align: center;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold"><i class="fas fa-file-alt me-2"></i>SIJADI</span>
            <div class="d-flex gap-2 align-items-center">
                <div class="text-white small me-3">
                    <i class="fas fa-user-circle me-1"></i>
                    <strong><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></strong>
                    <br>
                    <small>ID Reg: <?php echo htmlspecialchars($_SESSION['registration_id']); ?></small>
                </div>
                <a href="search.php" class="btn btn-outline-light btn-sm fw-bold"><i class="fas fa-search me-1"></i>
                    Cari Data</a>
                <a href="list_pegawai.php" class="btn btn-outline-light btn-sm fw-bold"><i class="fas fa-list me-1"></i>
                    List Pegawai</a>
                <a href="logout.php" class="btn btn-outline-danger btn-sm fw-bold"><i
                        class="fas fa-sign-out-alt me-1"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="alert-heading mb-2"><i class="fas fa-info-circle me-2"></i>Selamat Datang!</h5>
                    <p class="mb-0">Anda login sebagai
                        <strong><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></strong>
                    </p>
                    <p class="mb-0"><small>ID Registrasi:
                            <code><?php echo htmlspecialchars($_SESSION['registration_id']); ?></code></small></p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="dashboard.php" class="btn btn-info btn-sm me-2"><i
                            class="fas fa-chart-line me-1"></i>Dashboard</a>
                    <a href="search.php" class="btn btn-success btn-sm"><i class="fas fa-search me-1"></i>Cari SPPD</a>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center mb-4 text-primary fw-bold"><i class="fas fa-file-alt me-2"></i>FORM INPUT
                    PERJALANAN DINAS (SPPD)</h3>

                <form action="proses.php" method="POST" id="formSPPD">

                    <div class="section-title bg-primary text-white p-2 rounded mb-3">
                        <span><i class="fas fa-file-invoice me-2"></i>I. Informasi Administrasi</span>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Nomor SPPD</label>
                            <input type="text" name="no_sppd" class="form-control" placeholder="001/SPPD/2026" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tanggal SPPD</label>
                            <input type="date" name="tgl_sppd" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Type Surat</label>
                            <select name="type_surat" class="form-select">
                                <option value="tte">TTE (Digital)</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">PPTK (Pejabat Pelaksana Teknis Kegiatan)</label>
                            <select name="id_pptk" class="form-select" required>
                                <option value="">-- Pilih PPTK --</option>
                                <?php
                                $target_ids = "2, 17, 30, 37, 44";
                                $sql_pptk = "SELECT id_peg, nama, nip FROM pegawai WHERE id_peg IN ($target_ids) ORDER BY FIELD(id_peg, $target_ids)";
                                $res_pptk = mysqli_query($conn, $sql_pptk);
                                while ($row = mysqli_fetch_assoc($res_pptk)) {
                                    echo "<option value='" . $row['id_peg'] . "'>" . $row['nama'] . " (NIP. " . $row['nip'] . ")</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">No. Ref A2</label>
                            <input type="text" name="no_ref_a2" class="form-control" placeholder="Input No Ref">
                        </div>
                    </div>

                    <div class="section-title bg-primary text-white p-2 rounded mb-3">
                        <span><i class="fas fa-map-marked-alt me-2"></i>II. Detail Perjalanan Dinas</span>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Type Dinas</label>
                            <select name="id_type" id="type_dinas" class="form-select" required>
                                <option value="">-- Pilih Tipe Dinas --</option>
                                <?php
                                $res = mysqli_query($conn, "SELECT * FROM type_dinas");
                                while ($row = mysqli_fetch_assoc($res)) {
                                    // Value adalah ID (untuk database), Teks adalah Nama (untuk logika JS)
                                    echo "<option value='" . $row['id_type'] . "'>" . $row['nama_type'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Lama Dinas (Kategori)</label>
                            <select name="lama_dinas" id="lama_dinas" class="form-select" required>
                                <option value="">-- Pilih Tipe Dinas Dahulu --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Transportasi Utama</label>
                            <select name="transportasi" id="transportasi" class="form-select">
                                <option value="Kendaraan Dinas">Kendaraan Dinas</option>
                                <option value="Kendaraan Umum">Kendaraan Umum</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Provinsi Tujuan</label>
                            <select name="provinsi_tujuan" id="provinsi" class="form-select" required>
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Kota Tujuan</label>
                            <select name="kota_tujuan" id="kota" class="form-select" required>
                                <option value="">-- Pilih Provinsi Dahulu --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Detail Lokasi</label>
                            <input type="text" name="detail_tujuan" class="form-control"
                                placeholder="Nama Gedung/Kantor">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Keperluan</label>
                            <input type="text" name="keperluan" class="form-control" placeholder="Maksud Perjalanan">
                        </div>
                    </div>

                    <div class="section-title bg-primary text-white p-2 rounded mb-3">
                        <span><i class="fas fa-users-cog me-2"></i>III. Rincian Personel & Biaya</span>
                        <button type="button" id="add-personel" class="btn btn-success btn-sm fw-bold shadow-sm">
                            <i class="fas fa-plus-circle"></i> Tambah Personel
                        </button>
                    </div>

                    <div id="personel-container">
                        <div class="personel-item card mb-4 border-primary shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                <span class="personel-label text-primary fw-bold"><i
                                        class="fas fa-user me-2"></i>Personel #1</span>
                                <button type="button" class="btn btn-danger btn-sm remove-personel"
                                    style="display:none;">
                                    <i class="fas fa-times"></i> Hapus
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-secondary">Nama Pegawai</label>
                                        <select name="id_peg[]" class="form-select select-pegawai" required>
                                            <option value="">-- Pilih Pegawai --</option>
                                            <?php
                                            $res_peg = mysqli_query($conn, "SELECT id_peg, nama FROM pegawai ORDER BY nama ASC");
                                            while ($row = mysqli_fetch_assoc($res_peg)) {
                                                echo "<option value='" . $row['id_peg'] . "'>" . $row['nama'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-secondary">Tgl Pergi</label>
                                        <input type="date" name="tgl_pergi[]" class="form-control tgl-pergi" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-secondary">Tgl Pulang</label>
                                        <input type="date" name="tgl_pulang[]" class="form-control tgl-pulang" required>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle bg-white">
                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th width="40%">Item Pengeluaran</th>
                                                <th width="10%">Hari</th>
                                                <th width="25%">Nominal Satuan (Rp)</th>
                                                <th width="25%">Sub Total (Rp)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="row-harian">
                                                <td class="fw-bold ps-3">Uang Harian</td>
                                                <td><input type="text" name="hari_harian[]"
                                                        class="form-control text-center hari-harian bg-transparent border-0"
                                                        value="0" readonly></td>
                                                <td><input type="text" name="nom_harian[]"
                                                        class="form-control nom-harian bg-transparent border-0"
                                                        value="0" readonly></td>
                                                <td><input type="text" name="sub_harian[]"
                                                        class="form-control sub-total-personel bg-transparent border-0 fw-bold"
                                                        value="0" readonly></td>
                                            </tr>
                                            <tr class="table-info">
                                                <td class="fw-bold ps-3">Uang Representatif</td>
                                                <td><input type="number" name="hari_rep[]"
                                                        class="form-control text-center hari-rep" value="0" readonly>
                                                </td>
                                                <td><input type="number" name="nom_rep[]"
                                                        class="form-control nom-rep manual-input-rep" value="0"
                                                        readonly></td>
                                                <td><input type="number" name="sub_rep[]"
                                                        class="form-control sub-total-personel fw-bold" value="0"
                                                        readonly></td>
                                            </tr>
                                            <tr class="row-tiket">
                                                <td class="ps-4 text-muted small">Biaya Tiket</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                <td><input type="text" name="biaya_tiket[]"
                                                        class="form-control manual-input" value="0"></td>
                                            </tr>
                                            <tr class="row-penginapan">
                                                <td class="ps-4 text-muted small">Biaya Penginapan</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                <td><input type="text" name="biaya_penginapan[]"
                                                        class="form-control manual-input" value="0"></td>
                                            </tr>
                                            <tr class="row-transport-lokal">
                                                <td class="ps-4 text-muted small">Transport Lokal</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                <td><input type="text" name="biaya_transport_lokal[]"
                                                        class="form-control manual-input" value="0"></td>
                                            </tr>
                                            <tr class="row-tol">
                                                <td class="ps-4 text-muted small">Biaya Tol & Parkir</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                <td><input type="text" name="biaya_tol[]"
                                                        class="form-control manual-input" value="0"></td>
                                            </tr>
                                            <tr class="row-kontribusi">
                                                <td class="ps-4 text-muted small">Kontribusi Diklat</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center">-</td>
                                                <td><input type="text" name="biaya_kontribusi[]"
                                                        class="form-control manual-input" value="0"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-end mt-4">
                        <div class="col-md-6">
                            <div class="input-group input-group-lg shadow-sm">
                                <span class="input-group-text bg-dark text-white fw-bold">GRAND TOTAL BIAYA</span>
                                <input type="text" id="grand_total" name="grand_total"
                                    class="form-control fw-bold text-primary" value="0" readonly
                                    style="font-size: 1.5rem;">
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <hr>
                        <button type="reset" class="btn btn-secondary px-4 me-2"><i class="fas fa-undo"></i> Reset
                            Form</button>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow"><i
                                class="fas fa-save me-2"></i>Simpan Seluruh Data SPPD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>

    <script>
        $(document).ready(function () {
            // FUNGSI: Check Overlap Dinas
            function checkOverlapDinas(personelCard) {
                var idPeg = personelCard.find('.select-pegawai').val();
                var tglPergi = personelCard.find('.tgl-pergi').val();
                var tglPulang = personelCard.find('.tgl-pulang').val();
                var overlapAlert = personelCard.find('.overlap-alert');

                // Hapus alert sebelumnya
                overlapAlert.remove();

                // Jika belum lengkap, jangan cek
                if (!idPeg || !tglPergi || !tglPulang) {
                    return;
                }

                // Kirim AJAX untuk check overlap
                $.ajax({
                    url: 'check_overlap_dinas.php',
                    type: 'POST',
                    data: {
                        id_peg: idPeg,
                        tgl_pergi: tglPergi,
                        tgl_pulang: tglPulang
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'conflict') {
                            // Ada konflik - tampilkan warning
                            var alertHtml = '<div class="alert alert-warning alert-dismissible fade show overlap-alert" role="alert">';
                            alertHtml += '<strong><i class="fas fa-exclamation-circle me-2"></i>Peringatan Jadwal Dinas!</strong><br>';
                            alertHtml += response.message + '<br><br>';
                            alertHtml += '<small class="text-muted"><strong>Jadwal Dinas yang Konflik:</strong><br>';

                            $.each(response.overlaps, function (index, overlap) {
                                alertHtml += '• SPPD ' + overlap.no_sppd + ' (' + overlap.id_surat + ')<br>';
                                alertHtml += '&nbsp;&nbsp;Tanggal: ' + formatDate(overlap.tgl_pergi) + ' s/d ' + formatDate(overlap.tgl_pulang) + '<br>';
                                alertHtml += '&nbsp;&nbsp;Tujuan: ' + overlap.kota_tujuan + '<br>';
                            });

                            alertHtml += '</small>';
                            alertHtml += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                            alertHtml += '</div>';

                            personelCard.find('.card-body').prepend(alertHtml);

                            // Disable submit button
                            $('#formSPPD').data('has-conflict', true);
                            updateSubmitButtonState();
                        } else if (response.status === 'ok') {
                            // Tidak ada konflik
                            $('#formSPPD').data('has-conflict', false);
                            updateSubmitButtonState();
                        }
                    },
                    error: function () {
                        console.log('Error checking overlap');
                    }
                });
            }

            // FUNGSI: Format tanggal (helper)
            function formatDate(dateStr) {
                var date = new Date(dateStr + 'T00:00:00');
                var options = { weekday: 'short', year: 'numeric', month: '2-digit', day: '2-digit' };
                return date.toLocaleDateString('id-ID', options);
            }

            // FUNGSI: Update state tombol submit
            function updateSubmitButtonState() {
                var hasConflict = false;
                $('form#formSPPD').data('has-conflict', false);

                // Check apakah ada alert warning dalam form
                if ($('.overlap-alert').length > 0) {
                    hasConflict = true;
                    $('button[type="submit"]').prop('disabled', true).addClass('disabled opacity-50');
                    $('button[type="submit"]').html('<i class="fas fa-lock me-2"></i>Form tidak bisa disimpan (Ada Konflik Jadwal)');
                } else {
                    $('button[type="submit"]').prop('disabled', false).removeClass('disabled opacity-50');
                    $('button[type="submit"]').html('<i class="fas fa-save me-2"></i>Simpan Seluruh Data SPPD');
                }
            }

            // EVENT: Saat pegawai dipilih
            $(document).on('change', '.select-pegawai', function () {
                var personelCard = $(this).closest('.personel-item');
                checkOverlapDinas(personelCard);
            });

            // EVENT: Saat tanggal pergi berubah
            $(document).on('change', '.tgl-pergi', function () {
                var personelCard = $(this).closest('.personel-item');
                checkOverlapDinas(personelCard);
            });

            // EVENT: Saat tanggal pulang berubah
            $(document).on('change', '.tgl-pulang', function () {
                var personelCard = $(this).closest('.personel-item');
                checkOverlapDinas(personelCard);
            });

            // EVENT: Form submit - cek sekali lagi sebelum submit
            $('#formSPPD').on('submit', function (e) {
                if ($('.overlap-alert').length > 0) {
                    e.preventDefault();
                    alert('Tidak bisa menyimpan! Ada konflik jadwal dinas yang perlu diselesaikan.');
                    return false;
                }
            });

            // Event ketika Type Dinas berubah
            $('#type_dinas').change(function () {
                var type_id = $(this).val();

                // Aktifkan Lama Dinas untuk semua tipe dinas yang dipilih
                if (type_id !== "") {
                    $('#lama_dinas').prop('disabled', false);
                } else {
                    $('#lama_dinas').val('').prop('disabled', true);
                }

                if (!type_id) {
                    $('#provinsi').html('<option value="">-- Pilih Provinsi --</option>');
                    $('#kota').html('<option value="">-- Pilih Provinsi Dahulu --</option>');
                    return;
                }

                // Fetch provinsi berdasarkan type dinas
                $.ajax({
                    url: 'get_filter_data.php',
                    type: 'GET',
                    data: {
                        action: 'get_provinsi',
                        type_dinas: type_id
                    },
                    dataType: 'json',
                    success: function (data) {
                        var html = '<option value="">-- Pilih Provinsi --</option>';
                        $.each(data, function (index, item) {
                            html += '<option value="' + item.kode_prov + '">' + item.nama_prov + '</option>';
                        });

                        $('#provinsi').html(html);

                        // Jika DINAS DALAM KOTA (ID: 1)
                        if (type_id == "1") {
                            // Auto-select Jawa Tengah (Sesuaikan value '33' dengan kode_prov Jateng di DB)
                            $('#provinsi').val('33').change();

                            // Delay sejenak agar ajax kota selesai dimuat, lalu auto-select Klaten
                            setTimeout(function () {
                                $('#kota').val('KABUPATEN KLATEN');
                            }, 500);
                        } else {
                            $('#kota').html('<option value="">-- Pilih Provinsi Dahulu --</option>');
                        }
                    },
                    error: function () {
                        alert('Gagal mengambil data provinsi');
                    }
                });
            });

            // Event ketika Provinsi berubah
            $('#provinsi').change(function () {
                var type_id = $('#type_dinas').val();
                var provinsi = $(this).val();

                if (!provinsi) {
                    $('#kota').html('<option value="">-- Pilih Provinsi Dahulu --</option>');
                    return;
                }

                $.ajax({
                    url: 'get_filter_data.php',
                    type: 'GET',
                    data: {
                        action: 'get_kota',
                        type_dinas: type_id,
                        provinsi: provinsi
                    },
                    dataType: 'json',
                    success: function (data) {
                        var html = '<option value="">-- Pilih Kota --</option>';
                        $.each(data, function (index, item) {
                            html += '<option value="' + item + '">' + item + '</option>';
                        });
                        $('#kota').html(html);

                        // Memastikan Klaten terpilih jika dalam kondisi Dinas Dalam Kota
                        if (type_id == "1" && (provinsi == '33' || provinsi == 'JAWA TENGAH')) {
                            $('#kota').val('KABUPATEN KLATEN');
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>