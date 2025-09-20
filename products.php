<?php
session_start();
include "connect/connection.php";
$unique_items = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Menangkap filter sort dan kategori dari request AJAX
$sort = $_GET['sort'] ?? '';
$category = $_GET['category'] ?? '';
$search = mysqli_real_escape_string($koneksi, $_GET['search'] ?? '');

// Query dasar dengan join ke tabel category
// Query dasar
// Query dasar dengan join ke tabel category
$query = "SELECT products.*, category.name_category FROM products 
          JOIN category ON products.category = category.id_category";

$queryRecom = "SELECT od.id_product, COUNT(*) AS jumlah_beli, p.link_image, p.name_product, p.price
                FROM orderdetails od
                JOIN products p ON od.id_product = p.id_product
                GROUP BY od.id_product, p.link_image, p.name_product, p.price
                ORDER BY jumlah_beli DESC";

// Cek apakah ada filter kategori
if ($category) {
    $query .= " WHERE category.name_category = '$category'";
}

if ($search) {
    $query .= (strpos($query, 'WHERE') !== false ? " AND" : " WHERE") . " products.name_product LIKE '%$search%'";
}

// Cek apakah ada filter sorting
if ($sort == "low") {
    $query .= " ORDER BY price ASC";
} elseif ($sort == "high") {
    $query .= " ORDER BY price DESC";
} elseif ($sort == "a-z") {
    $query .= " ORDER BY name_product ASC";
} elseif ($sort == "z-a") {
    $query .= " ORDER BY name_product DESC";
} elseif ($sort == "recomended") {
    $query = $queryRecom;
}

// Eksekusi query
$products = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));


if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
    if (mysqli_num_rows($products) > 0) {
        while ($product = mysqli_fetch_assoc($products)) { ?>
            <div class="list-box">
                <div class="img-box">
                    <img src="<?php echo $product['link_image']; ?>" alt="<?php echo $product['name_product']; ?>">
                    <div class="hover">
                        <p>Klik nama produk untuk detail</p>
                    </div>
                </div>
                <div class="price">Rp<?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                <a href="detailProduct.php?kode=<?php echo $product['id_product']; ?>">
                    <div class="text">
                        <span class="name"><?php echo (strlen($product['name_product']) > 20) ? substr($product['name_product'], 0, 20) . '...' : $product['name_product']; ?></span>
                    </div>
                </a>
                <div class="button-shop">
                    <a href="#" class="cart" data-product-id="<?php echo $product['id_product']; ?>">
                        <i class="ri-shopping-cart-2-line"></i>
                    </a>
                    <button id="buy-now" class="button">Beli Sekarang</button>
                </div>
            </div>
<?php }
    } else {
        echo "<p>Tidak ada produk yang sesuai dengan filter yang dipilih.</p>";
    }
    exit;
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
    <link rel="stylesheet" href="style/allProduct.css">
    <title>Produk</title>
</head>

<body>
    <header>
        <a href="index.php">
            <i class="ri-arrow-left-s-line"></i>
        </a>
        <h1>Produk</h1>
    </header>

    <main>
        <div class="filter">
            <h2>Urutkan</h2>
            <div class="sort" id="sort-by">
                <div class="cheapest" data-sort="low">
                    <p>Harga Rendah</p>
                </div>
                <div class="priciest" data-sort="high">
                    <p>Harga Tinggi</p>
                </div>
                <div class="a-z" data-sort="a-z">
                    <p>A-Z</p>
                </div>
                <div class="z-a" data-sort="z-a">
                    <p>Z-A</p>
                </div>
                <div class="recomended" data-sort="recomended">
                    <p>Rekomendasi</p>
                </div>
            </div>
            <h2>Kategori</h2>
            <div class="sort" id="sort-category">
                <div class="kategori" data-category="Alat Pemantauan Kesehatan">
                    <p>Alat Pemantauan Kesehatan</p>
                </div>
                <div class="kategori" data-category="Alat Perawatan Pribadi">
                    <p>Alat Perawatan Pribadi</p>
                </div>
                <div class="kategori" data-category="Alat Terapi Pernapasan">
                    <p>Alat Terapi Pernapasan</p>
                </div>
                <div class="kategori" data-category="Perlengkapan Kebersihan dan Perlindungan">
                    <p>Perlengkapan Kebersihan dan Perlindungan</p>
                </div>
                <div class="kategori" data-category="Alat Bantu Dengar">
                    <p>Alat Bantu Dengar</p>
                </div>
                <div class="kategori" data-category="Alat Kesehatan Wanita dan Bayi">
                    <p>Alat Kesehatan Wanita dan Bayi</p>
                </div>
                <div class="kategori" data-category="Peralatan Pertolongan Pertama">
                    <p>Peralatan Pertolongan Pertama</p>
                </div>
                <div class="kategori" data-category="Alat Perawatan Kecantikan Medis">
                    <p>Alat Perawatan Kecantikan Medis</p>
                </div>
            </div>
        </div>
        <div class="products" id="list-products">
            <?php
            if (mysqli_num_rows($products) > 0) {
                while ($product = mysqli_fetch_assoc($products)) { ?>
                    <div class="list-box">
                        <div class="img-box">
                            <img src="<?php echo $product['link_image']; ?>" alt="<?php echo $product['name_product']; ?>">
                            <div class="hover">
                                <p>Klik nama produk untuk detail</p>
                            </div>
                        </div>
                        <a href="detailProduct.php?kode=<?php echo $product['id_product']; ?>">
                            <div class="text">
                                <span class="name"><?php echo (strlen($product['name_product']) > 20) ? substr($product['name_product'], 0, 20) . '...' : $product['name_product']; ?></span>
                            </div>
                        </a>
                        <div class="price">Rp<?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                        <div class="button-shop">
                            <a href="#" class="cart" data-product-id="<?php echo $product['id_product']; ?>">
                                <i class="ri-shopping-cart-2-line"></i>
                            </a>
                            <button id="buy-now" class="button">Beli Sekarang</button>
                        </div>
                    </div>
            <?php }
            } else {
                echo "<p>Tidak ada produk yang ditemukan.</p>";
            }
            ?>
        </div>
    </main>

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
        document.addEventListener("DOMContentLoaded", function() {
            let currentSort = '';
            let currentCategory = '';

            // Handle sorting
            document.querySelectorAll("#sort-by div").forEach(function(sortOption) {
                sortOption.addEventListener("click", function() {
                    currentSort = this.getAttribute("data-sort");

                    // Hapus kelas 'active-sort' dari semua elemen sort dan kelas 'active-category' dari semua kategori
                    document.querySelectorAll("#sort-by div").forEach(el => el.classList.remove("active-sort"));
                    document.querySelectorAll("#sort-category .kategori").forEach(el => el.classList.remove("active-category"));

                    // Tambahkan kelas 'active-sort' ke elemen yang dipilih
                    this.classList.add("active-sort");

                    // Hapus pilihan kategori
                    currentCategory = '';

                    filterProducts();
                });
            });

            // Handle categories
            document.querySelectorAll("#sort-category .kategori").forEach(function(categoryOption) {
                categoryOption.addEventListener("click", function() {
                    currentCategory = this.getAttribute("data-category");

                    // Hapus kelas 'active-category' dari semua elemen kategori dan kelas 'active-sort' dari semua sort
                    document.querySelectorAll("#sort-category .kategori").forEach(el => el.classList.remove("active-category"));
                    document.querySelectorAll("#sort-by div").forEach(el => el.classList.remove("active-sort"));

                    // Tambahkan kelas 'active-category' ke elemen yang dipilih
                    this.classList.add("active-category");

                    // Hapus pilihan sort
                    currentSort = '';

                    filterProducts();
                });
            });

            function filterProducts() {
                const xhr = new XMLHttpRequest();
                xhr.open("GET", `?ajax=true&sort=${currentSort}&category=${currentCategory}`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        document.getElementById("list-products").innerHTML = xhr.responseText;
                    }
                };
                xhr.send();
            }
        });

        document.querySelectorAll('.cart').forEach(function(cartButton) {
            cartButton.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah perilaku default link

                var productId = this.getAttribute('data-product-id'); // Ambil ID produk
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_to_cart.php', true); // Request ke 'add_to_cart.php'
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
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