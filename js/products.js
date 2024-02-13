//list product
// fetch("http://localhost:3000/products")
// .then(res => res.json())
// .then(data => renderDataToContent(data));

// function renderDataToContent(products) {
//     let content = document.getElementById("list-products");
//     for (product of products) {
//         let starsHTML = '';
//         for (let i = 0; i < 5; i++) {
//             if (i < product.rate) {
//                 starsHTML += '<i class="ri-star-fill"></i>';
//             } else {
//                 starsHTML += '<i class="ri-star-line"></i>';
//             }
//         }
//         content.innerHTML += 
//             `<div class="list-box">
//                 <div class="img-box">
//                     <img src=${product.url_image} alt="">
//                     <span class="price">$${product.price}</span>
//                     <div class="hover">
//                         <p>Click name product to see details</p>
//                     </div>
//                 </div>
//                 <div class="stars">
//                     ${starsHTML}
//                 </div>
//                 <a href="product/chocoChipCookies.html">
//                     <div class="text">
//                         <span class="name">${product.name}</span>
//                     </div>
//                 </a>
//                 <div class="button-shop">
//                     <a href="#" class="cart"><i class="ri-shopping-cart-2-line"></i></a>
//                     <a href="#" class="button">Order Now</a>
//                 </div>
//             </div>`;
//     }
// }

//display products by sort
document.addEventListener("DOMContentLoaded", function () {
    fetch("http://localhost:3000/products")
    .then(res => res.json())
    .then(data => renderDataToContent(data));
    //choose sort
    const sortElements = document.querySelectorAll('.sort > div');
    sortElements.forEach(element => {
        element.addEventListener('click', async function () {
            // Remove 'selected' class from all sort elements
            sortElements.forEach(el => el.removeAttribute('id'));

            // Add 'selected' class to the clicked sort element
            // element.classList.add('selected');
            element.setAttribute('id', 'selectedSort');
            switch (element.className) {
                case 'cheapest':
                    fetch("http://localhost:3000/products-cheapest")
                    .then(res => res.json())
                    .then(data => renderDataToContent(data));

                    break;
                case 'priciest':
                    fetch("http://localhost:3000/products-priciest")
                    .then(res => res.json())
                    .then(data => renderDataToContent(data));

                    break;
                case 'a-z':
                    fetch("http://localhost:3000/products-name-asc")
                    .then(res => res.json())
                    .then(data => renderDataToContent(data));

                    break;
                case 'z-a':
                    fetch("http://localhost:3000/products-name-des")
                    .then(res => res.json())
                    .then(data => renderDataToContent(data));

                    break;
                case 'recomended':
                    fetch("http://localhost:3000/products-recomended")
                    .then(res => res.json())
                    .then(data => renderDataToContent(data));

                    break;
                default:
            }
        });
    });
    function renderDataToContent(products) {
        let content = document.getElementById("list-products");
        content.innerHTML = '';
        for (product of products) {
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
});
