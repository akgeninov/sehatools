const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector(".pass");

togglePassword.addEventListener("click", function () {
    // toggle the type attribute
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    
    // toggle the icon
    this.classList.toggle("ri-eye-line");
});

// // prevent form submit
// const form = document.querySelector("form");
// form.addEventListener('submit', function (e) {
//     e.preventDefault();
// });