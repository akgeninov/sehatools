<?php
session_start();

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tangani data yang dikirim melalui AJAX
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Jika produk sudah ada di keranjang, hanya tambah kuantitas
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++; // Tambah jumlah produk
    } else {
        // Jika produk belum ada di keranjang, tambahkan produk dengan kuantitas 1
        $_SESSION['cart'][$product_id] = 1;
    }

    // Kembalikan jumlah item unik (jenis produk) di keranjang, bukan jumlah total kuantitas produk
    $unique_items = count($_SESSION['cart']); // Menghitung jumlah produk unik
    echo $unique_items; // Output jumlah item unik ke AJAX
}
