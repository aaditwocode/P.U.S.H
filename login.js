function validateForm() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const emailError = document.getElementById('email-error');
    const passwordError = document.getElementById('password-error');

    let isValid = true;

    emailError.textContent = '';
    passwordError.textContent = '';

    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(emailInput.value)) {
        emailError.textContent = 'Please enter a valid email address.';
        isValid = false;
    }

    if (passwordInput.value.length < 8) {
        passwordError.textContent = 'Password must be at least 8 characters.';
        isValid = false;
    }

    if (isValid) {
        alert('Sign-in successful!');
    }
}