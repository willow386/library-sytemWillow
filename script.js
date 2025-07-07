const eyeIcon=document.getElementById('eye');

const passwordField=document.getElementById('password');

eyeIcon.addEventListener('click', ()=>{
    if (passwordField.type==="password" && passwordField.value){
        passwordField.type==="text";
        eyeIcon.classList.remove('fa-eye')
        eyeIcon.classList.add('fa-eye-slash')
    }
    else{
        passwordField.type="password";
        eyeIcon.classList.remove('fa-eye-slash')
        eyeIcon.classList.add('fa-eye-slash')
    }
})

// const passwordField = document.getElementById("password");
// const togglePassword = document.querySelector(".password-toggle-icon i");

// togglePassword.addEventListener("click", function () {
//   if (passwordField.type === "password") {
//     passwordField.type = "text";
//     togglePassword.classList.remove("fa-eye");
//     togglePassword.classList.add("fa-eye-slash");
//   } else {
//     passwordField.type = "password";
//     togglePassword.classList.remove("fa-eye-slash");
//     togglePassword.classList.add("fa-eye");
//   }
// });