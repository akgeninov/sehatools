<?php
session_start();

// Cek apakah ada permintaan untuk menambahkan item ke keranjang
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    // Tambahkan jumlah item di keranjang pada session
    $_SESSION['cart_count'] += 1;

    // Kembalikan jumlah baru sebagai respons
    echo $_SESSION['cart_count'];
}
?>
