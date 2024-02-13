const menuToggle = document.querySelector('.menu-toggle input');
const nav = document.querySelector('header ul');

menuToggle.addEventListener('click', function(){
  nav.classList.toggle('menu');
});

const inputs = document.querySelectorAll(".input");

function focusFunc(){
    let parent = this.parentNode;
    parent.classList.add("focus");
}
function blurFunc(){
    let parent = this.parentNode;
    if (this.value == "") {
        parent.classList.remove("focus");   
    }
}

inputs.forEach((input) => {
    input.addEventListener("focus", focusFunc);
    input.addEventListener("blur", blurFunc);
});

//section HOME
fetch("http://localhost:3000/products")
.then(res => res.json())
.then(data => renderDataToSlide(data));

function renderDataToSlide(products) {
    let content = document.getElementById("img-home");
    let navigation = document.getElementById("navigation");
    let rate_top = 0;
    for (product of products) {
        if (product.rate == 5) {
            rate_top++;
            if (rate_top <= 5) {
                content.innerHTML += `<img id="slide-${rate_top}" src="${product.url_image}" alt="">`;
                navigation.innerHTML += `<a href="#slide-${rate_top}"></a>`;
            } else {
                break;
            }
        }
    }
}

//section PRODUCTS
fetch("http://localhost:3000/products")
.then(res => res.json())
.then(data => renderDataToContent(data));

function renderDataToContent(products) {
    const limitedProducts = products.slice(0, 6);
    let content = document.getElementById("products");
    for (product of limitedProducts) {
        let starsHTML = '';
        for (let i = 0; i < 5; i++) {
            if (i < product.rate) {
                starsHTML += '<i class="ri-star-fill"></i>';
            } else {
                starsHTML += '<i class="ri-star-line"></i>';
            }
        }
        content.innerHTML += 
            `<div class="list-box">
                <div class="img-box">
                    <img src=${product.url_image} alt="">
                    <span class="price">$${product.price}</span>
                    <div class="hover">
                        <p>Click name product to see details</p>
                    </div>
                </div>
                <div class="stars">
                    ${starsHTML}
                </div>
                <a href="product/chocoChipCookies.html">
                    <div class="text">
                        <span class="name">${product.name}</span>
                    </div>
                </a>
                <div class="button-shop">
                    <a href="#" class="cart"><i class="ri-shopping-cart-2-line"></i></a>
                    <a href="#" class="button">Order Now</a>
                </div>
            </div>`;
    }
}

//section CONTACT
function sendMessage(event) {
    event.preventDefault();
    console.log('sendMessage');
    // Mengambil nilai dari elemen input
    const form = event.target;
    const name = form.querySelector("[name='name']").value;
    const email = form.querySelector("[name='email']").value;
    const subject = form.querySelector("[name='subject']").value;
    const message = form.querySelector("[name='message']").value;
  
    // Membentuk objek data
    const data = {
      name: name,
      email: email,
      subject: subject,
      message: message,
    };
  
    // Validasi: Memeriksa apakah semua input telah diisi
    if (name === "" || email === "" || subject === "" || message === "") {
      alert("Mohon lengkapi semua form sebelum mengirim pesan.");
      return; // Menghentikan pengiriman jika ada input yang kosong
    }
  
    // Mengirim data ke API
    fetch("http://localhost:3000/add-contact", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
      .then(res => res.json())
      .then(data => {
        // Tanggapan dari API dapat digunakan di sini
        console.log(data);
        alert("Pesan Anda berhasil dikirim!");
        form.reset();
      })
      .catch((error) => {
        console.error("Terjadi kesalahan:", error);
        alert("Terjadi kesalahan saat mengirim pesan.");
      });
  }
document.querySelector(".form-box form").addEventListener("submit", sendMessage); 
