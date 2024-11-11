// Real-time validation for all fields
function realTimeValidation() {
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const dobInput = document.getElementById('dob');

    // Real-time validation for Name
    nameInput.addEventListener('input', function() {
        const nameError = document.getElementById('name-error');
        const namePattern = /^[a-zA-Z\s]{3,}$/;
        if (!namePattern.test(nameInput.value)) {
            nameError.innerText = 'Name must be at least 3 characters long and contain no special characters or numbers, except spaces.';
        } else {
            nameError.innerText = '';
        }
    });

    // Real-time validation for Username
    usernameInput.addEventListener('input', function() {
        const usernameError = document.getElementById('username-error');
        const usernamePattern = /^(?=.*[!@#$%^&*(),.?":{}|<>]).{4,}$/;
        if (!usernamePattern.test(usernameInput.value)) {
            usernameError.innerText = 'Username must be at least 4 characters long and contain one special character';
        } else {
            usernameError.innerText = '';
        }
    });

    // Real-time validation for Email
    emailInput.addEventListener('input', function() {
        const emailError = document.getElementById('email-error');
        const emailPattern = /@/;
        if (!emailPattern.test(emailInput.value)) {
            emailError.innerText = 'Email must contain "@" symbol.';
        } else {
            emailError.innerText = '';
        }
    });

    // Real-time validation for Password
    passwordInput.addEventListener('input', function() {
        const passwordError = document.getElementById('password-error');
        const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordPattern.test(passwordInput.value)) {
            passwordError.innerText = 'Password must be at least 8 characters long, with at least one number, one special character, and both upper and lower case letters.';
        } else {
            passwordError.innerText = '';
        }
        updatePasswordStrength();
    });

    // Real-time validation for Date of Birth (check if older than 12)
    dobInput.addEventListener('input', function() {
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

// Add event listener for form submission
function validateForm(event) {
    const name = document.getElementById('name').value;
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const dob = document.getElementById('dob').value;

    let isValid = true;

    // Clear previous error messages
    const errorMessages = document.querySelectorAll('.error');
    errorMessages.forEach(function (error) {
        error.innerText = '';
    });

    // Validate Name (allows spaces between words)
    const namePattern = /^[a-zA-Z\s]{3,}$/;
    if (!namePattern.test(name)) {
        document.getElementById('name-error').innerText = 'Name must be at least 3 characters long and contain no special characters or numbers, except spaces.';
        isValid = false;
    }

    // Validate Username
    const usernamePattern = /^(?=.*[!@#$%^&*(),.?":{}|<>]).{4,}$/;
    if (!usernamePattern.test(username)) {
        document.getElementById('username-error').innerText = 'Username must be at least 4 characters long and contain one special character';
        isValid = false;
    }

    // Validate Email
    const emailPattern = /@/;
    if (!emailPattern.test(email)) {
        document.getElementById('email-error').innerText = 'Email must contain "@" symbol.';
        isValid = false;
    }

    // Validate Password
    const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordPattern.test(password)) {
        document.getElementById('password-error').innerText = 'Password must be at least 8 characters long, with at least one number, one special character, and both upper and lower case letters.';
        isValid = false;
    }

    // Validate Date of Birth (ensure user is older than 12)
    const dobDate = new Date(dob);
    const age = new Date().getFullYear() - dobDate.getFullYear();
    const monthDiff = new Date().getMonth() - dobDate.getMonth();
    const ageValid = age > 12 || (age === 12 && monthDiff >= 0);
    if (!dob || !ageValid) {
        document.getElementById('dob-error').innerText = 'You must be older than 12 years.';
        isValid = false;
    }

    // Prevent form submission if validation fails
    if (!isValid) {
        event.preventDefault();
    }

    return isValid;
}

// Password strength function
function updatePasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthMessage = document.getElementById('password-strength');
    const easyPattern = /^[a-zA-Z]+$/;  // Only letters
    const mediumPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/;  // Letters + numbers
    const strongPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;  // Letters + numbers + special chars

    // Check for strong password
    if (strongPattern.test(password)) {
        strengthMessage.innerText = 'Strength: Strong';
        strengthMessage.style.color = 'green';
    }
    // Check for moderate password
    else if (mediumPattern.test(password)) {
        strengthMessage.innerText = 'Strength: Moderate';
        strengthMessage.style.color = 'orange';
    }
    // Check for easy password
    else if (easyPattern.test(password)) {
        strengthMessage.innerText = 'Strength: Easy';
        strengthMessage.style.color = 'red';
    } else {
        strengthMessage.innerText = 'Strength: Weak';
        strengthMessage.style.color = 'red';
    }
}


// Call the real-time validation function when the page loads
document.addEventListener('DOMContentLoaded', realTimeValidation);
