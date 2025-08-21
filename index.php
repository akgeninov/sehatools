<?php 
    session_start();
    include "connect/connection.php";
    $unique_items = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

    // session_unset();
    // session_destroy();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/shortcut.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="style/sty.css">
    <title>sehatools</title>
</head>
<body>
    <header>
        <a href="#" class="logo"><img src="images/sehat.png" alt=""></a>
        <div class="search-nav">
        <form action="products.php" method="GET" class="search-form">
            <input type="search" placeholder="Temukan kebutuhanmu" name="search" required>
            <i class="ri-search-line" onclick="this.parentElement.submit();" style="cursor: pointer;"></i>
            <button type="submit" class="submit-button">Cari</button> <!-- Tombol ini tersembunyi -->
        </form>
            <ul>
                <!-- <li><a class="nav" href="#home">Home</a></li> -->
                <li><a class="nav" href="products.php">Produk</a></li>
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
    <section class="home" id="home">
        <!-- <div class="text-home">
            <span>Selamat Datang di</span>
            <h1>sehatools</h1>
            <h2>segala kebutuhan kesehatan<br> tersedia di sini</h2>
            <a href="#" class="button">Beli Sekarang</a>
        </div> -->
        <div class="img-home">
            <h2>Kami Melayani Anda dengan Sepenuh Hati</h2>
            <div class="images" id="img-home">
                <img id="slide-1" src="https://img.freepik.com/free-photo/front-view-young-beautiful-woman-making-heart-shape-with-red-apple-standing-against-white-background_23-2148076223.jpg?t=st=1730209012~exp=1730212612~hmac=d3e938be810865ae8dde9df277eefa7a79c035aaa1006cbd42cc3a2f9d053b0a&w=740" alt="">
                <img id="slide-2" src="https://img.freepik.com/free-photo/front-view-covid-recovery-center-female-doctor-with-medical-mask-checking-elder-patient-s-blood-pressure_23-2148847845.jpg?t=st=1730209262~exp=1730212862~hmac=49cfb30a63a90a019716c0fa6e96d83e3a6e143eafe2e88cbb580346cad4eba9&w=740" alt="">
                <img id="slide-3" src="https://images.pexels.com/photos/29060401/pexels-photo-29060401/free-photo-of-vibrant-health-supplement-bottles-on-white-background.jpeg?auto=compress&cs=tinysrgb&w=600" alt="">
                <img id="slide-4" src="https://images.pexels.com/photos/6320167/pexels-photo-6320167.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="">
                <img id="slide-5" src="https://images.pexels.com/photos/9951390/pexels-photo-9951390.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="">
            </div>
            <div class="navigation" id="navigation">
                <a href="#slide-1"></a>
                <a href="#slide-2"></a>
                <a href="#slide-3"></a>
                <a href="#slide-4"></a>
                <a href="#slide-5"></a>
            </div>
        </div> 
    </section>
    <section class="product" id="product">
        <div class="heading">
            <h1>Rekomendasi Produk</h1>
        </div>
        <div class="products" id="products">
        <?php 
            // Query untuk mengambil data produk dari database
            $ambil = mysqli_query($koneksi, "
                SELECT od.id_product, COUNT(*) AS jumlah_beli, p.link_image, p.name_product, p.price
                FROM orderdetails od
                JOIN products p ON od.id_product = p.id_product
                GROUP BY od.id_product, p.link_image, p.name_product, p.price
                ORDER BY jumlah_beli DESC 
                LIMIT 5
            ");

            // Cek apakah ada hasil
            if (mysqli_num_rows($ambil) > 0) {
                // Loop melalui setiap produk yang diambil dari database
                while ($product = mysqli_fetch_assoc($ambil)) { ?>
                    <div class="list-box">
                        <div class="img-box">
                            <img src="<?php echo $product['link_image']; ?>" alt="<?php echo $product['name_product']; ?>">
                            <div class="hover">
                                <p>Klik nama produk untuk detail</p>
                            </div>
                        </div>
                        <a href="detailProduct.php?kode=<?php echo $product['id_product']; ?>">
                            <div class="text">
                                <span class="name"><?php echo (strlen($product['name_product']) > 20) ? substr($product['name_product'], 0, 20) . '...' : $product['name_product'];  ?></span>
                            </div>
                        </a>
                        <div class="price">Rp<?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                        <!-- <div class="stars">
                            <?php
                                // Menghitung jumlah bintang penuh
                                $fullStars = floor($product['stars']);

                                // Mengecek apakah ada setengah bintang
                                $halfStar = ($product['stars'] - $fullStars >= 0.5) ? true : false;

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
                        </div> -->
                        <div class="button-shop">
                            <a href="#" class="cart" data-product-id="<?php echo $product['id_product']; ?>">
                                <i class="ri-shopping-cart-2-line"></i>
                            </a>
                            <button id="buy-now" class="button">Beli Sekarang</button>
                        </div>
                    </div>
                <?php 
                }
            } else {
                echo "Tidak ada produk yang ditemukan.";
            }
            ?>

        </div>
        <button class="more" onclick="location.href='products.php'">Tampilkan semua produk</button>
    </section>
    <section class="contact" id="contact">
        <div class="heading">
                <h1>Kontak</h1>
            </div>
        <div class="item">
            <div class="img-contact">
                <img src="images/contact-us.png" alt="">
            </div>
            <div class="form-box">
            <form method="POST" action="https://formspree.io/f/mzzbdldz">
                <h3>Kirim Pesan Langsung ke Kami</h3>
                <div class="input-field">
                    <input id="name" type="text" name="name" class="input" required />
                    <label for="">Nama</label>
                    <span>Name</span>
                </div>
                <div class="input-field">
                    <input id="email" type="email" name="email" class="input" required />
                    <label for="">Email</label>
                    <span>Email</span>
                </div>
                <div class="input-field">
                    <input id="subject" type="text" name="subject" class="input" required />
                    <label for="">Subjek</label>
                    <span>Subject</span>
                </div>
                <div class="input-field textarea">
                    <textarea id="message" name="message" class="input" required></textarea>
                    <label for="">Pesan</label>
                    <span>Message</span>
                </div>
                <div class="btn-send">
                    <button type="submit">Kirim</button>
                </div>
            </form>
            </div>
        </div>
    </section>
    <footer>
        <ul class="socmed">
            <li><a href="#"><i class="ri-facebook-circle-fill"></i></a></li>
            <li><a href="#"><i class="ri-instagram-fill"></i></a></li>
            <li><a href="#"><i class="ri-twitter-fill"></i></a></li>
            <li><a href="https://www.linkedin.com/in/akge-ninov" target="_blank"><i class="ri-linkedin-box-fill"></i></a></li>
        </ul>
        <span>&copy;Sehatools</span>
        <br>
        <span class="proj">Personal project</span>
    </footer>

    <div class="cart-shop">
        <i class="ri-shopping-cart-2-fill" title="Masukkan ke keranjang"></i>
        <a href="cart_page.php"><span id="cart-count"><?php echo $unique_items; ?></span></a>
    </div>

    <div id="flash-message" style="display: none; position: fixed; top: 20px; right: 20px; background-color: #4caf50; color: white; padding: 10px; border-radius: 5px; z-index: 1000;">
        Produk ditambahkan ke keranjang
    </div>



    <script>
        document.querySelectorAll('.input-field .input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.classList.add('focus');
            });
            input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.parentNode.classList.remove('focus');
                }
            });
        });

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
                var productId = this.closest('.list-box').querySelector('.cart').getAttribute('data-product-id');
                
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