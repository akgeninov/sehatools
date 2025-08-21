<?php
session_start();
include "connect/connection.php";

// Cek apakah pengguna sudah login dan memiliki id_order
if (!isset($_SESSION['id_customer']) || !isset($_GET['id_order'])) {
    echo "Akses tidak diizinkan.";
    exit;
}

$id_customer = $_SESSION['id_customer'];
$id_order = $_GET['id_order'];

// Ambil detail pesanan dari database
$queryPesanan = "SELECT * FROM orders WHERE id_order = '$id_order' AND id_customer = '$id_customer'";
$hasil = mysqli_query($koneksi, $queryPesanan);
$order = mysqli_fetch_assoc($hasil);

if (!$order) {
    echo "Pesanan tidak ditemukan atau tidak berhak mengakses pesanan ini.";
    exit;
}

// Hitung tenggat waktu 24 jam
$orderDate = new DateTime($order['order_date']);
$tenggatWaktu = $orderDate->add(new DateInterval('P1D'));
$tenggatWaktuFormatted = $tenggatWaktu->format('d-m-Y H:i');

// Cek apakah metode pembayaran adalah Transfer Bank dan status Menunggu Pembayaran
if ($order['payment_method'] !== 'Transfer Bank' || $order['status'] !== 'Menunggu Pembayaran') {
    echo "Pembayaran tidak dapat dilakukan untuk pesanan ini.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/shortcut.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="style/payment.css">
    <title>sehatools - Pembayaran</title>
</head>
<body>
    <header>
        <a href="index.php" class="logo"><img src="images/sehat.png" alt="Logo Sehatools"></a>
    </header>

    <section class="payment-section">
        <h2>Pembayaran Pesanan</h2>

        <div class="order-details">
            <p><strong>ID Pesanan:</strong> <?php echo $order['id_order']; ?></p>
            <p><strong>Total Pembayaran:</strong> Rp<?php echo number_format($order['total'], 0, ',', '.'); ?></p>
            <p><strong>Tenggat Waktu Pembayaran:</strong> <?php echo $tenggatWaktuFormatted; ?></p>
        </div>

        <div class="bank-details">
            <h3>Transfer ke Rekening Berikut:</h3>
            <p><strong>Bank:</strong> BCA</p>
            <p><strong>Nomor Rekening:</strong> 1234567890</p>
            <p><strong>Atas Nama:</strong> PT. Sehatools Indonesia</p>
        </div>

        <p>Setelah melakukan pembayaran, harap kirim bukti transfer melalui halaman <a href="confirmation.php?id_order=<?php echo $order['id_order']; ?>">konfirmasi pembayaran</a>.</p>
        
        <a href="order_history.php" class="button">Kembali ke Riwayat Pesanan</a>
    </section>

</body>
</html>
