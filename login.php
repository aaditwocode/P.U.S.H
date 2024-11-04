<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "push_db";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    
        if (password_verify($password, $row['password'])) {
            echo "<script>alert('Login Successful!'); window.location.href='home.html';</script>";
        } else {
            echo "<script>alert('Incorrect password'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Email not registered'); window.location.href='login.html';</script>";
    }
}

$conn->close();
?>
