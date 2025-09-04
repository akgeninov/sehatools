<?php
session_start();
include "connect/connection.php";

$orderNumber = isset($_GET['order_id']) ? $_GET['order_id'] : '';

if (!$orderNumber) {
    die("Nomor pesanan tidak valid.");
}

// Cek status pesanan
$query = "SELECT * FROM orders WHERE order_number = '$orderNumber'";
$result = mysqli_query($koneksi, $query);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    die("Pesanan tidak ditemukan.");
}

// Kalau user sudah klik tombol 'yakin batal', proses update
if (isset($_GET['confirm']) && $_GET['confirm'] == "yes") {
    if ($order['status'] === 'Menunggu Pembayaran') {
        $update = "UPDATE orders SET status = 'Dibatalkan' WHERE order_number = '$orderNumber'";
        if (mysqli_query($koneksi, $update)) {
            $message = "Pesanan dengan nomor <strong>$orderNumber</strong> berhasil dibatalkan.";
        } else {
            $message = "Terjadi kesalahan saat membatalkan pesanan.";
        }
    } else {
        $message = "Pesanan tidak bisa dibatalkan (status: {$order['status']}).";
    }
}
mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Batalkan Pesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 80px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #dc3545;
        }
        p {
            margin-bottom: 20px;
            font-size: 16px;
            color: #333;
        }
        a, button {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            margin: 5px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: var(--secondary-color);
            color: #fff;
        }
        .btn-danger {
            background: #dc3545;
            color: #fff;
        }
        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }
    </style>
    <script>
    function confirmCancel() {
        let yakin = confirm("Pesanan anda masih dalam status Menunggu Pembayaran, apakah yakin ingin membatalkan pesanan?");
        if (yakin) {
            window.location.href = "<?php echo $_SERVER['PHP_SELF'].'?order_id='.$orderNumber.'&confirm=yes'; ?>";
        } else {
            window.location.href = "order_histor.php"; // arahkan balik ke riwayat pesanan
        }
    }
    </script>
</head>
<body>
    <div class="container">
        <h2>Batalkan Pesanan</h2>

        <?php if (!isset($message)) : ?>
            <?php if ($order['status'] === 'Menunggu Pembayaran') : ?>
                <script>confirmCancel();</script>
            <?php else : ?>
                <p>Pesanan tidak bisa dibatalkan (status: <?php echo $order['status']; ?>).</p>
                <a href="index.php" class="btn-secondary">Kembali ke Beranda</a>
            <?php endif; ?>
        <?php else : ?>
            <p><?php echo $message; ?></p>
            <a href="index.php" class="btn-primary">Kembali ke Beranda</a>
        <?php endif; ?>
    </div>
</body>
</html>
