<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/phpmailer/phpmailer/src/SMTP.php';
require './vendor/phpmailer/phpmailer/src/Exception.php';

session_start();
include "connect/connection.php";

// Variabel untuk status pesan
$message = '';
$orderNumber = '';
$unique_code = '';
$total_harga = '';
$metode_pembayaran = '';

// Ambil data dari formulir checkout
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['name'];
    $email = $_POST['email'];
    $telepon = $_POST['phone'];
    $alamat = $_POST['address'];
    $kota = $_POST['city'];
    $provinsi = $_POST['province'];
    $kode_pos = $_POST['postal_code'];
    $metode_pembayaran = $_POST['payment_method'];

    // Ambil produk yang dipilih dari sesi
    $selectedProducts = $_SESSION['selected_products'];

    // Fungsi untuk menghasilkan nomor pesanan unik
    function generateOrderNumber($connection) {
        $date = date("Ymd");
        $query = "SELECT COUNT(*) AS order_count FROM orders WHERE DATE(order_date) = CURDATE()";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $orderCount = $row['order_count'] + 1;
        return 'ORD-' . $date . str_pad($orderCount, 3, '0', STR_PAD_LEFT);
    }

    // Simpan data pelanggan
    $queryPelanggan = "INSERT INTO customers (name_customer, email, phone, shipping_address, city, postal_code, county) VALUES ('$nama', '$email', '$telepon', '$alamat', '$kota', '$kode_pos', '$provinsi')";
    if (mysqli_query($koneksi, $queryPelanggan)) {
        $id_pelanggan = mysqli_insert_id($koneksi);
        $_SESSION['id_customer'] = $id_pelanggan;

        // Hitung total harga
        $total_harga = 0;
        foreach ($selectedProducts as $productData) {
            $product_id = $productData['id'];
            $quantity = $productData['quantity'];
            $queryProduct = "SELECT price FROM products WHERE id_product = $product_id";
            $resultProduct = mysqli_query($koneksi, $queryProduct);
            $product = mysqli_fetch_assoc($resultProduct);
            $total_harga += $product['price'] * $quantity;
        }

        // Buat nomor pesanan unik
        $orderNumber = generateOrderNumber($koneksi);
        $unique_code = rand(100000, 999999); // Kode unik 6 digit

        // Simpan data pesanan
        $queryPesanan = "INSERT INTO orders (id_customer, total, payment_method, status, order_number, unique_code) VALUES ('$id_pelanggan', '$total_harga', '$metode_pembayaran', 'Menunggu Pembayaran', '$orderNumber', '$unique_code')";
        if (mysqli_query($koneksi, $queryPesanan)) {
            $id_pesanan = mysqli_insert_id($koneksi);

            // Simpan rincian pesanan
            foreach ($selectedProducts as $productData) {
                $product_id = $productData['id'];
                $quantity = $productData['quantity'];
                $queryProduct = "SELECT price FROM products WHERE id_product = $product_id";
                $resultProduct = mysqli_query($koneksi, $queryProduct);
                $product = mysqli_fetch_assoc($resultProduct);
                $subtotal = $product['price'] * $quantity;

                $queryRincian = "INSERT INTO orderdetails (id_order, id_product, price_each, amount, subtotal) VALUES ('$id_pesanan', '$product_id', '{$product['price']}', '$quantity', '$subtotal')";
                mysqli_query($koneksi, $queryRincian);
            }

            // Kirim Email Konfirmasi dengan Link Pembayaran dan Pembatalan
            $mail = new PHPMailer(true);
            try {
                // Konfigurasi SMTP
                $mail->isSMTP();
                $mail->Host = $_ENV['SMTP_HOST'];
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['SMTP_USER'];  // Ganti dengan email Anda
                $mail->Password = $_ENV['SMTP_PASS'];  // Ganti dengan password Anda
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = (int) $_ENV['SMTP_PORT'];

                // Pengaturan Email
                $mail->setFrom($_ENV['SMTP_USER'], 'SEHATOOLS');
                $mail->addAddress($email, $nama); // Kirim ke email pelanggan

                $mail->isHTML(true);
                $mail->Subject = "Konfirmasi Pesanan Anda - $orderNumber";

                $_SESSION['order_number'] = $orderNumber; // Simpan nomor pesanan dalam sesi
                $_SESSION['unique_code'] = $unique_code; // Simpan kode unik dalam sesi

                $paymentLink = "http://localhost/sehatools-pelanggan/order_histor.php?order_id=$orderNumber&unique_code=$unique_code";
                $cancelLink = "http://localhost/sehatools-pelanggan/cancel_order.php?order_id=$orderNumber";

                $mail->Body = "<h1>Terima Kasih atas Pesanan Anda!</h1>
                <p>Nomor Pesanan: <strong>$orderNumber</strong></p>
                <p>Kode Unik: <strong>$unique_code</strong></p>
                <p>Total: Rp" . number_format($total_harga, 0, ',', '.') . "</p>
                <p>Metode Pembayaran: $metode_pembayaran</p>";

                // Tambahkan link "Bayar Sekarang" hanya jika metode pembayaran adalah transfer
                if ($metode_pembayaran === 'transfer') {
                    $mail->Body .= "<p><a href='$paymentLink' style='color: #4CAF50; font-weight: bold; text-decoration: none;'>Bayar Sekarang</a></p>";
                }

                // Tambahkan link "Cek Pesanan" untuk kedua metode pembayaran
                $mail->Body .= "<p><a href='$paymentLink' style='color: #4CAF50; font-weight: bold; text-decoration: none;'>Cek Pesanan</a></p>";

                // Tambahkan link pembatalan pesanan
                $mail->Body .= "<p><a href='$cancelLink' style='color: #FF0000; font-weight: bold; text-decoration: none;'>Batalkan Pesanan</a></p>";

                $mail->send();

                // Hapus produk yang dipilih dari sesi
                unset($_SESSION['selected_products']);
                $message = "Pesanan Anda berhasil kami terima, cek email dari kami untuk melakukan pembayaran dan cek pesanan.";
            } catch (Exception $e) {
                $message = "Email gagal dikirim. Error: {$mail->ErrorInfo}";
                exit; // Menghentikan eksekusi jika ada error
            }

        } else {
            $message = "Error: " . mysqli_error($koneksi);
        }
    } else {
        $message = "Error: " . mysqli_error($koneksi);
    }

    // Tutup koneksi
    mysqli_close($koneksi);
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
    <link rel="stylesheet" href="style/process_checkou.css">
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
                <!-- <li><a class="nav" href="#home">Home</a></li> -->
                <li><a class="nav" href="#product">Produk</a></li>
                <!-- <li><a class="nav" href="#aboutus">About Us</a></li> -->
                <li><a class="nav" href="order_histor.php">Lihat Pesanan</a></li>
                <li><a class="nav" href="#contact">Kontak</a></li>
                <!-- <li><a id="login" class="nav" href="./admin/start.php">Login sebagai Admin</a></li> -->
            </ul>
            <div class="menu-toggle">
                <input type="checkbox" name="" id="">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>
        <div class="container">
            <h1 style="text-align: center; margin-bottom: 10px">Pesanan Anda Berhasil!</h1>
            <?php if ($metode_pembayaran === 'cash_on_delivery'): ?>
                <p>Pesanan Anda berhasil kami terima, cek email dari kami untuk melakukan cek pesanan.</p>
            <?php else: ?>
                <p>Pesanan Anda berhasil kami terima, cek email dari kami untuk melakukan konfirmasi pembayaran dan cek pesanan.</p>
            <?php endif; ?>
            <p>Nomor Pesanan: <strong><?php echo htmlspecialchars($orderNumber); ?></strong></p>
            <!-- <p>Kode Unik: <strong><?php echo htmlspecialchars($unique_code); ?></strong></p> -->
            <p>Total: Rp<?php echo number_format($total_harga, 0, ',', '.'); ?></p>
            <p>Metode Pembayaran: <?php echo htmlspecialchars($metode_pembayaran); ?></p>
            <!-- <a href="order_history.php" class="link">Cek Pesanan</a> -->
        </div>
</body>
</html>
