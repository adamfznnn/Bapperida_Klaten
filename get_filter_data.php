<?php
/**
 * API untuk filter provinsi & kota berdasarkan type dinas
 */

include 'koneksi.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$type_dinas = isset($_GET['type_dinas']) ? intval($_GET['type_dinas']) : 0;
$provinsi = isset($_GET['provinsi']) ? mysqli_real_escape_string($conn, $_GET['provinsi']) : '';

header('Content-Type: application/json');

if ($action == 'get_provinsi') {
    // Jika type dinas = 1 (DINAS DALAM KOTA), hanya tampil Jawa Tengah
    if ($type_dinas == 1) {
        // DINAS DALAM KOTA - hanya Jawa Tengah
        $query = "SELECT kode_prov, nama_prov FROM provinsi WHERE nama_prov LIKE '%JAWA TENGAH%' ORDER BY nama_prov";
    } else {
        // Type dinas lain - tampil semua provinsi
        $query = "SELECT kode_prov, nama_prov FROM provinsi ORDER BY nama_prov ASC";
    }
    
    $result = mysqli_query($conn, $query);
    $provinsi_list = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $provinsi_list[] = $row;
    }
    
    echo json_encode($provinsi_list);
}
else if ($action == 'get_kota') {
    // Get kota berdasarkan provinsi & type dinas
    if ($type_dinas == 1) {
        // DINAS DALAM KOTA - tampil semua kota di provinsi (Jawa Tengah)
        // Jangan filter berdasarkan id_type, ambil semua kota di provinsi tersebut
        // gunakan DISTINCT untuk menghindari duplikasi nama kota
        $query = "SELECT DISTINCT nama_kota FROM kab_kota WHERE kode_prov='$provinsi' ORDER BY nama_kota ASC";
    } else {
        // Type dinas lain - tampil semua kota di provinsi
        $query = "SELECT DISTINCT nama_kota FROM kab_kota WHERE kode_prov='$provinsi' ORDER BY nama_kota ASC";
    }
    
    $result = mysqli_query($conn, $query);
    $kota_list = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $kota_list[] = $row['nama_kota'];
    }
    
    echo json_encode($kota_list);
}

mysqli_close($conn);
?>
