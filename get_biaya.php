<?php
include 'koneksi.php';

if (isset($_POST['kode_prov']) && isset($_POST['type'])) {
    $kode_prov = mysqli_real_escape_string($conn, $_POST['kode_prov']);
    $type_id = $_POST['type']; // Sekarang berisi ID (1, 2, 3, dst)

    // 1. Ambil nama provinsi dan cari nama tipe dinasnya berdasarkan ID
    $q_prov = mysqli_query($conn, "SELECT nama_prov FROM provinsi WHERE kode_prov = '$kode_prov'");
    $data_prov = mysqli_fetch_assoc($q_prov);
    
    $q_type = mysqli_query($conn, "SELECT nama_type FROM type_dinas WHERE id_type = '$type_id'");
    $data_type = mysqli_fetch_assoc($q_type);

    if (!$data_prov || !$data_type) {
        echo 0;
        exit;
    }

    $nama_prov = $data_prov['nama_prov'];
    $nama_type = strtolower($data_type['nama_type']); // Ubah ke lowercase untuk pengecekan

    // 2. Cari di tabel biaya_perjalanan berdasarkan Nama Provinsi
    $query = mysqli_query($conn, "SELECT * FROM biaya_perjalanan WHERE kota = '$nama_prov' LIMIT 1");
    $biaya = mysqli_fetch_assoc($query);

    $nominal = 0;
    if ($biaya) {
        // Logika pengecekan menggunakan nama_type yang diambil dari database berdasarkan ID
        if (strpos($nama_type, 'luar kota') !== false) {
            $nominal = $biaya['dinas_luar'];
        } elseif (strpos($nama_type, 'diklat') !== false) {
            $nominal = $biaya['diklat'];
        } elseif (strpos($nama_type, 'fullboard') !== false) {
            $nominal = $biaya['fullboard'];
        } elseif (strpos($nama_type, 'dalam kota') !== false || strpos($nama_type, 'residence') !== false) {
            $nominal = $biaya['residence_dalam_kota'];
        } elseif (strpos($nama_type, 'fullday') !== false || strpos($nama_type, 'halfday') !== false) {
            $nominal = $biaya['fullday_halfday'];
        }
    }
    
    // Kembalikan angka murni (tanpa format) agar JavaScript bisa memproses
    echo $nominal ? $nominal : 0;
}
?>