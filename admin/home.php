<?php 
    include "../connect/connection.php";
    include "auth.php";
    $data_produk = mysqli_query($koneksi, "SELECT * FROM products");
    $data_pelanggan = mysqli_query($koneksi, "SELECT * FROM customers");
    $data_admin = mysqli_query($koneksi, "SELECT * FROM admin");
    $data_kategori = mysqli_query($koneksi, "SELECT * FROM category");
    $data_penjualan = mysqli_query($koneksi, "SELECT * FROM orders");
    $jml_produk = mysqli_num_rows($data_produk);
    $jml_pelanggan = mysqli_num_rows($data_pelanggan);
    $jml_admin = mysqli_num_rows($data_admin);
    $jml_kategori = mysqli_num_rows($data_kategori);
    $jml_penjualan = mysqli_num_rows($data_penjualan);

    $data = mysqli_fetch_assoc($data_admin);

    $query_grafik = "
        SELECT od.id_product, COUNT(*) AS jumlah_beli, p.name_product
        FROM orderdetails od
        JOIN products p ON od.id_product = p.id_product
        GROUP BY od.id_product, p.name_product
        ORDER BY jumlah_beli DESC
        LIMIT 5
    ";


    $result_grafik = mysqli_query($koneksi, $query_grafik);

    // Mengambil hasil query ke dalam array PHP
    $products = [];
    $jumlah_beli = [];
    while ($row = mysqli_fetch_assoc($result_grafik)) {
        $product_name = strlen($row['name_product']) > 20 ? substr($row['name_product'], 0, 20) . '...' : $row['name_product'];
        $products[] = $product_name;
        $jumlah_beli[] = (int)$row['jumlah_beli'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="hom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"/>
    <link rel="icon" type="image/png" href="../images/shortcut.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <h1>Dashboard</h1>
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
            <div class="cards">
                <div class="card-single">
                    <div>
                        <span class="fa-solid fa-people-group"></span>
                    </div>
                    <div>
                        <h2><?php echo $jml_pelanggan; ?></h2>
                        <small> Pelanggan </small>
                    </div>
                </div>
                <div class="card-single">
                    <div>
                        <span class="fa-solid fa-flask-vial"></span>
                    </div>
                    <div>
                        <h2> <?php echo $jml_produk; ?></h2>
                        <small> Jumlah Produk </small>
                    </div>
                </div>
                <div class="card-single">
                    <div>
                        <span class="fa-solid fa-file-circle-check"></span>
                    </div>
                    <div>
                        <h2> <?php echo $jml_kategori; ?></h2>
                        <small> Kategori </small>
                    </div>
                </div>
                <div class="card-single">
                    <div>
                        <span class="fa-solid fa-person"></span>
                    </div>
                    <div>
                        <h2> <?php echo $jml_admin; ?></h2>
                        <small> Admin </small>
                    </div>
                </div>
                <div class="card-single">
                    <div>
                        <span class="fa-solid fa-folder-open"></span>
                    </div>
                    <div>
                        <h2> <?php echo $jml_penjualan; ?></h2>
                        <small> Penjualan </small>
                    </div>
                </div>
            </div>
            <h2>Grafik</h2>
            <div class="graphics">
                <div class="graphic">
                    <canvas id="chartProduct"></canvas>
                </div>
                <div class="column">
                    <div class="graphic">
                        <canvas id="chartOrder"></canvas>
                    </div>
                    <div class="graphic">
                        <canvas id="chartStatus"></canvas>
                    </div>
                </div>
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

        const labels = <?php echo json_encode($products); ?>;
        const data = <?php echo json_encode($jumlah_beli); ?>;

        // Membuat grafik batang menggunakan Chart.js
        const ctx = document.getElementById('chartProduct').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pembelian',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Top 5 Produk Paling Banyak Dibeli',
                        font: {
                            size: 18,
                            family: 'Quicksand, sans-serif'
                        }
                    }
                }
            },
        });

        const orderLabels = <?php 
            $order_data = mysqli_query($koneksi, "SELECT DATE(order_date) AS tanggal, COUNT(*) AS jumlah_order FROM orders GROUP BY DATE(order_date) ORDER BY tanggal");
            $dates = [];
            $orders = [];
            while ($row = mysqli_fetch_assoc($order_data)) {
                $dates[] = $row['tanggal'];
                $orders[] = $row['jumlah_order'];
            }
            echo json_encode($dates); 
        ?>;
        const orderData = <?php echo json_encode($orders); ?>;

        // Membuat grafik jumlah order (line chart)
        const ctxOrder = document.getElementById('chartOrder').getContext('2d');
        new Chart(ctxOrder, {
            type: 'line', // Tipe grafik adalah line
            data: {
                labels: orderLabels,
                datasets: [{
                    label: 'Jumlah Penjualan',
                    data: orderData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3 // Membuat garis grafik lebih halus
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Tren Penjualan Berdasarkan Tanggal',
                        font: {
                            size: 18,
                            family: 'Quicksand, sans-serif'
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal',
                            font: {
                                weight: 800,
                                family: 'Quicksand, sans-serif',
                                size: 14
                            }
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Penjualan',
                            font: {
                                weight: 800,
                                family: 'Quicksand, sans-serif',
                                size: 14
                            }
                        }
                    }
                }
            }
        });

        const orderLabelsStatus = <?php 
            $order_dataStatus = mysqli_query($koneksi, "SELECT status, COUNT(*) AS jumlah_order FROM orders GROUP BY status ORDER BY status");
            $statuses = [];
            $ordersStatus = [];
            while ($rowStatus = mysqli_fetch_assoc($order_dataStatus)) {
                $statuses[] = $rowStatus['status'];
                $ordersStatus[] = $rowStatus['jumlah_order'];
            }
            echo json_encode($statuses); 
        ?>;
        const orderDataStatus = <?php echo json_encode($ordersStatus); ?>;

        // Membuat grafik jumlah order (line chart)
        const ctxOrderStatus = document.getElementById('chartStatus').getContext('2d');
        new Chart(ctxOrderStatus, {
            type: 'line', // Tipe grafik adalah line
            data: {
                labels: orderLabelsStatus,
                datasets: [{
                    label: 'Jumlah Penjualan',
                    data: orderDataStatus,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3 // Membuat garis grafik lebih halus
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Tren Penjualan Berdasarkan Status',
                        font: {
                            size: 18,
                            family: 'Quicksand, sans-serif'
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Status',
                            font: {
                                weight: 800,
                                family: 'Quicksand, sans-serif',
                                size: 14
                            }
                        },
                        ticks: {
                            maxRotation: 30,
                            minRotation: 30
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Penjualan',
                            font: {
                                weight: 800,
                                family: 'Quicksand, sans-serif',
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>