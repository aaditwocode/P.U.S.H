<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: home.php?id=" . $_SESSION['user_id']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "push_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            header("Location: home.php?id=" . $row['user_id']);
            exit();
        } else {
            $error_message = "Incorrect password.";
        }
    } else {
        $error_message = "Email not registered. Please sign up.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="./assets/WhatsApp Image 2024-09-21 at 16.04.21.jpeg" />
    <title>P.U.S.H Sign In</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div id="modal" class="modal" style="display: <?php echo ($error_message != '') ? 'block' : 'none'; ?>;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalMessage"><?php echo $error_message; ?></p>
        </div>
    </div>
    
    <div class="container">
        <div class="left">
            <img src="./assets/tinywow_change_bg_photo_65176222.jpg" alt="Logo">
            <h2>P.U.S.H</h2>
            <p>Welcome back to your journey of strength, peace, and balance.</p>
        </div>
        <div class="right">
            <h3>Sign In to your account</h3>
            <form id="loginForm" method="POST" onsubmit="return validateForm()">
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="text" id="email" name="email" placeholder="email@example.com" required>
                    <div id="email-error" class="error"></div>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <div id="password-error" class="error"></div>
                </div>
                <button class="btn" type="submit">Sign In</button>
                <div class="signup-link">
                    Don't have an account? <a href="signUp.html">Sign up</a>
                </div>
                <div class="forgot-password">
                    Forgot Password? <a href="#" onclick="forgotPassword()">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showModal(message) {
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function forgotPassword() {
            const email = document.getElementById('email').value;
            if (!email) {
                showModal("Please enter your email address first.");
                return;
            }

            const newPassword = prompt("Enter your new password:");
            const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (newPassword === null) return;
            if (newPassword.length === 0) {
                showModal("Password cannot be empty.");
                return;
            }
            if (!passwordPattern.test(newPassword)) {
                showModal("Password must be at least 8 characters long, with at least one number, one special character, and both upper and lower case letters.");
                return;
            }

            if (confirm("Are you sure you want to set this as your new password?")) {
                fetch("update_password.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ email: email, password: newPassword })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showModal("Password updated successfully.");
                    } else {
                        if (data.error === 'Email not registered') {
                            showModal("The email address is not registered. Please sign up or enter a registered email.");
                        } else {
                            showModal("Error updating password. Please try again.");
                        }
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    showModal("An error occurred. Please try again later.");
                });
            }
        }

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

            return isValid;
        }
        
    </script>
</body>
</html>
