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
        <link rel="stylesheet" href="hom.css">
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
                    <h1>Form Tambah Data Produk</h1>
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
                                    <td>Nama Produk</td>
                                    <td> : </td>
                                    <td><input type="text" name="name_prod" required></td>
                                </tr>
                                <tr>
                                    <td>Kategori</td>
                                    <td> : </td>
                                    <td>
                                        <select name="category" required>
                                            <option></option>
                                            <?php
                                                $impor_data = mysqli_query($koneksi, "SELECT * FROM category");
                                                while($data_from = mysqli_fetch_array($impor_data)){
                                                    echo "<option value='{$data_from['id_category']}'>{$data_from['id_category']}-{$data_from['name_category']}</option>";
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Harga</td>
                                    <td> : </td>
                                    <td><input type="text" name="price" required></td>
                                </tr>
                                <tr>
                                    <td>Kuantitas</td>
                                    <td> : </td>
                                    <td><input type="text" name="quantity" required></td>
                                </tr>
                                <tr>
                                    <td>Link Gambar</td>
                                    <td> : </td>
                                    <td><input type="text" name="link_img" required></td>
                                </tr>
                                <tr>
                                    <td>Deskripsi Produk</td>
                                    <td> : </td>
                                    <td><input type="text" name="prod_desc" required></td>
                                </tr>
                                <tr>
                                    <td>Komposisi</td>
                                    <td> : </td>
                                    <td><input type="text" name="composition" required></td>
                                </tr>
                                <tr>
                                    <td>Petunjuk Penggunaan</td>
                                    <td> : </td>
                                    <td><input type="text" name="usage" required></td>
                                </tr>
                                <tr>
                                    <td>Petunjuk Penyimpanan</td>
                                    <td> : </td>
                                    <td><input type="text" name="storage" required></td>
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
                            mysqli_query($koneksi, "INSERT INTO products set
                            name_product = '$_POST[name_prod]',
                            category = '$_POST[category]',
                            price = '$_POST[price]',
                            quantity = '$_POST[quantity]',
                            description_product = '$_POST[prod_desc]',
                            link_image = '$_POST[link_img]',
                            composition_product = '$_POST[composition]',
                            storage_instruction = '$_POST[storage]',
                            usage_instruction = '$_POST[usage]'");

                            $status = "berhasil";
                        }

                        if($status == "berhasil"){
                            echo "<script> alert('Tambah data berhasil!'); window.location='tampilProduk.php';</script>";
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