<?php
include 'koneksi.php';

$id = $_GET['id_peg'];

mysqli_query($conn, "DELETE FROM pegawai WHERE id_peg = '$id'");

header("Location: list_pegawai.php");
exit;
