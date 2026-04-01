<?php
session_start();

// Check apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}
?>
