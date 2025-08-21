<?php
session_start();
include "connect/connection.php";

// Cek apakah ada produk yang dipilih
if (!isset($_SESSION['selected_products']) || empty($_SESSION['selected_products'])) {
    echo "Tidak ada produk yang dipilih.";
    exit;
}

// Ambil daftar produk yang dipilih dari sesi
$selectedProducts = $_SESSION['selected_products'];

// Ambil informasi produk dari database
$productDetails = [];
foreach ($selectedProducts as $productData) {
    $product_id = $productData['id'];
    $quantity = $productData['quantity'];

    $query = "SELECT * FROM products WHERE id_product = $product_id";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($product = mysqli_fetch_assoc($result)) {
            $productDetails[] = [
                'name' => $product['name_product'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }
    }
}

// Menghitung total harga
$totalPrice = 0;
foreach ($productDetails as $product) {
    $totalPrice += $product['price'] * $product['quantity'];
}
$formattedTotalPrice = number_format($totalPrice, 0, ',', '.');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/shortcut.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="style/checkout.css">
    <title>sehatools</title>
</head>
<body>
    <header>
        <a href="index.php" class="logo"><img src="images/sehat.png" alt=""></a>
        <div class="search-nav">
            <div class="search-bar">
                <input type="search" placeholder="Temukan kebutuhanmu" name="" id="">
                <i class="ri-search-line"></i>
            </div>
            <ul>
                <li><a class="nav" href="#product">Produk</a></li>
                <li><a class="nav" href="#contact">Kontak</a></li>
                <li><a id="login" class="nav" href="./admin/start.php">Login sebagai Admin</a></li>
            </ul>
            <div class="menu-toggle">
                <input type="checkbox" name="" id="">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

    <h1>Checkout</h1>

    <h2>Produk yang Dipilih:</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productDetails as $product): 
                $subtotal = $product['price'] * $product['quantity'];
                $formattedSubtotal = number_format($subtotal, 0, ',', '.');
            ?>
            <tr>
                <td><?php echo $product['name']; ?></td>
                <td>Rp<?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                <td><?php echo $product['quantity']; ?></td>
                <td>Rp<?php echo $formattedSubtotal; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: center; font-weight: bold;">TOTAL</td>
                <td style="font-weight: bold;">Rp<?php echo $formattedTotalPrice; ?></td>
            </tr>
        </tfoot>
    </table>

    <h2>Data Diri</h2>
    <form action="process_checkout.php" method="POST">
        <label for="name">Nama Lengkap:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Nomor HP:</label>
        <input type="tel" id="phone" name="phone" required pattern="^\+?\d{10,15}$" placeholder="Contoh: 08123456789">

        <label for="address">Alamat:</label>
        <textarea id="address" name="address" required></textarea>

        <label for="city">Kota:</label>
        <input type="text" id="city" name="city" required>

        <label for="province">Provinsi:</label>
        <input type="text" id="province" name="province" required>

        <label for="postal_code">Kode Pos:</label>
        <input type="text" id="postal_code" name="postal_code" required pattern="^\d{5}$" placeholder="Contoh: 12345">

        <label for="payment_method">Metode Pembayaran:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="transfer">Transfer Bank</option>
            <option value="cash_on_delivery">Cash on Delivery</option>
        </select>

        <!-- <label for="shipping_method">Metode Pengiriman:</label>
        <select id="shipping_method" name="shipping_method" required>
            <option value="standard">Pengiriman Reguler</option>
            <option value="express">Pengiriman Ekspres</option>
        </select> -->

        <button type="submit">Buat Pesanan</button>
    </form>
</body>
</html>