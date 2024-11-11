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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Prepare the SQL query to prevent SQL injection
    $sql = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind the email parameter
        $stmt->bind_param("s", $email);

        // Execute the prepared statement
        $stmt->execute();

        // Get the result of the query
        $result = $stmt->get_result();

        // Check if a user is found
        if ($result->num_rows > 0) {
            // User found, fetch the user details
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Password is correct, set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $row['user_id'];  // Assuming user_id exists in the database
                $_SESSION['username'] = $row['username'];  // Assuming username exists in the database
                $_SESSION['email'] = $row['email'];  // Optionally, store the email too if needed

                // Redirect user to home page with their user_id
                header("Location: home.php?id=" . $row['user_id']);
                exit();
            } else {
                // Incorrect password
                echo "<script>alert('Incorrect password'); window.location.href='login.html';</script>";
            }
        } else {
            // User not found, redirect to sign-up page
            echo "<script>alert('Email not registered. Please sign up.'); window.location.href='signup.html';</script>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // If the prepared statement fails
        echo "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
