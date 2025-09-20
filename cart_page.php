<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/shortcut.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="style/cart_pag.css">
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
</body>

<?php
session_start();
include "connect/connection.php";

// Periksa apakah session cart ada dan tidak kosong
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='empty-cart-container'>
            <h2>Keranjang Anda kosong</h2>
            <a href='index.php' class='back-to-shop'>Lihat Produk</a>
          </div>";
    exit;
}

// Daftar produk yang ada di keranjang
$cart = $_SESSION['cart'];
$product_ids = array_keys($cart);

// Cek apakah ada product_ids yang valid
$product_ids = array_filter($product_ids, 'is_numeric');

if (!empty($product_ids)) {
    $ids_string = implode(',', $product_ids);
    $query = "SELECT * FROM products WHERE id_product IN ($ids_string)";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<form id='cart-form'>";
        echo "<table>";
        echo "<tr>
                <th><input type='checkbox' id='select-all'> Pilih Semua</th>
                <th colspan='2'>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Hapus</th>
              </tr>";

        while ($product = mysqli_fetch_assoc($result)) {
            $product_id = $product['id_product'];
            $product_name = $product['name_product'];
            $product_price = $product['price'];
            $product_image = $product['link_image'];
            $formatted_price = number_format($product_price, 0, ',', '.');
            $quantity = $cart[$product_id];
            $subtotal = $product_price * $quantity;

            echo "<tr>";
            echo "<td class='select'>
                    <input type='checkbox' class='product-select' 
                           data-price='$product_price' 
                           data-id='$product_id'>
                  </td>";
            echo "<td><img src='$product_image' alt='$product_name' style='width: 50px; height: auto;'></td>";
            echo "<td class='name-product'>$product_name</td>";
            echo "<td>Rp$formatted_price</td>";
            echo "<td class='quantity-controls'>
                    <button type='button' class='decrease' data-id='$product_id'>
                        <i class='ri-checkbox-indeterminate-line'></i>
                    </button>
                    <span class='quantity' id='quantity-$product_id'>$quantity</span>
                    <button type='button' class='increase' data-id='$product_id'>
                        <i class='ri-add-box-line'></i>
                    </button>
                  </td>";
            echo "<td>Rp<span class='subtotal' id='subtotal-$product_id'>" . number_format($subtotal, 0, ',', '.') . "</span></td>";
            echo "<td><button class='delete-item' data-id='$product_id'>Hapus</button></td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</form>";
        echo "<div id='total-container'>
                <span>Total: Rp<span id='total-price'>0</span></span>
                <button id='buy-now' disabled>Beli Sekarang</button>
              </div>";
    } else {
        echo "<div class='empty-cart-container'>
                <h2>Keranjang Anda kosong</h2>
                <a href='index.php' class='back-to-shop'>Lihat Produk</a>
              </div>";
    }
} else {
    echo "<div class='empty-cart-container'>
            <h2>Keranjang Anda kosong</h2>
            <a href='index.php' class='back-to-shop'>Lihat Produk</a>
          </div>";
}
?>


</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buyNowButton = document.getElementById('buy-now');

        // Hitung total
        function updateTotal() {
            let totalPrice = 0;
            document.querySelectorAll('.product-select:checked').forEach(function(selectedCheckbox) {
                const productId = selectedCheckbox.getAttribute('data-id');
                const subtotal = parseFloat(
                    document.getElementById('subtotal-' + productId).textContent.replace(/\./g, '')
                );
                totalPrice += subtotal;
            });
            document.getElementById('total-price').textContent = totalPrice.toLocaleString('id-ID');
            buyNowButton.disabled = totalPrice === 0;
        }

        // Update subtotal produk
        function updateSubtotal(productId, newQuantity, productPrice) {
            const newSubtotal = productPrice * newQuantity;
            document.getElementById('subtotal-' + productId).textContent = newSubtotal.toLocaleString('id-ID');
        }

        // Checkbox pilih produk
        document.querySelectorAll('.product-select').forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });

        // Tombol tambah/kurang jumlah
        document.querySelectorAll('.increase').forEach(button => {
            button.addEventListener('click', function() {
                const productId = button.getAttribute('data-id');
                let quantityElement = document.getElementById('quantity-' + productId);
                let quantity = parseInt(quantityElement.textContent);
                const productPrice = parseFloat(
                    button.closest('tr').querySelector('.product-select').getAttribute('data-price')
                );

                quantity++;
                quantityElement.textContent = quantity;
                updateSubtotal(productId, quantity, productPrice);
                updateTotal();
            });
        });

        document.querySelectorAll('.decrease').forEach(button => {
            button.addEventListener('click', function() {
                const productId = button.getAttribute('data-id');
                let quantityElement = document.getElementById('quantity-' + productId);
                let quantity = parseInt(quantityElement.textContent);
                const productPrice = parseFloat(
                    button.closest('tr').querySelector('.product-select').getAttribute('data-price')
                );

                if (quantity > 1) {
                    quantity--;
                    quantityElement.textContent = quantity;
                    updateSubtotal(productId, quantity, productPrice);
                    updateTotal();
                }
            });
        });

        // Tombol hapus produk
        document.querySelectorAll('.delete-item').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-id');
                if (!confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) return;

                // hapus baris di tabel
                this.closest('tr').remove();
                updateTotal();
            });
        });

        // Tombol Beli Sekarang
        buyNowButton.addEventListener('click', function() {
            if (!buyNowButton.disabled) {
                const selectedProducts = [];
                document.querySelectorAll('.product-select:checked').forEach(checkbox => {
                    const productId = checkbox.getAttribute('data-id');
                    const quantity = document.getElementById(`quantity-${productId}`).textContent;
                    selectedProducts.push({
                        id: productId,
                        quantity: quantity
                    });
                });

                fetch('add_to_checkout.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(selectedProducts)
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.href = 'checkout.php';
                        }
                    });
            }
        });

        // Checkbox pilih semua
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                document.querySelectorAll('.product-select').forEach(cb => {
                    cb.checked = isChecked;
                });
                updateTotal();
            });
        }

        // Inisialisasi awal
        updateTotal();
    });
</script>