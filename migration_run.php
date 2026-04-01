<?php
include 'koneksi.php';

// Script untuk menambah kolom biaya ke tabel sppd_personel
$alterQueries = [
    "ALTER TABLE `sppd_personel` ADD COLUMN `biaya_tiket` DECIMAL(15,0) DEFAULT 0 AFTER `nom_rep`",
    "ALTER TABLE `sppd_personel` ADD COLUMN `biaya_penginapan` DECIMAL(15,0) DEFAULT 0 AFTER `biaya_tiket`",
    "ALTER TABLE `sppd_personel` ADD COLUMN `biaya_transport_lokal` DECIMAL(15,0) DEFAULT 0 AFTER `biaya_penginapan`",
    "ALTER TABLE `sppd_personel` ADD COLUMN `biaya_tol` DECIMAL(15,0) DEFAULT 0 AFTER `biaya_transport_lokal`",
    "ALTER TABLE `sppd_personel` ADD COLUMN `biaya_kontribusi` DECIMAL(15,0) DEFAULT 0 AFTER `biaya_tol`",
];

echo "<h1>Database Migration - Menambah Kolom Biaya</h1>";
echo "<hr>";

foreach ($alterQueries as $i => $query) {
    echo "Query " . ($i + 1) . ": " . htmlspecialchars($query) . "<br>";
    
    if (mysqli_query($conn, $query)) {
        echo "<span style='color: green;'><strong>✓ Berhasil</strong></span><br><br>";
    } else {
        $error = mysqli_error($conn);
        // Cek apakah error karena kolom sudah ada
        if (strpos($error, 'Duplicate column name') !== false || strpos($error, 'already exists') !== false) {
            echo "<span style='color: orange;'><strong>⚠ Kolom sudah ada (skip)</strong></span><br><br>";
        } else {
            echo "<span style='color: red;'><strong>✗ Error: $error</strong></span><br><br>";
        }
    }
}

echo "<hr>";
echo "<h3>✓ Migrasi Selesai!</h3>";
echo "<p><a href='index.php'>Kembali ke Form Input SPPD</a></p>";
?>
