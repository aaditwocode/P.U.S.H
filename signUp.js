const nameInput = document.getElementById('name');
const usernameInput = document.getElementById('username');
const emailInput = document.getElementById('email');
const dobInput = document.getElementById('dob');
const phoneInput = document.getElementById('phone');
const passwordInput = document.getElementById('password');
const nameError = document.getElementById('name-error');
const usernameError = document.getElementById('username-error');
const emailError = document.getElementById('email-error');
const dobError = document.getElementById('dob-error');
const phoneError = document.getElementById('phone-error');
const passwordError = document.getElementById('password-error');
const passwordStrength = document.getElementById('password-strength');

nameInput.addEventListener('input', () => {
    const name = nameInput.value.trim();
    if (!name) {
        nameError.textContent = 'Name is required';
    } else if (name.length < 3) {
        nameError.textContent = 'Name must be at least 3 characters long';
    } else {
        nameError.textContent = '';
    }
});

usernameInput.addEventListener('input', () => {
    const username = usernameInput.value.trim();
    if (!username) {
        usernameError.textContent = 'Username is required';
    } else if (username.length < 3) {
        usernameError.textContent = 'Username must be at least 3 characters long';
    } else {
        usernameError.textContent = '';
    }
});

emailInput.addEventListener('input', () => {
    const email = emailInput.value.trim();
    if (!email) {
        emailError.textContent = 'Email is required';
    } else if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
        emailError.textContent = 'Invalid email address';
    } else {
        emailError.textContent = '';
    }
});

dobInput.addEventListener('input', () => {
    const dob = dobInput.value.trim();
    if (!dob) {
        dobError.textContent = 'Date of birth is required';
    } else {
        dobError.textContent = '';
    }
});

phoneInput.addEventListener('input', () => {
    const phone = phoneInput.value.trim();
    if (!phone) {
        phoneError.textContent = 'Phone number is required';
    } else if (!/^\d{10}$/.test(phone)) {
        phoneError.textContent = 'Invalid phone number';
    } else {
        phoneError.textContent = '';
    }
});

passwordInput.addEventListener('input', () => {
    const password = passwordInput.value.trim();
    if (!password) {
        passwordError.textContent = 'Password is required';
    } else if (password.length < 8) {
        passwordError.textContent = 'Password must be at least 8 characters long';
    } else if (!/[a-z]/.test(password)) {
        passwordError.textContent = 'Password must contain at least one lowercase letter';
    } else if (!/[A-Z]/.test(password)) {
        passwordError.textContent = 'Password must contain at least one uppercase letter';
    } else if (!/[0-9]/.test(password)) {
        passwordError.textContent = 'Password must contain at least one number';
    } else if (!/[!@#$%^&*()_+=[\]{};':"\\|,.<>?]/.test(password)) {
        passwordError.textContent = 'Password must contain at least one special character';
    } else {
        passwordError.textContent = '';
        if (password.length < 12) {
            passwordStrength.textContent = 'Weak password';
            passwordStrength.style.color = 'red';
        } else if (password.length < 16) {
            passwordStrength.textContent = 'Medium password';
            passwordStrength.style.color = 'orange';
        } else {
            passwordStrength.textContent = 'Strong password';
            passwordStrength.style.color = 'green';
        }
    }
});

document.getElementById('login-form').addEventListener('submit', (e) => {
    e.preventDefault();
    const name = nameInput.value.trim();
    const username = usernameInput.value.trim();
    const email = emailInput.value.trim();
    const dob = dobInput.value.trim();
    const phone = phoneInput.value.trim();
    const password = passwordInput.value.trim();
    if (!name || !username || !email || !dob || !phone || !password) {
        alert('Please fill in all fields');
    } else if (nameError.textContent || usernameError.textContent || emailError.textContent || dobError.textContent || phoneError.textContent || passwordError.textContent) {
        alert('Please fix the errors');
    } else {
        alert('Sign UP successful');
        window.location.href = "home.html";
    }
});