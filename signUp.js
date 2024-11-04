function validateForm() {
    // Simple validation example
    const name = document.getElementById('name').value;
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const dob = document.getElementById('dob').value;

    let isValid = true;

    if (name === '') {
        document.getElementById('name-error').innerText = 'Name is required.';
        isValid = false;
    } else {
        document.getElementById('name-error').innerText = '';
    }

    if (username === '') {
        document.getElementById('username-error').innerText = 'Username is required.';
        isValid = false;
    } else {
        document.getElementById('username-error').innerText = '';
    }

    if (email === '') {
        document.getElementById('email-error').innerText = 'Email is required.';
        isValid = false;
    } else {
        document.getElementById('email-error').innerText = '';
    }

    if (password === '') {
        document.getElementById('password-error').innerText = 'Password is required.';
        isValid = false;
    } else {
        document.getElementById('password-error').innerText = '';
    }

    if (dob === '') {
        document.getElementById('dob-error').innerText = 'Date of Birth is required.';
        isValid = false;
    } else {
        document.getElementById('dob-error').innerText = '';
    }

    if (isValid) {
        alert('Form submitted successfully!');
    }
}
