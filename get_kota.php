<?php
include 'koneksi.php';

if (isset($_POST['kode_prov'])) {
    $kode_prov = mysqli_real_escape_string($conn, $_POST['kode_prov']);
    
    // Query mengambil nama_kota berdasarkan kode_prov
    // Note: Berdasarkan SQL anda, nama kolom adalah 'nama_kota' di tabel 'kab_kota'
    $query = "SELECT DISTINCT nama_kota FROM kab_kota WHERE kode_prov = '$kode_prov' ORDER BY nama_kota ASC";
    $result = mysqli_query($conn, $query);

    echo '<option value="">-- Pilih Kota --</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="'.$row['nama_kota'].'">'.$row['nama_kota'].'</option>';
    }
}
?>