$(document).ready(function () {

    /**
     * 1. FUNGSI PEMBANTU (HELPERS)
     */
    function formatRibuan(angka) {
        if (angka === undefined || angka === null || angka === "") return "0";
        let str = angka.toString().replace(/\D/g, "");
        if (str === "") return "0";
        return str.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function cleanNumber(str) {
        if (!str) return 0;
        let clean = str.toString().replace(/\./g, '');
        return parseInt(clean) || 0;
    }

    /**
     * 2. AJAX & UPDATER
     */

    // Update Kota & Nominal saat Provinsi atau Tipe Dinas berubah
    $('#provinsi, #type_dinas').on('change', function () {
        const kodeProv = $('#provinsi').val();
        const typeId = $('#type_dinas').val();
        const $kota = $('#kota');

        if (kodeProv) {
            // 1. Update Dropdown Kota
            $.post('get_filter_data.php?action=get_kota', {
                provinsi: kodeProv,
                type_dinas: typeId
            }, function (data) {
                try {
                    const res = typeof data === 'string' ? JSON.parse(data) : data;
                    let options = '<option value="">-- Pilih Kota --</option>';
                    $.each(res, function (i, item) {
                        options += `<option value="${item}">${item}</option>`;
                    });
                    $kota.html(options);
                } catch (e) {
                    $kota.html(data);
                }
            });

            // 2. Tarik Nominal Biaya Harian
            updateAllNominalHarian();
        }
    });

    function updateAllNominalHarian() {
        const prov = $('#provinsi').val();
        const typeId = $('#type_dinas').val();

        if (prov && typeId) {
            $.post('get_biaya.php', { kode_prov: prov, type: typeId }, function (nominal) {
                // Set nilai nominal ke semua input nominal harian
                $('.nom-harian').val(formatRibuan(nominal));
                // Hitung ulang semua baris
                $('.personel-item').each(function () {
                    kalkulasiBaris($(this));
                });
            });
        }
    }

    /**
     * 3. LOGIKA DROPDOWN LAMA DINAS
     */
    $('#type_dinas').on('change', function () {
        // PENTING: Gunakan .text() karena .val() adalah ID Angka
        const typeText = $(this).find('option:selected').text().toUpperCase().trim();
        const $lamaDinas = $('#lama_dinas');
        let options = '<option value="">-- Pilih Durasi --</option>';

        if (typeText.includes("DALAM KOTA")) {
            options += '<option value="< 8 jam">< 8 jam</option><option value="> 8 jam">> 8 jam</option>';
        } else if (typeText.includes("LUAR KOTA")) {
            options += '<option value="< 12 jam">< 12 jam</option><option value="Menginap">Menginap</option>';
        } else if (typeText.includes("DIKLAT") || typeText.includes("FULLDAY")) {
            options += '<option value="> 8 jam">> 8 jam</option>';
        } else if (typeText.includes("FULLBOARD")) {
            options += '<option value="Menginap">Menginap</option>';
        } else if (typeText.includes("HALFDAY")) {
            options += '<option value="> 5 jam">> 5 jam</option>';
        } else if (typeText.includes("RESIDENCE")) {
            options += '<option value="> 12 jam">> 12 jam</option>';
        }

        $lamaDinas.html(options);
        $('.personel-item').each(function () { updateVisibility($(this)); });
    });

    /**
     * 4. VISIBILITAS & FILTERING
     */
    function updateVisibility($parent) {
        const typeText = $('#type_dinas').find('option:selected').text().toUpperCase();
        const lama = $('#lama_dinas').val() || "";
        const trans = $('#transportasi').val() || "";
        const namaPegawai = $parent.find('.select-pegawai option:selected').text().toLowerCase();

        // Tampilkan semua dulu sebagai reset
        $parent.find('tbody tr').show();

        if (lama === "") return;

        // Sembunyikan semua kecuali yang memenuhi syarat
        $parent.find('tbody tr').hide();

        if (typeText.includes("LUAR KOTA")) {
            $parent.find('.row-harian, .row-rep, .row-penginapan, .row-tol').show();
            if (trans !== "Kendaraan Dinas") {
                $parent.find('.row-tiket, .row-transport-lokal').show();
            }
        }
        else if (typeText.includes("DALAM KOTA")) {
            if (lama.includes("> 8")) {
                $parent.find('.row-harian').show();
                if (trans === "Kendaraan Umum") $parent.find('.row-rep').show();
                if (trans === "Kendaraan Dinas" && namaPegawai.includes("pandu wirabangsa")) {
                    $parent.find('.row-rep').show();
                }
            } else {
                if (trans === "Kendaraan Umum") $parent.find('.row-harian').show();
            }
        }
        else if (typeText.includes("DIKLAT") || typeText.includes("FULLBOARD")) {
            $parent.find('.row-harian, .row-rep, .row-penginapan, .row-tol').show();
        }
        else {
            $parent.find('.row-harian, .row-rep').show();
        }

        kalkulasiBaris($parent);
    }

    /**
     * 5. KALKULASI
     */
    function kalkulasiBaris($parent) {
        // Kalkulasi Harian
        const hariHarian = parseInt($parent.find('.hari-harian').val()) || 0;
        const nomHarian = cleanNumber($parent.find('.nom-harian').val());
        $parent.find('input[name="sub_harian[]"]').val(formatRibuan(hariHarian * nomHarian));

        // Kalkulasi Representatif
        const hariRep = parseInt($parent.find('.hari-rep').val()) || 0;
        const nomRep = cleanNumber($parent.find('.nom-rep').val());
        $parent.find('input[name="sub_rep[]"]').val(formatRibuan(hariRep * nomRep));

        kalkulasiGrandTotal();
    }

    function kalkulasiGrandTotal() {
        let grandTotal = 0;
        $('.personel-item').each(function () {
            const $item = $(this);
            // PENTING: Hanya hitung baris yang :visible agar item yang difilter tidak ikut terjumlah
            $item.find('tbody tr:visible input[name*="sub"], tbody tr:visible input[name*="biaya"]').each(function () {
                grandTotal += cleanNumber($(this).val());
            });
        });
        $('#grand_total').val(formatRibuan(grandTotal));
    }

    /**
     * 6. EVENT LISTENERS
     */
    $(document).on('change', '#lama_dinas, #transportasi, .select-pegawai', function () {
        $('.personel-item').each(function () { updateVisibility($(this)); });
    });

    $(document).on('change', '.tgl-pergi, .tgl-pulang', function () {
        const $p = $(this).closest('.personel-item');
        const d1 = new Date($p.find('.tgl-pergi').val());
        const d2 = new Date($p.find('.tgl-pulang').val());
        if (!isNaN(d1) && !isNaN(d2)) {
            const diff = Math.floor((d2 - d1) / 86400000) + 1;
            const val = diff > 0 ? diff : 0;
            $p.find('.hari-harian, .hari-rep').val(val);
            kalkulasiBaris($p);
        }
    });

    $(document).on('keyup', '.manual-input', function () {
        // Jika nilai awal adalah "0" dan user mulai mengetik, hapus angka 0
        if ($(this).val() === "0" && event.key !== "Backspace" && event.key !== "Delete") {
            $(this).val("");
        }
        $(this).val(formatRibuan($(this).val()));
        kalkulasiGrandTotal();
    });

    // Fokus: highlight jika nilai 0
    $(document).on('focus', '.manual-input', function () {
        if ($(this).val() === "0") {
            $(this).val("");
        }
    });

    // Blur: jika kosong, isi dengan 0
    $(document).on('blur', '.manual-input', function () {
        if ($(this).val() === "" || $(this).val() === "0") {
            $(this).val("0");
            kalkulasiGrandTotal();
        }
    });

    $(document).on('change', '.select-pegawai', function () {
        const $parent = $(this).closest('.personel-item');
        const nama = $(this).find('option:selected').text().toLowerCase();
        const nominalRep = (nama.includes("pandu wirabangsa")) ? 150000 : 0;
        $parent.find('.nom-rep').val(formatRibuan(nominalRep));
        kalkulasiBaris($parent);
    });

    $('#add-personel').click(function () {
        let $clone = $('.personel-item:first').clone();
        $clone.find('input').val('0');
        $clone.find('input[type="date"]').val('');
        $clone.find('select').val('');
        $clone.find('.nom-harian').val($('.personel-item:first .nom-harian').val()); // Copy nominal yang sudah ada
        $('#personel-container').append($clone);
        updateVisibility($clone);
    });

    $(document).on('click', '.remove-personel', function () {
        if ($('.personel-item').length > 1) {
            $(this).closest('.personel-item').remove();
            kalkulasiGrandTotal();
        }
    });

    /**
     * 7. SUBMIT HANDLING
     */
    $('#formSPPD').on('submit', function (e) {
        let isValid = true;

        // Validasi Dasar
        if ($('#provinsi').val() === "" || $('#type_dinas').val() === "") {
            alert("Provinsi dan Tipe Dinas wajib diisi!");
            return false;
        }

        // Cleaning Ribuan sebelum kirim ke PHP
        $('input[name*="nom"], input[name*="sub"], input[name*="biaya"], #grand_total').each(function () {
            $(this).val(cleanNumber($(this).val()));
        });

        return true;
    });
});