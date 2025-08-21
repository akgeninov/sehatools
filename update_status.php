<?php
include "connect/connection.php";

if (isset($_GET['id_order']) && isset($_GET['action']) && $_GET['action'] === 'pay') {
    $id_order = $_GET['id_order'];

    // Query untuk mengambil status pesanan saat ini
    $query = "SELECT status FROM orders WHERE id_order = '$id_order'";
    $result = mysqli_query($koneksi, $query);
    $order = mysqli_fetch_assoc($result);

    if ($order) {
        $currentStatus = $order['status'];
        
        // Ubah status menjadi "Diproses" hanya jika status saat ini adalah "Menunggu Pembayaran"
        if ($currentStatus === 'Menunggu Pembayaran') {
            $updateQuery = "UPDATE orders SET status = 'Diproses' WHERE id_order = '$id_order'";
            if (mysqli_query($koneksi, $updateQuery)) {
                header("Location: order_histor.php?status_update=success");
                exit;
            } else {
                echo "Gagal memperbarui status pesanan.";
            }
        } else {
            // Redirect jika status sudah "Diproses" atau "Dikirim"
            header("Location: order_histor.php?status_update=already_processed");
            exit;
        }
    } else {
        echo "Pesanan tidak ditemukan.";
    }
} else {
    echo "Parameter tidak valid.";
}