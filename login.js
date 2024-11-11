function validateForm() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const emailError = document.getElementById('email-error');
    const passwordError = document.getElementById('password-error');

    let isValid = true;

    // Reset error messages before checking
    emailError.textContent = '';
    passwordError.textContent = '';

    // Email pattern for validation
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    // Email validation
    if (!emailPattern.test(emailInput.value)) {
        emailError.textContent = 'Please enter a valid email address.';
        isValid = false;
    }

    // Password validation
    if (passwordInput.value.length < 8) {
        passwordError.textContent = 'Password must be at least 8 characters.';
        isValid = false;
    }

    return isValid;
}

// Real-time validation for the email field
const emailInput = document.getElementById('email');
emailInput.addEventListener('input', function() {
    const emailError = document.getElementById('email-error');
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (emailInput.value.length > 0 && !emailPattern.test(emailInput.value)) {
        emailError.textContent = 'Please enter a valid email address.';
    } else {
        emailError.textContent = '';
    }
});

// Real-time validation for the password field
const passwordInput = document.getElementById('password');
passwordInput.addEventListener('input', function () {
    const passwordError = document.getElementById('password-error');
    if (passwordInput.value.length < 8) {
        passwordError.textContent = 'Password must be at least 8 characters.';
    } else {
        passwordError.textContent = '';
    }
});
