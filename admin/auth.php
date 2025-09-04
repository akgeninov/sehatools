<?php
session_start();
if (!isset($_SESSION['userweb'])) {
    // kalau belum login, paksa balik ke login
    echo "<script>
            alert('Silakan login/daftar terlebih dahulu');
            window.location='start.php';
        </script>";
    exit;
}
?>
