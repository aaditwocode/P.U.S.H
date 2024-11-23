<?php
session_start();  // Start the session

// If the user is already logged in, redirect them to the home page
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: home.php?id=" . $_SESSION['user_id']); // Redirect to home page
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "push_db";

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle redirection and messages
function showMessageAndRedirect($message, $redirect) {
    echo "<script>
            window.onload = function() {
                showModal('$message');
                setTimeout(function() {
                    window.location.href = '$redirect';
                }, 2000);
            };
          </script>";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // SQL query to fetch user details based on email
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    // Check if a user is found
    if ($result && $result->num_rows > 0) {
        // User found, fetch the user details
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Password is correct, set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            // Redirect user to home page with their user_id
            header("Location: home.php?id=" . $row['user_id']);
            exit();
        } else {
            // Incorrect password
            showMessageAndRedirect("Incorrect password.", "login.html");
        }
    } else {
        // User not found, redirect to sign-up page
        showMessageAndRedirect("Email not registered. Please sign up.", "signup.html");
    }
}

// Close the database connection
$conn->close();
?>
