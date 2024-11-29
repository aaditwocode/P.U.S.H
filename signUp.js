function realTimeValidation() {
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const phonenoInput = document.getElementById('phoneno');
    const dobInput = document.getElementById('dob');

    nameInput.addEventListener('input', function () {
        const nameError = document.getElementById('name-error');
        const namePattern = /^[a-zA-Z\s]{3,}$/;
        if (!namePattern.test(nameInput.value)) {
            nameError.innerText = 'Name must be at least 3 characters long and contain no special characters or numbers, except spaces.';
        } else {
            nameError.innerText = '';
        }
    });

    usernameInput.addEventListener('input', function () {
        const usernameError = document.getElementById('username-error');
        const usernamePattern = /^(?=.*[!@#$%^&*(),.?":{}|<>]).{4,}$/;
        if (!usernamePattern.test(usernameInput.value)) {
            usernameError.innerText = 'Username must be at least 4 characters long and contain one special character';
        } else {
            usernameError.innerText = '';
        }
    });

    emailInput.addEventListener('input', function () {
        const emailError = document.getElementById('email-error');
        const emailPattern = /@/;
        if (!emailPattern.test(emailInput.value)) {
            emailError.innerText = 'Email must contain "@" symbol.';
        } else {
            emailError.innerText = '';
        }
    });

    passwordInput.addEventListener('input', function () {
        const passwordError = document.getElementById('password-error');
        const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordPattern.test(passwordInput.value)) {
            passwordError.innerText = 'Password must be at least 8 characters long, with at least one number, one special character, and both upper and lower case letters.';
        } else {
            passwordError.innerText = '';
        }
        updatePasswordStrength();
    });

    phonenoInput.addEventListener('input', function () {
        const phonenoError = document.getElementById('phoneno-error');
        const phonenoPattern = /^\d{10}$/; // Pattern to match exactly 10 digits
        if (!phonenoPattern.test(phonenoInput.value)) {
            phonenoError.innerText = 'Phone number must be exactly 10 digits.';
        } else {
            phonenoError.innerText = '';
        }
    });



    dobInput.addEventListener('input', function () {
        const dobError = document.getElementById('dob-error');
        const dobDate = new Date(dobInput.value);
        const age = new Date().getFullYear() - dobDate.getFullYear();
        const monthDiff = new Date().getMonth() - dobDate.getMonth();
        const ageValid = age > 12 || (age === 12 && monthDiff >= 0);
        if (!dobInput.value || !ageValid) {
            dobError.innerText = 'You must be older than 12 years.';
        } else {
            dobError.innerText = '';
        }
    });
}

function validateForm(event) {
    const name = document.getElementById('name').value;
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const dob = document.getElementById('dob').value;

    let isValid = true;

    const errorMessages = document.querySelectorAll('.error');
    errorMessages.forEach(function (error) {
        error.innerText = '';
    });

    const namePattern = /^[a-zA-Z\s]{3,}$/;
    if (!namePattern.test(name)) {
        document.getElementById('name-error').innerText = 'Name must be at least 3 characters long and contain no special characters or numbers, except spaces.';
        isValid = false;
    }

    const usernamePattern = /^(?=.*[!@#$%^&*(),.?":{}|<>]).{4,}$/;
    if (!usernamePattern.test(username)) {
        document.getElementById('username-error').innerText = 'Username must be at least 4 characters long and contain one special character';
        isValid = false;
    }

    const emailPattern = /@/;
    if (!emailPattern.test(email)) {
        document.getElementById('email-error').innerText = 'Email must contain "@" symbol.';
        isValid = false;
    }

    const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordPattern.test(password)) {
        document.getElementById('password-error').innerText = 'Password must be at least 8 characters long, with at least one number, one special character, and both upper and lower case letters.';
        isValid = false;
    }

    const dobDate = new Date(dob);
    const age = new Date().getFullYear() - dobDate.getFullYear();
    const monthDiff = new Date().getMonth() - dobDate.getMonth();
    const ageValid = age > 12 || (age === 12 && monthDiff >= 0);
    if (!dob || !ageValid) {
        document.getElementById('dob-error').innerText = 'You must be older than 12 years.';
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault();
    }

    return isValid;
}

function updatePasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthMessage = document.getElementById('password-strength');
    const easyPattern = /^[a-zA-Z]+$/;  // Only letters
    const mediumPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/;  // Letters + numbers
    const strongPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;  // Letters + numbers + special chars

    if (strongPattern.test(password)) {
        strengthMessage.innerText = 'Strength: Strong';
        strengthMessage.style.color = 'green';
    }
    else if (mediumPattern.test(password)) {
        strengthMessage.innerText = 'Strength: Moderate';
        strengthMessage.style.color = 'orange';
    }
    else if (easyPattern.test(password)) {
        strengthMessage.innerText = 'Strength: Easy';
        strengthMessage.style.color = 'red';
    } else {
        strengthMessage.innerText = 'Strength: Weak';
        strengthMessage.style.color = 'red';
    }
}


document.addEventListener('DOMContentLoaded', realTimeValidation);
