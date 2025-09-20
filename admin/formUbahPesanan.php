<?php
include "../connect/connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require '../vendor/autoload.php';

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';

require '../vendor/autoload.php';

require 'fpdf186/fpdf.php';

$data_admin = mysqli_query($koneksi, "SELECT * FROM admin");
$dataAdmin = mysqli_fetch_assoc($data_admin);

// $sql = mysqli_query($koneksi, "SELECT * FROM orders where id_order = '$_GET[kode]'");
// $data = mysqli_fetch_array($sql);

$sql = mysqli_query($koneksi, "SELECT o.*, c.* FROM orders o JOIN customers c ON o.id_customer = c.id_customer WHERE o.id_order = '$_GET[kode]'");
$data = mysqli_fetch_array($sql);

// function generateInvoicePDF($data, $koneksi) {
//     $pdf = new FPDF();
//     $pdf->AddPage();

//     // Header
//     $pdf->SetFont('Arial', 'B', 16);
//     $pdf->Cell(0, 10, 'SEHATOOLS', 0, 1, 'C');
//     $pdf->SetFont('Arial', '', 14);
//     $pdf->Cell(0, 10, 'Laporan Belanja Anda', 0, 1, 'C');
//     $pdf->Ln(10);

//     // Informasi Pesanan
//     $pdf->SetFont('Arial', '', 12);
//     $pdf->Cell(0, 10, 'User ID: ' . $data['id_customer'], 0, 1);
//     $pdf->Cell(0, 10, 'Nama: ' . $data['name_customer'], 0, 1);
//     $pdf->Cell(0, 10, 'Alamat Pengiriman: ' . $data['shipping_address'], 0, 1);
//     $pdf->Cell(0, 10, 'No HP: ' . $data['phone'], 0, 1);
//     $pdf->Cell(0, 10, 'Tanggal Pemesanan: ' . $data['order_date'], 0, 1);
//     $pdf->Cell(0, 10, 'Metode Pembayaran: ' . $data['payment_method'], 0, 1);
//     $pdf->Ln(10);

//     // Header Tabel Produk
//     $pdf->SetFont('Arial', 'B', 12);
//     $pdf->Cell(60, 10, 'Nama Produk', 1);
//     $pdf->Cell(30, 10, 'Harga', 1);
//     $pdf->Cell(30, 10, 'Kuantitas', 1);
//     $pdf->Cell(40, 10, 'Subtotal', 1);
//     $pdf->Ln();

//     // Mendapatkan data produk dari tabel orderdetails
//     $id_order = $data['id_order'];
//     $produkQuery = mysqli_query($koneksi, "SELECT p.name_product AS nama_produk, p.price, od.amount, (p.price * od.amount) AS subtotal
//                                            FROM orderdetails od
//                                            JOIN products p ON od.id_product = p.id_product
//                                            WHERE od.id_order = '$id_order'");

//     // Isi Tabel Produk
//     $pdf->SetFont('Arial', '', 12);
//     $total = 0;
//     while ($produk = mysqli_fetch_assoc($produkQuery)) {
//         $pdf->Cell(60, 10, $produk['nama_produk'], 1);
//         $pdf->Cell(30, 10, number_format($produk['price'], 0, ',', '.'), 1);
//         $pdf->Cell(30, 10, $produk['amount'], 1);
//         $pdf->Cell(40, 10, number_format($produk['subtotal'], 0, ',', '.'), 1);
//         $pdf->Ln();
//         $total += $produk['subtotal'];
//     }

//     // Total
//     $pdf->SetFont('Arial', 'B', 12);
//     $pdf->Cell(120, 10, 'Total', 1);
//     $pdf->Cell(40, 10, number_format($total, 0, ',', '.'), 1);
//     $pdf->Ln(20);

//     // Logo di bagian bawah
//     $pdf->Image('./images/sehat.png', 10, $pdf->GetY(), 30); // Ganti path sesuai lokasi logo

//     // Simpan file PDF
//     $filename = 'invoices/invoice_' . $data['order_number'] . '.pdf';
//     $pdf->Output('F', $filename);

//     return $filename;
// }

// Fungsi kirim email status
function sendEmailStatus($to, $nama, $order_number, $status, $payment_method, $total)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int) $_ENV['SMTP_PORT'];

        $mail->setFrom($_ENV['SMTP_USER'], 'SEHATOOLS');
        $mail->addAddress($to, $nama);

        $mail->isHTML(true);
        $mail->Subject = "Update Status Pesanan Anda - $order_number";

        $mail->Body = "
            <h2>Status Pesanan Anda Telah Diperbarui</h2>
            <p>Nomor Pesanan: <strong>$order_number</strong></p>
            <p>Status Terbaru: <strong>$status</strong></p>
            <p>Total: Rp" . number_format($total, 0, ',', '.') . "</p>
            <p>Metode Pembayaran: $payment_method</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        echo "Email tidak dapat dikirim. Kesalahan: {$mail->ErrorInfo}";
    }
}

function sendEmailWithInvoice($to, $order_number, $data, $koneksi)
{
    // Membuat PDF invoice
    $pdf = new FPDF();
    $pdf->AddPage();

    // Header
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'SEHATOOLS', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(0, 10, 'Laporan Belanja Anda', 0, 1, 'C');
    $pdf->Ln(10);

    // Informasi Pesanan
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'User ID: ' . $data['id_customer'], 0, 1);
    $pdf->Cell(0, 10, 'Nama: ' . $data['name_customer'], 0, 1);
    $pdf->Cell(0, 10, 'Alamat Pengiriman: ' . $data['shipping_address'], 0, 1);
    $pdf->Cell(0, 10, 'No HP: ' . $data['phone'], 0, 1);
    $pdf->Cell(0, 10, 'Tanggal Pemesanan: ' . $data['order_date'], 0, 1);
    $pdf->Cell(0, 10, 'Metode Pembayaran: ' . $data['payment_method'], 0, 1);
    $pdf->Ln(10);

    // Header Tabel Produk
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'Nama Produk', 1);
    $pdf->Cell(30, 10, 'Harga', 1);
    $pdf->Cell(30, 10, 'Kuantitas', 1);
    $pdf->Cell(40, 10, 'Subtotal', 1);
    $pdf->Ln();

    // Mendapatkan data produk dari tabel orderdetails
    $id_order = $data['id_order'];
    $produkQuery = mysqli_query($koneksi, "SELECT p.name_product AS nama_produk, p.price, od.amount, (p.price * od.amount) AS subtotal
                                               FROM orderdetails od
                                               JOIN products p ON od.id_product = p.id_product
                                               WHERE od.id_order = '$id_order'");

    // Isi Tabel Produk
    $pdf->SetFont('Arial', '', 12);
    $total = 0;
    while ($produk = mysqli_fetch_assoc($produkQuery)) {
        $pdf->Cell(60, 10, $produk['nama_produk'], 1);
        $pdf->Cell(30, 10, number_format($produk['price'], 0, ',', '.'), 1);
        $pdf->Cell(30, 10, $produk['amount'], 1);
        $pdf->Cell(40, 10, number_format($produk['subtotal'], 0, ',', '.'), 1);
        $pdf->Ln();
        $total += $produk['subtotal'];
    }

    // Total
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(120, 10, 'Total', 1);
    $pdf->Cell(40, 10, number_format($total, 0, ',', '.'), 1);
    $pdf->Ln(20);

    // Logo di bagian bawah
    $pdf->Image('../images/sehat.png', 10, $pdf->GetY(), 30); // Ganti path sesuai lokasi logo


    // Tambahkan detail lain tentang invoice di sini
    $filePath = 'invoices/invoice_' . $order_number . '.pdf'; // Path untuk menyimpan invoice
    $pdf->Output('F', $filePath); // Menyimpan PDF ke server

    // Mengirim email dengan invoice terlampir
    $mail = new PHPMailer(true);
    try {
        // Konfigurasi server
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];  // Ganti dengan email Anda
        $mail->Password = $_ENV['SMTP_PASS'];  // Ganti dengan password Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int) $_ENV['SMTP_PORT'];

        // Pengaturan penerima
        $mail->setFrom($_ENV['SMTP_USER'], 'SEHATOOLS');
        $mail->addAddress($to); // Tambahkan penerima

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = 'Invoice Pesanan Anda';
        $mail->Body = 'Terima kasih telah berbelanja! Invoice Anda terlampir.';
        $mail->addAttachment($filePath); // Lampirkan file PDF

        $mail->send();
    } catch (Exception $e) {
        echo "Email tidak dapat dikirim. Kesalahan: {$mail->ErrorInfo}";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>
    <link rel="stylesheet" href="ho.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <div class="home">
        <div class="navigation">
            <div class="logo">
                <img src="../images/logo.png" alt="">
            </div>
            <ul>
                <li>
                    <a href="home.php" class="nav">
                        <span class="icon">
                            <i class="fa-solid fa-gauge-high"></i>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li class="data">
                    <div class="nav">
                        <span class="icon">
                            <i class="fa-solid fa-database"></i>
                        </span>
                        <span class="title">
                            Data
                            <i class="fa-solid fa-chevron-down chevron-toggle-nav"></i>
                        </span>
                    </div>
                    <div class="list" style="display: none;">
                        <ul>
                            <li><a href="tampilAdmin.php">Data Admin</a></li>
                            <li><a href="tampilPelanggan.php">Data Pelanggan</a></li>
                            <li><a href="tampilKategori.php">Data Kategori</a></li>
                            <li><a href="tampilOrder.php">Data Pesanan</a></li>
                            <li><a href="tampilProduk.php">Data Produk</a></li>
                        </ul>
                    </div>
                </li>
                <li class="edit">
                    <div class="nav-edit">
                        <span class="icon-edit">
                            <i class="fa-solid fa-pen-clip"></i>
                        </span>
                        <span class="title-edit">
                            Edit dan Hapus Data
                            <i class="fa-solid fa-chevron-down chevron-toggle-nav-edit"></i>
                        </span>
                    </div>
                    <div class="list-edit" style="display: none;">
                        <ul>
                            <li><a href="ubahAdmin.php">Data Admin</a></li>
                            <li><a href="ubahPelanggan.php">Data Pelanggan</a></li>
                            <li><a href="ubahKategori.php">Data Kategori</a></li>
                            <li><a href="ubahOrder.php">Data Pesanan</a></li>
                            <li><a href="ubahProduk.php">Data Produk</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <div class="content">
            <div class="title-page">
                <h1>Form Ubah Data Pesanan</h1>
                <div class="admin">
                    <p><?php echo $dataAdmin['username']; ?></p>
                    <div class="icon-admin">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <i class="fa-solid fa-chevron-down chevron-toggle"></i>
                    <div class="dropdown-menu" style="display: none;">
                        <a href="logout.php">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
            <div class="form">
                <form action="" method="post">
                    <fieldset>
                        <table>
                            <tr>
                                <td>Id Pelanggan</td>
                                <td> : </td>
                                <td><input type="text" name="id_cust" value="<?php echo $data['id_customer']; ?>" disabled></td>
                            </tr>
                            <tr>
                                <td>Tanggal Pesanan</td>
                                <td> : </td>
                                <td><input type="datetime" name="order_date" value="<?php echo $data['order_date']; ?>" required></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td> : </td>
                                <td><input type="text" name="total" value="<?php echo $data['total']; ?>" required></td>
                            </tr>
                            <tr>
                                <td>Metode Pembayaran</td>
                                <td> : </td>
                                <td>
                                    <select name="payment_method" required>
                                        <option value="transfer" <?php echo ($data['payment_method'] == "Menunggu Pembayaran") ? 'selected' : ''; ?>>Transfer</option>
                                        <option value="cah_on_delivery" <?php echo ($data['payment_method'] == "Diproses") ? 'selected' : ''; ?>>Cahs On Delivery</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td> : </td>
                                <td>
                                    <select name="status" required>
                                        <option value="Menunggu Pembayaran" <?php echo ($data['status'] == "Menunggu Pembayaran") ? 'selected' : ''; ?>>Menunggu Pembayaran</option>
                                        <option value="Diproses" <?php echo ($data['status'] == "Diproses") ? 'selected' : ''; ?>>Diproses</option>
                                        <option value="Dikirim" <?php echo ($data['status'] == "Dikirim") ? 'selected' : ''; ?>>Dikirim</option>
                                        <option value="Selesai" <?php echo ($data['status'] == "Selesai") ? 'selected' : ''; ?>>Selesai</option>
                                        <option value="Dibatalkan" <?php echo ($data['status'] == "Dibatalkan") ? 'selected' : ''; ?>>Dibatalkan</option>
                                    </select>
                                </td>
                            <tr>
                                <td>Nomor Pesanan</td>
                                <td> : </td>
                                <td><input type="text" name="order_number" value="<?php echo $data['order_number']; ?>" required></td>
                            </tr>
                            <tr>
                                <td>Kode Unik</td>
                                <td> : </td>
                                <td><input type="text" name="unique_code" value="<?php echo $data['unique_code']; ?>" required></td>
                            </tr>
                            <tr></tr>
                            <tr>
                                <td>
                                    <input type="submit" value="Simpan" name="proses">
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </form>

                <?php
                if (isset($_POST['proses'])) {
                    $query = mysqli_query($koneksi, "UPDATE orders SET
        order_date = '$_POST[order_date]',
        total = '$_POST[total]',
        payment_method = '$_POST[payment_method]',
        status = '$_POST[status]',
        order_number = '$_POST[order_number]',
        unique_code = '$_POST[unique_code]' 
        WHERE id_order = '$_GET[kode]'");

                    // kirim email status apapun
                    sendEmailStatus(
                        $data['email'],
                        $data['name_customer'],
                        $_POST['order_number'],
                        $_POST['status'],
                        $_POST['payment_method'],
                        $_POST['total']
                    );

                    // kalau status selesai, sekalian kirim invoice
                    if ($_POST['status'] == "Selesai") {
                        sendEmailWithInvoice($data['email'], $_POST['order_number'], $data, $koneksi);
                    }

                    echo "<script> alert('Ubah data berhasil!'); window.location='tampilOrder.php';</script>";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // JavaScript untuk toggle dropdown dan rotasi ikon
        document.querySelector('.chevron-toggle').addEventListener('click', function() {
            const dropdown = document.querySelector('.dropdown-menu');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';

            // Toggle class rotate pada ikon chevron
            this.classList.toggle('rotate');
        });

        // Menyembunyikan dropdown saat klik di luar area dropdown
        window.addEventListener('click', function(e) {
            const adminDiv = document.querySelector('.admin');
            const dropdown = document.querySelector('.dropdown-menu');
            const chevron = document.querySelector('.chevron-toggle');

            if (!adminDiv.contains(e.target)) {
                dropdown.style.display = 'none';
                chevron.classList.remove('rotate');
            }
        });

        document.querySelector('.chevron-toggle-nav').addEventListener('click', function() {
            const dropdown = document.querySelector('.list');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';

            // Toggle class rotate pada ikon chevron
            this.classList.toggle('rotate');
        });

        // Menyembunyikan dropdown saat klik di luar area dropdown
        window.addEventListener('click', function(e) {
            const data = document.querySelector('.data');
            const list = document.querySelector('.list');
            const chevron = document.querySelector('.chevron-toggle-nav');

            if (!data.contains(e.target)) {
                list.style.display = 'none';
                chevron.classList.remove('rotate');
            }
        });

        document.querySelector('.chevron-toggle-nav-edit').addEventListener('click', function() {
            const dropdown = document.querySelector('.list-edit');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';

            // Toggle class rotate pada ikon chevron
            this.classList.toggle('rotate');
        });

        // Menyembunyikan dropdown saat klik di luar area dropdown
        window.addEventListener('click', function(e) {
            const data = document.querySelector('.edit');
            const list = document.querySelector('.list-edit');
            const chevron = document.querySelector('.chevron-toggle-nav-edit');

            if (!data.contains(e.target)) {
                list.style.display = 'none';
                chevron.classList.remove('rotate');
            }
        });
    </script>
</body>

</html>