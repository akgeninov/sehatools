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

function sendMessage(){
    var params = {
        senderName: document.getElementById("name").value,
        senderEmail: document.getElementById("email").value,
        senderSubject: document.getElementById("subject").value,
        senderMessage: document.getElementById("message").value,
    };

    emailjs.send("service_d8z6tu8","template_czp815b", params)
    .then(function (res) {
        alert('Thank You, '+params['senderName']+'! Your message has been sent'+res.status);
    })
}

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
                break; // Stop iterating if 5 images have been added
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

