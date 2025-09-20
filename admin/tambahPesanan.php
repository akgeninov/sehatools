<?php 
    include "../connect/connection.php";

    $data_admin = mysqli_query($koneksi, "SELECT * FROM admin");
    $dataAdmin = mysqli_fetch_assoc($data_admin);
?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Halaman Utama</title>
        <link rel="stylesheet" href="ho.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
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
                <h1>Form Tambah Data Pesanan</h1>
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
                                <td><input type="text" name="id_cust" required></td>
                            </tr>
                            <tr>
                                <td>Tanggal Pesanan</td>
                                <td> : </td>
                                <td><input type="datetime-local" name="date" required></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td> : </td>
                                <td><input type="text" name="total" required></td>
                            </tr>
                            <tr>
                            <td>Metode Pembayaran</td>
                                <td> : </td>
                                <td>
                                    <select name="payment_method" required>
                                        <option value="transfer">Transfer</option>
                                        <option value="cah_on_delivery">Cash On Delivery</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td> : </td>
                                <td>
                                <select name="status" required>
                                    <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                                    <option value="Diproses">Diproses</option>
                                    <option value="Dikirim" >Dikirim</option>
                                    <option value="Selesai" >Selesai</option>
                                    <option value="Dibatalkan">Dibatalkan</option>
                                </select>
                                </td>
                            <tr>
                                <td>Nomor Pesanan</td>
                                <td> : </td>
                                <td><input type="text" name="order_number" required></td>
                            </tr>
                            <tr>
                                <td>Kode Unik</td>
                                <td> : </td>
                                <td><input type="text" name="unique_code" required></td>
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
                    $status = "";
                    if(isset($_POST['proses'])){
                        mysqli_query($koneksi, "INSERT INTO orders set
                        id_customer = '$_POST[id_cust]',
                        order_date = '$_POST[date]',
                        total = '$_POST[total]',
                        payment_method = '$_POST[payment_method]',
                        status = '$_POST[status]',
                        order_number = '$_POST[order_number]',
                        unique_code = '$_POST[unique_code]'");

                        $status = "berhasil";
                    }

                    if($status == "berhasil"){
                        echo "<script> alert('Ubah data berhasil!'); window.location='tampilPesanan.php';</script>";
                    }
                ?>
            </div>
        </div>
    </div>

    <script>
        // JavaScript untuk toggle dropdown dan rotasi ikon
        document.querySelector('.chevron-toggle').addEventListener('click', function () {
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
        
        document.querySelector('.chevron-toggle-nav').addEventListener('click', function () {
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
        
        document.querySelector('.chevron-toggle-nav-edit').addEventListener('click', function () {
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