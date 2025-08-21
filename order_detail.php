<?php 
    session_start();
    include "connect/connection.php";

    if (!isset($_SESSION['id_customer']) || !isset($_GET['id_order'])) {
        header("Location: order_history.php");
        exit();
    }

    $id_order = $_GET['id_order'];

    // Ambil data detail pesanan
    $queryDetails = "SELECT p.name_product, od.amount, od.price_each, od.subtotal
                     FROM orderdetails od
                     JOIN products p ON od.id_product = p.id_product
                     WHERE od.id_order = '$id_order'";
    $resultDetails = mysqli_query($koneksi, $queryDetails);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <h1>Detail Pesanan #<?php echo $id_order; ?></h1>

    <table>
        <tr>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($detail = mysqli_fetch_assoc($resultDetails)) : ?>
            <tr>
                <td><?php echo $detail['name_product']; ?></td>
                <td><?php echo $detail['amount']; ?></td>
                <td>Rp<?php echo number_format($detail['price_each'], 0, ',', '.'); ?></td>
                <td>Rp<?php echo number_format($detail['subtotal'], 0, ',', '.'); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="order_history.php">Kembali ke Riwayat Pesanan</a>
</body>
</html>
