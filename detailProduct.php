<?php 
    include "connect/connection.php";

    $sql = mysqli_query($koneksi, "SELECT * FROM products where id_product = '$_GET[kode]'");
    $data = mysqli_fetch_array($sql);

    $unique_items = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/favicon3.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="style/detailProducts.css">
    <title>Detail Produk</title>
</head>
<body>
    <header>
        <ul class="nav-details">
            <li><a href="index.php"><i class="ri-arrow-left-line"></i></a></li>
            <li>Details</li>
        </ul>
        <div class="box-menu" id="menu-icon"><i class="ri-menu-line"></i></div>
    </header>
    <div class="prod" id="prod">
        <div class="img-prod">
            <div class="images">
                <img src="<?php echo $data['link_image']; ?>" alt="">
                <div class="hover-prod">
                    <a href="<?php echo $data['link_image']; ?>" target="_blank"><i class="ri-zoom-in-line"></i></i></a>
                </div>
            </div>
            <div class="price-star-button">
                <div class="price-star">
                    <span>Rp<?php echo $data['price']; ?>
                    <br>
                    <!-- <div class="stars">
                        <?php
                            // Menghitung jumlah bintang penuh
                            $fullStars = floor($data['stars']);

                            // Mengecek apakah ada setengah bintang
                            $halfStar = ($data['stars'] - $fullStars >= 0.5) ? true : false;

                            // Menampilkan bintang penuh
                            for ($i = 0; $i < $fullStars; $i++) {
                                echo '<i class="ri-star-fill"></i>';
                            }

                            // Menampilkan setengah bintang jika ada
                            if ($halfStar) {
                                echo '<i class="ri-star-half-line"></i>';
                            }

                            // Menampilkan bintang kosong untuk sisa dari total 5 bintang
                            $totalDisplayed = $fullStars + ($halfStar ? 1 : 0);
                            for ($i = $totalDisplayed; $i < 5; $i++) {
                                echo '<i class="ri-star-line"></i>';
                            }
                            ?>
                        </div>
                    </span> -->
                </div>
                <div class="button-shop">
                    <a href="#" class="cart" data-product-id="<?php echo $data['id_product']; ?>">Masukkan Keranjang</i></a>
                    <button id="buy-now" class="button">Beli Sekarang</button>
                </div>
            </div>
        </div> 
        <div class="text-prod">
            <h1><?php echo $data['name_product']; ?></h1>
            
            <div class="desc">
                <p><strong>Deskripsi Produk</strong></p>
                <div class="useful">
                    <p>
                        <?php echo $data['description_product']; ?>
                    </p>
                </div>
                <p><strong>Komposisi</strong></p>
                <div class="useful">
                    <p>
                        <?php echo $data['composition_product']; ?>
                    </p>
                </div>
                <p><strong>Petunjuk Pemakaian</strong></p>
                <div class="useful">
                    <p>
                        <?php echo $data['usage_instruction']; ?>
                    </p>
                </div>
                <p><strong>Petunjuk Penyimpanan</strong></p>
                <div class="useful">
                    <p>
                        <?php echo $data['storage_instruction']; ?>
                    </p>
                </div>
            </div>
        </div>
    </div> 

    <div class="cart-shop">
        <i class="ri-shopping-cart-2-fill" title="Masukkan ke keranjang"></i>
        <a href="cart_page.php"><span id="cart-count"><?php echo $unique_items; ?></span></a>
    </div>

    <script>
        document.querySelectorAll('.cart').forEach(function(cartButton) {
            cartButton.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah perilaku default link

                var productId = this.getAttribute('data-product-id'); // Ambil ID produk
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_to_cart.php', true); // Request ke 'add_to_cart.php'
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Update elemen cart count di frontend dengan unique items
                        document.getElementById('cart-count').textContent = xhr.responseText;

                        // Tampilkan pesan kilat
                        var flashMessage = document.getElementById('flash-message');
                        flashMessage.style.display = 'block'; // Tampilkan pesan kilat
                        flashMessage.textContent = 'Produk berhasil ditambahkan ke keranjang'; // Ubah pesan jika diperlukan

                        // Hilangkan pesan setelah 3 detik
                        setTimeout(function() {
                            flashMessage.style.display = 'none';
                        }, 3000);
                    }
                };
                xhr.send('product_id=' + productId); // Kirim ID produk ke server
            });
        });
        document.querySelectorAll('#buy-now').forEach(function(buyButton) {
            buyButton.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah reload halaman

                // Ambil ID produk dari atribut data
                var productId = this.closest('.prod').querySelector('.cart').getAttribute('data-product-id');
                
                // Kirim ID produk ke server untuk disimpan di session
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_to_selected.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log(xhr.responseText); // Debugging untuk memastikan respon dari server
                        window.location.href = 'checkout.php'; // Arahkan pengguna ke halaman checkout
                    }
                };
                xhr.send('product_id=' + productId + '&quantity=1'); // Kirim data produk ke server
            });
        });
    </script>
</body>
</html>