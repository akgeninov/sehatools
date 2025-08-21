<?php
session_start();
include "connect/connection.php"; // Pastikan koneksi ke database berhasil

// Inisialisasi variabel untuk pesan error dan order
$errorMsg = "";
$order = null;
$newOrder = null;

// Cek apakah ada data pesanan baru dari session
if (isset($_SESSION['new_order'])) {
    $newOrder = $_SESSION['new_order'];
    unset($_SESSION['new_order']); // Hapus data setelah ditampilkan
}

// Cek apakah form di-submit
if (isset($_GET['check_order'])) {
    $order_id = mysqli_real_escape_string($koneksi, $_GET['order_id']);
    $unique_code = mysqli_real_escape_string($koneksi, $_GET['unique_code']);

    // Ambil status pesanan saat ini berdasarkan order_id dan unique_code
    $queryStatus = "SELECT * FROM orders WHERE order_number = '$order_id' AND unique_code = '$unique_code'";
    $resultStatus = mysqli_query($koneksi, $queryStatus);

    if ($resultStatus && mysqli_num_rows($resultStatus) > 0) {
        $order = mysqli_fetch_assoc($resultStatus);
    } else {
        $errorMsg = "Pesanan tidak ditemukan dengan Order Number: $order_id dan Unique Code: $unique_code.";
    }
} elseif (isset($_GET['order_id']) && isset($_GET['unique_code'])) {
    $order_id = mysqli_real_escape_string($koneksi, $_GET['order_id']);
    $unique_code = mysqli_real_escape_string($koneksi, $_GET['unique_code']);

    // Ambil status pesanan saat ini berdasarkan order_id dan unique_code
    $queryStatus = "SELECT * FROM orders WHERE order_number = '$order_id' AND unique_code = '$unique_code'";
    $resultStatus = mysqli_query($koneksi, $queryStatus);

    if ($resultStatus && mysqli_num_rows($resultStatus) > 0) {
        $order = mysqli_fetch_assoc($resultStatus);

        // Jika statusnya "Menunggu Pembayaran" dan metode pembayaran bukan "cash_on_delivery"
        if ($order['status'] === 'Menunggu Pembayaran') {
            if ($order['payment_method'] !== 'cash_on_delivery') {
                $updateQuery = "UPDATE orders SET status = 'Diproses' WHERE order_number = '$order_id'";
                if (mysqli_query($koneksi, $updateQuery)) {
                    $order['status'] = 'Diproses'; // Update status dalam array $order
                    $alertMessage = "Status pesanan berhasil diperbarui menjadi 'Diproses'.";
                } else {
                    $errorMsg = "Error updating status: " . mysqli_error($koneksi); // Simpan error ke variabel error
                }
            }
        }
    } else {
        $errorMsg = "Pesanan tidak ditemukan dengan Order Number: $order_id dan Unique Code: $unique_code.";
    }
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
    <link rel="stylesheet" href="style/order_history.css">
    <title>sehatools - Cek Pesanan</title>
</head>
<body>
    <header>
        <a href="index.php" class="logo"><img src="images/sehat.png" alt="Logo Sehatools"></a>
    </header>

    <h2>Cek Status Pesanan Anda</h2>
    <section class="order-check">
        <div class="form">
        
        <!-- Form untuk cek status pesanan -->
        <form action="order_histor.php" method="GET">
            <label for="order_id">Order Number</label>
            <input type="text" id="order_id" name="order_id" required>

            <label for="unique_code">Kode Unik</label>
            <input type="text" id="unique_code" name="unique_code" required>

            <button type="submit" name="check_order">Cek Pesanan</button>
        </form>

        <!-- Tampilkan data pesanan terbaru -->
        <?php if ($newOrder): ?>
            <div class="new-order-card">
                <h3>Pesanan Terbaru Anda</h3>
                <p>No. Pesanan: <strong><?php echo $newOrder['order_number']; ?></strong></p>
                <p>Status: <strong><?php echo $newOrder['status']; ?></strong></p>
                <p>Total: <strong>Rp<?php echo number_format($newOrder['total'], 0, ',', '.'); ?></strong></p>
            </div>
        <?php endif; ?>

        </div>
        <div class="data-order">
            <?php
            // Tampilkan pesan error jika ada
            if ($errorMsg) {
                echo "<p style='color: red;'>$errorMsg</p>";
            }

            // Jika data pesanan ditemukan, tampilkan detail pesanan
            if ($order) {
                echo '<div class="order-card">';
                    echo '<div class="no-status">';
                        echo '<p class="no-order">No. Pesanan: ' . $order['order_number'] . '</p>';
                        echo '<p class="status">' . $order['status'] . '</p>';
                    echo '</div>';
                    echo "<hr style='border: 1px solid #dedede; width: 100%; margin: 10px auto'>";
                    echo '<div class="order-info">';

                // Mengambil detail produk untuk pesanan ini
                $id_order = $order['id_order'];
                $queryItems = "SELECT products.name_product, products.link_image, orderdetails.amount, orderdetails.price_each 
                            FROM orderdetails 
                            JOIN products ON orderdetails.id_product = products.id_product 
                            WHERE orderdetails.id_order = '$id_order'";
                $resultItems = mysqli_query($koneksi, $queryItems);

                if ($resultItems && mysqli_num_rows($resultItems) > 0) {
                    while ($item = mysqli_fetch_assoc($resultItems)) {
                        echo '<div class="product-detail">';
                            echo '<div class="product">';
                                echo '<img src="' . $item['link_image'] . '" alt="' . $item['name_product'] . '">';
                                echo '<div class="name-amount">';
                                    echo '<h4>' . $item['name_product'] . '</h4>';
                                    echo '<p>Jumlah: ' . $item['amount'] . '</p>';
                                echo '</div>';
                            echo '</div>';
                            echo '<p>Rp' . number_format($item['price_each'], 0, ',', '.') . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>Tidak ada produk dalam pesanan ini.</p>";
                }

                echo '<p>Total: <strong>Rp' . number_format($order['total'], 0, ',', '.') . '</strong></p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </section>
    <a href="index.php" class="button">Kembali ke Beranda</a>

    <script>
        // Tampilkan alert jika ada pesan
        <?php if ($alertMessage): ?>
            alert("<?php echo $alertMessage; ?>");
        <?php endif; ?>
    </script>

</body>
</html>
