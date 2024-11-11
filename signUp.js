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

function updatePasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthMessage = document.getElementById('password-strength');
    const easyPattern = /^[a-zA-Z]+$/;
    const mediumPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/;
    const strongPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (strongPattern.test(password)) {
        strengthMessage.innerText = 'Strength: Strong';
        strengthMessage.style.color = 'green';
    } else if (mediumPattern.test(password)) {
        strengthMessage.innerText = 'Strength: Medium';
        strengthMessage.style.color = 'orange';
    } else if (easyPattern.test(password)) {
        strengthMessage.innerText = 'Strength: Easy';
        strengthMessage.style.color = 'red';
    } else {
        strengthMessage.innerText = 'Strength: Weak';
        strengthMessage.style.color = 'red';
    }
}