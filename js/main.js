let isFormTouched =false;
const registerForm = document.querySelector("#registerForm");
const attendanceModalBtn = document.querySelector("#attendanceModalBtn");
const loginModalBtn = document.querySelector("#loginModalBtn");
const registerModalBtn = document.querySelector("#registerModalBtn");
const registerModalBody =  document.querySelector("#registerModalBody");
const loginModalBody =  document.querySelector("#loginModalBody");
const passwordVisiblityTogglers = [...document.querySelectorAll(".bi-eye")];
passwordVisiblityTogglers.forEach(toggler => {
    toggler.addEventListener("click",(event)=>{
         console.log(toggler);
         toggler.previousElementSibling.type==="password"?
         toggler.previousElementSibling.type="text":
         toggler.previousElementSibling.type="password";
         toggler.classList.toggle("opacity-5");
    })
});
function handleLogin(event) {
    event.preventDefault();

    const formData = new FormData(event.target);

    fetch("/login/handler.php", {
        method: "POST",
        credentials: "include",
        body: formData   
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        if(data.status==="success"){
            if(data.role==="user"){
                window.location.href ="/attendance";
                return
            }
            else{
            window.location.href ="/admin";
                return
            }
        }
        else{
            loginModalBody.textContent = data.message;
            loginModalBtn.click();
        }
 
    })
    .catch(err => console.error(err));
}
function handleAttendance(event){
    event.preventDefault();
    fetch("/attendance/handler.php", {
        method: "POST",
        credentials: "include",
        body: new FormData(event.target)   
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        event.target.lastElementChild.disabled=true;
        attendanceModalBtn.click();
    })
    .catch(err => console.error(err));
}
function handleRegister(event){
    event.preventDefault();
 
    Array.from(event.target.children).filter(element => element.tagName !=="BUTTON" && element.tagName !=="INPUT").forEach(i =>{
        const input = i.children[1].children[0];
        const errorElement = i.querySelector(".invalid-feedback");
        console.log(input)
        validateForm(input,errorElement);
    });
fetch("/register/handler.php", {
    method: "POST",
    credentials: "include",
    body: new FormData(event.target)
})
.then(r => r.json())
.then(d => {
    // Handle password warnings
    if(d.status==="error"){
        if(registerModalBody.classList.contains("alert-success")){
            registerModalBody.classList.remove("alert-success");
        }
        registerModalBody.classList.add("alert-warning");
    }
    // Handle success
    else{
        console.log(d)
        if(registerModalBody.classList.contains("alert-warning")){
            registerModalBody.classList.remove("alert-warning");
        }
        registerModalBody.classList.add("alert-success");

        // Optionally reset the form
        event.target.reset();
    }
    // Handle other errors
 

    // Show modal and set message
    registerModalBody.textContent = d.message;
    registerModalBtn.click();
})
.catch(err => {
    // Network or unexpected error
    registerModalBody.classList.remove("alert-success", "alert-warning", "alert-danger");
    registerModalBody.classList.add("alert-danger");
    registerModalBody.textContent = "Something went wrong. Please try again.";
    registerModalBtn.click();
    console.error(err);
});
isFormTouched=true;
}
function validateForm(input, errorElement) {
if(isFormTouched){    
    const hasMinLength = input.value.trim().length >= 8;
    const hasLower     = /[a-z]/.test(input.value.trim());
    const hasUpper     = /[A-Z]/.test(input.value.trim());
    const hasDigit     = /[0-9]/.test(input.value.trim());
    const hasSpecial   = /[!@#$%^&*()_+\-=\[\]{}|;:'",.<>?/~`]/.test(input.value.trim());
    if (input.value.length < 1) {
        errorElement.textContent = "Input must not be empty";
        return;
    } else {
        errorElement.textContent = "";
    }

    if (input.type === "email") {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        !emailRegex.test(input.value)
            ? errorElement.textContent = "Please enter a valid email"
            : errorElement.textContent = "";
        return;
    }
if ((input.type === "password" && input.name === "register_password") || (input.type === "text" && input.name === "register_password")) {

    let nextInput = input.parentElement.parentElement.nextElementSibling.querySelector("input");
    let nextMessageElement = input.parentElement.parentElement.nextElementSibling.querySelector(".invalid-feedback");

    const passwordsMatch = input.value === nextInput.value;

    errorElement.textContent = "";

    if (!hasMinLength) {
        errorElement.textContent += "password must be greater than 7 characters, ";
    }
    if (!hasLower) {
        errorElement.textContent += "password must contain 1 lower case, ";
    }
    if (!hasUpper) {
        errorElement.textContent += "password must contain 1 upper case, ";
    }
    if (!hasDigit) {
        errorElement.textContent += "password must have 1 digit, ";
    }
    if (!hasSpecial) {
        errorElement.textContent += "password must contain 1 special character, ";
    }
    if (!passwordsMatch) {
        errorElement.textContent += "both passwords must match";
    }

 
    if (
        hasMinLength &&
        hasLower &&
        hasUpper &&
        hasDigit &&
        hasSpecial &&
        passwordsMatch
    ) {
        errorElement.textContent="";
        console.log("Password validation passed");
        nextMessageElement.textContent="";
    }
}


if ((input.type === "password" && input.name === "register_confirm_password") || (input.type === "text" && input.name === "register_confirm_password")) {

    let previousInput =
        input.parentElement.parentElement.previousElementSibling.querySelector("input");
    let previousMessageElement = input.parentElement.parentElement.previousElementSibling.querySelector(".invalid-feedback");
 
    const passwordsMatch = input.value === previousInput.value;

    errorElement.textContent = "";

    if (!hasMinLength) {
        errorElement.textContent += "password must be greater than 7 characters, ";
    }

    if (!hasLower) {
        errorElement.textContent += "password must contain 1 lower case, ";
    }

    if (!hasUpper) {
        errorElement.textContent += "password must contain 1 upper case, ";
    }

    if (!hasDigit) {
        errorElement.textContent += "password must have 1 digit, ";
    }

    if (!hasSpecial) {
        errorElement.textContent += "password must contain 1 special character, ";
    }

    if (!passwordsMatch) {
        errorElement.textContent += "both passwords must match";
    }

 
    if (
        hasMinLength &&
        hasLower &&
        hasUpper &&
        hasDigit &&
        hasSpecial &&
        passwordsMatch
    ) {
        errorElement.textContent = "";
        previousMessageElement.textContent="";
        console.log("Confirm password validation passed");
    }
}}

}
    Array.from(registerForm.children).filter(element => element.tagName !=="BUTTON" && element.tagName !=="INPUT").forEach(i =>{
        const input = i.children[1].children[0];
        const errorElement = i.querySelector(".invalid-feedback");
        console.log(input)
        input.addEventListener("input",(e)=>{
            validateForm(input,errorElement);
            // console.log(e.target.value)
        });
                input.addEventListener("blur",(e)=>{
            validateForm(input,errorElement);
            // console.log(e.target.value)
        })
    });