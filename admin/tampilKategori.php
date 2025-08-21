<?php 
    include "../connect/connection.php";

    $data_admin = mysqli_query($koneksi, "SELECT * FROM admin");
    $data = mysqli_fetch_assoc($data_admin);

    if(isset($_GET['kode'])&&$_GET['aksi']=="hapus"){
        mysqli_query($koneksi, "DELETE FROM category WHERE id_category = '$_GET[kode]'");
        header("Location: tampilKategori.php");
    }
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
                    <h1>Data Kategori</h1>
                    <div class="admin">
                        <p><?php echo $data['username']; ?></p>
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
                <div class="tabelHasil">
                    <div class="table-wrapper">
                        <table border=1>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Id Kategori</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    $ambil = mysqli_query($koneksi, "SELECT * FROM category");
                                    while ($tampil = mysqli_fetch_array($ambil)){
                                        echo "
                                            <tr>
                                                <td>$no</td>
                                                <td>$tampil[id_category]</td>
                                                <td>$tampil[name_category]</td>
                                                <td>$tampil[description]</td>
                                            </tr>";
                                        $no++;
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <a href="tambahKategori.php">
                <button class="add-float">
                    <i class="fa-solid fa-circle-plus fa-3x"></i>
                    <span>Tambah Kategori</span>
                </button>
            </a>

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
    </html>