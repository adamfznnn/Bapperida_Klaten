<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mencegah SQL Injection
    $id = mysqli_real_escape_string($conn, $id);

    // Hapus data dari tabel sppd_personel terlebih dahulu (Foreign Key)
    mysqli_query($conn, "DELETE FROM sppd_personel WHERE id_sppd = '$id'");

    // Hapus data dari tabel utama sppd
    mysqli_query($conn, "DELETE FROM sppd WHERE id_sppd = '$id'");
}

// Redirect kembali ke halaman list_sppd.php
header("Location: list_sppd.php");
exit;
?>
