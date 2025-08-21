<?php
session_start();

// Hapus data selected_products sebelumnya
unset($_SESSION['selected_products']);

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Buat ulang array selected_products untuk menyimpan produk baru
    $_SESSION['selected_products'] = [
        [
            'id' => $product_id,
            'quantity' => $quantity,
        ]
    ];

    echo "Produk berhasil ditambahkan ke session.";
} else {
    echo "Data produk tidak lengkap.";
}
?>
