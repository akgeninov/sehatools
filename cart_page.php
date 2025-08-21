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
        echo "<div class='empty-cart-message'>Keranjang belanja Anda kosong</div>";
        echo "<a href='index.php' class='button'>Lihat produk kembali</a>";
        exit;
    }

    // Daftar produk yang dipilih dari sesi (jika ada)
    $selectedProducts = $_SESSION['selected_products'] ?? [];

    // Ambil produk yang ada di keranjang (ID dan jumlah)
    $cart = $_SESSION['cart'];

    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]); // Hapus item dari session cart
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan di keranjang']);
        }
        exit;
    }
    // Untuk menyimpan ID produk yang ada di keranjang
    $product_ids = array_keys($cart);

    if (!empty($product_ids)) {
        // Membuat query SQL dengan ID produk dari keranjang
        $product_ids = array_filter($product_ids, 'is_numeric');
        $ids_string = implode(',', $product_ids);
        $query = "SELECT * FROM products WHERE id_product IN ($ids_string)";
        
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            echo "<form id='cart-form'>";
            echo "<table>";
            echo "<tr><th>Select</th><th colspan='2'>Nama Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th>Hapus</th></tr>";
        
            while ($product = mysqli_fetch_assoc($result)) {
                $product_id = $product['id_product'];
                $product_name = $product['name_product'];
                $product_price = $product['price'];
                $product_image = $product['link_image'];
                $formatted_price = number_format($product_price, 0, ',', '.');
                $quantity = $cart[$product_id]; // Jumlah produk dari session cart
                $subtotal = $product_price * $quantity;
        
                echo "<tr>";
                echo "<td class='select'><input type='checkbox' class='product-select' data-price='$product_price' data-id='$product_id'></td>";
                echo "<td><img src='$product_image' alt='$product_name' style='width: 50px; height: auto;'></td>";
                echo "<td class='name-product'>$product_name</td>";
                echo "<td>Rp$formatted_price</td>";
                echo "<td class='quantity-controls'>
                        <button type='button' class='decrease' data-id='$product_id' title='Kurangi jumlah produk'>
                            <i class='ri-checkbox-indeterminate-line'></i>
                        </button>
                        <span class='quantity' id='quantity-$product_id'>$quantity</span>
                        <button type='button' class='increase' data-id='$product_id' title='Tambah jumlah produk'>
                            <i class='ri-add-box-line'></i>
                        </button>
                    </td>";
                echo "<td>Rp<span class='subtotal' id='subtotal-$product_id'>" . number_format($subtotal, 0, ',', '.') . "</span></td>";
                
                // Tambahkan kolom untuk tombol hapus
                echo "<td><button class='delete-item' data-id='$product_id' title='Hapus produk'>Hapus</button></td>";
                
                echo "</tr>";
            }
        
            echo "</table>";
            echo "</form>";
            echo "<div id='total-container'>
                <span>Total: Rp<span id='total-price'>0</span></span>
                <button id='buy-now' disabled title='Pilih produk dalam keranjang terlebih dahulu'>Beli Sekarang</button>
            </div>";
        } else {
            echo "Gagal mengambil data produk: " . mysqli_error($koneksi);
        }
    }  
?>
</html>

<script>
// JavaScript untuk menghitung total ketika checkbox di klik
document.querySelectorAll('.product-select').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        updateTotal();
    });
});

// Fungsi untuk menghitung total
function updateTotal() {
    let totalPrice = 0;
    document.querySelectorAll('.product-select:checked').forEach(function(selectedCheckbox) {
        const productId = selectedCheckbox.getAttribute('data-id');
        const subtotal = parseFloat(document.getElementById('subtotal-' + productId).textContent.replace(/\./g, ''));
        totalPrice += subtotal;
    });
    document.getElementById('total-price').textContent = totalPrice.toLocaleString('id-ID');
}

// Fungsi untuk memperbarui subtotal
function updateSubtotal(productId, newQuantity, productPrice) {
    const newSubtotal = productPrice * newQuantity;
    document.getElementById('subtotal-' + productId).textContent = newSubtotal.toLocaleString('id-ID');
}

// Event listener untuk tombol tambah kuantitas
document.querySelectorAll('.increase').forEach(function(button) {
    button.addEventListener('click', function() {
        const productId = button.getAttribute('data-id');
        let quantityElement = document.getElementById('quantity-' + productId);
        let quantity = parseInt(quantityElement.textContent);
        const productPrice = parseFloat(button.closest('tr').querySelector('.product-select').getAttribute('data-price'));

        quantity++;
        quantityElement.textContent = quantity;
        
        // Perbarui subtotal dan total
        updateSubtotal(productId, quantity, productPrice);
        updateTotal();
    });
});

// Event listener untuk tombol kurangi kuantitas
document.querySelectorAll('.decrease').forEach(function(button) {
    button.addEventListener('click', function() {
        const productId = button.getAttribute('data-id');
        let quantityElement = document.getElementById('quantity-' + productId);
        let quantity = parseInt(quantityElement.textContent);
        const productPrice = parseFloat(button.closest('tr').querySelector('.product-select').getAttribute('data-price'));

        if (quantity > 1) {
            quantity--;
            quantityElement.textContent = quantity;

            // Perbarui subtotal dan total
            updateSubtotal(productId, quantity, productPrice);
            updateTotal();
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const buyNowButton = document.getElementById('buy-now');
    const checkboxes = document.querySelectorAll('.product-select');
    
    // Fungsi untuk cek apakah ada checkbox yang dipilih
    function updateBuyNowButton() {
        const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        buyNowButton.disabled = !anyChecked;
        buyNowButton.title = buyNowButton.disabled ? 'Pilih produk dalam keranjang terlebih dahulu' : '';
    }

    // Tambahkan event listener untuk setiap checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBuyNowButton);
    });

    // Inisialisasi tombol pada load awal halaman
    updateBuyNowButton();
});

document.addEventListener('DOMContentLoaded', function() {
    const buyNowButton = document.getElementById('buy-now');

    // Reset semua checkbox saat halaman dimuat
    document.querySelectorAll('.product-select').forEach(checkbox => {
        checkbox.checked = false;
    });

    // Perbarui status tombol "Beli Sekarang" sesuai checkbox
    function updateBuyNowButton() {
        const anyChecked = document.querySelectorAll('.product-select:checked').length > 0;
        buyNowButton.disabled = !anyChecked;
    }

    document.querySelectorAll('.product-select').forEach(checkbox => {
        checkbox.addEventListener('change', updateBuyNowButton);
    });

    buyNowButton.addEventListener('click', function () {
        if (!buyNowButton.disabled) {
            const selectedProducts = [];
            document.querySelectorAll('.product-select:checked').forEach(checkbox => {
                const productId = checkbox.getAttribute('data-id');
                const quantity = document.getElementById(`quantity-${productId}`).textContent;
                selectedProducts.push({ id: productId, quantity: quantity });
            });

            fetch('add_to_checkout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(selectedProducts)
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = 'checkout.php';
                }
            });
        }
    });

    // Panggil fungsi untuk inisialisasi tombol saat halaman dimuat
    updateBuyNowButton();
});



document.addEventListener('DOMContentLoaded', function () {
    // Event listener untuk tombol hapus
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');

            // Konfirmasi penghapusan
            if (!confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) return;

            // Kirim request AJAX ke server untuk menghapus item
            fetch('cart_page.php', { // Pastikan path ke cart_page.php benar
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `action=delete&product_id=${productId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Hapus baris produk dari tampilan jika berhasil
                    document.querySelector(`button[data-id="${productId}"]`).closest('tr').remove();

                    // Perbarui total harga
                    updateTotal();
                } else {
                    alert('Gagal menghapus produk dari keranjang');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Fungsi untuk memperbarui total harga
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(subtotal => {
            total += parseInt(subtotal.textContent.replace(/[^0-9]/g, '')) || 0;
        });
        document.getElementById('total-price').textContent = new Intl.NumberFormat('id-ID').format(total);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const selectedProducts = <?php echo json_encode($selectedProducts); ?>;

    // Aktifkan checkbox yang sesuai dengan produk yang sudah terpilih
    selectedProducts.forEach(function (product) {
        const checkbox = document.querySelector(`.product-select[data-id="${product.id}"]`);
        const quantityElem = document.getElementById(`quantity-${product.id}`);
        if (checkbox) {
            checkbox.checked = true;
            // Set quantity dari sesi jika ada
            quantityElem.textContent = product.quantity;
        }
    });

    // Hitung ulang total harga berdasarkan produk yang terpilih
    calculateTotal(); // Panggil calculateTotal() di sini untuk update tombol
});

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.product-select:checked').forEach(checkbox => {
        const price = parseInt(checkbox.getAttribute('data-price'));
        const productId = checkbox.getAttribute('data-id');
        const quantity = parseInt(document.getElementById(`quantity-${productId}`).textContent);
        total += price * quantity;
    });
    
    // Update total price in the UI
    document.getElementById('total-price').textContent = total.toLocaleString('id-ID');

    // Enable or disable the "Beli Sekarang" button based on the total price
    const buyNowButton = document.getElementById('buy-now');
    if (total > 0) {
        buyNowButton.disabled = false; // Enable the button
    } else {
        buyNowButton.disabled = true; // Disable the button
    }
}





</script>
