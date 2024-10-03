document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contact-form');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const subjectInput = document.getElementById('subject');
    const messageInput = document.getElementById('message');

    // Form validation
    form.addEventListener('submit', function (e) {
        let isValid = true;
        let errorMessage = '';

        if (nameInput.value.trim() === '') {
            isValid = false;
            errorMessage += 'Please enter your name.\n';
            nameInput.classList.add('error');
        } else {
            nameInput.classList.remove('error');
        }

        if (!validateEmail(emailInput.value)) {
            isValid = false;
            errorMessage += 'Please enter a valid email address.\n';
            emailInput.classList.add('error');
        } else {
            emailInput.classList.remove('error');
        }

        if (subjectInput.value.trim() === '') {
            isValid = false;
            errorMessage += 'Please enter a subject.\n';
            subjectInput.classList.add('error');
        } else {
            subjectInput.classList.remove('error');
        }

        if (messageInput.value.trim() === '') {
            isValid = false;
            errorMessage += 'Please enter your message.\n';
            messageInput.classList.add('error');
        } else {
            messageInput.classList.remove('error');
        }

        if (!isValid) {
            alert(errorMessage); // Show validation messages
            e.preventDefault(); // Prevent form submission if invalid
        }
    });

    // Email validation function
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    // Add focus and blur event listeners to inputs
    [nameInput, emailInput, subjectInput, messageInput].forEach(input => {
        input.addEventListener('focus', function () {
            input.classList.add('input-focused');
        });

        input.addEventListener('blur', function () {
            input.classList.remove('input-focused');
        });
    });
});
// JavaScript for Accordion FAQ functionality
document.addEventListener("DOMContentLoaded", function () {
    // Get all accordion buttons
    const accordionBtns = document.querySelectorAll('.accordion-btn');

    // Loop through each button and add click event
    accordionBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            // Toggle the active class on the clicked button
            this.classList.toggle('active');

            // Get the panel (answer) associated with the clicked button
            const panel = this.nextElementSibling;

            // Toggle the visibility of the panel (answer)
            if (panel.style.display === 'block') {
                panel.style.display = 'none';
            } else {
                panel.style.display = 'block';
            }
        });
    });
});
