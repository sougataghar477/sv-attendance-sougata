const attendanceModalBtn = document.querySelector("#attendanceModalBtn");
const loginModalBtn = document.querySelector("#loginModalBtn");
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
        const input = i.children[1];
        const errorElement = i.querySelector(".invalid-feedback");
        validateForm(input,errorElement);
    })
}
function validateForm(input, errorElement) {
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

    if (input.type === "password" && input.name === "password") {

        let nextInput = input.parentElement.nextElementSibling.querySelector("input");

        if (input.value.length < 8) {
            errorElement.textContent += "password must be greater than 7 characters,";
        }

        if (!/[a-z]/.test(input.value)) {
            console.log("password has no lower case");
            errorElement.textContent += "password must contain 1 lower case,";
        }

        if (!/[A-Z]/.test(input.value)) {
            console.log("password has no upper case");
            errorElement.textContent += "password must contain 1 upper case,";
        }
                if (!/[0-9]/.test(input.value)) {
            console.log("password has no upper case");
            errorElement.textContent += "password must have 1 digit,";
        }
        if(!/[!@#$%^&*()_+\-=\[\]{}|;:'",.<>?/~`]/.test(input.value)){
            errorElement.textContent += "password must contain 1 special characters";
        }
    }

    if (input.type === "password" && input.name === "confirm_password") {
        let previousInput = input.parentElement.previousElementSibling.querySelector("input");
        console.log(previousInput);
    }
}
