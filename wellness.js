document.getElementById("signup-form").addEventListener("submit", function(event) {
    event.preventDefault();
    const email = document.querySelector('#signup-form input').value;
    if (email) {
        alert("Thank you for subscribing! We'll keep you updated.");
        document.querySelector('#signup-form input').value = "";  // Clear input
    } else {
        alert("Please enter a valid email.");
    }
});
