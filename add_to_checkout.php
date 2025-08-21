<?php
session_start();
include "connect/connection.php";

// Ambil data JSON dari fetch
$selectedProducts = json_decode(file_get_contents('php://input'), true);

// Hapus produk sebelumnya dari sesi, jika ada
unset($_SESSION['selected_products']);

// Simpan produk terpilih baru ke dalam sesi
$_SESSION['selected_products'] = $selectedProducts;

// Kirim respons sukses ke JavaScript
echo json_encode(['status' => 'success']);
?>