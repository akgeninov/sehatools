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
