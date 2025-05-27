
function validateForm() {
    const login = document.getElementById("login").value.trim();
    const password = document.getElementById("password").value.trim();
    let valid = true;

    const loginError = document.getElementById("loginError");
    const passwordError = document.getElementById("passwordError");

    loginError.textContent = "";
    passwordError.textContent = "";

    if (login.length < 4) {
        loginError.textContent = "Username must be at least 4 characters.";
        valid = false;
    }

    if (password.length < 4) {
        passwordError.textContent = "Password must be at least 4 characters.";
        valid = false;
    }

    return valid;
}

function registrationForm() {
    const login = document.getElementById("login").value.trim();
    const personalName = document.getElementById("personalName").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    let valid = true;

    // Очистка ошибок
    document.getElementById("loginError").textContent = "";
    document.getElementById("personalNameError").textContent = "";
    document.getElementById("emailError").textContent = "";
    document.getElementById("passwordError").textContent = "";

    if (login.length < 4) {
        document.getElementById("loginError").textContent = "Username must be at least 4 characters.";
        valid = false;
    }

    if (personalName.length < 2) {
        document.getElementById("personalNameError").textContent = "Personal name must be at least 2 characters.";
        valid = false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        document.getElementById("emailError").textContent = "Please enter a valid email address.";
        valid = false;
    }

    if (password.length < 4) {
        document.getElementById("passwordError").textContent = "Password must be at least 4 characters.";
        valid = false;
    }

    return valid;
}
