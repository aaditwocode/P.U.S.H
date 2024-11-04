<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "push_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "'); window.location.href='signup.html';</script>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkQuery = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already exists'); window.location.href='signup.html';</script>";
    } else {
        $sql = "INSERT INTO users (name, username, email, dob, phone, password) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo "<script>alert('Statement Preparation Failed: " . $conn->error . "'); window.location.href='signup.html';</script>";
        }

        $stmt->bind_param("ssssss", $name, $username, $email, $dob, $phone, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Sign Up Successful!'); window.location.href='home.html';</script>";
        } else {
            echo "<script>alert('Execution Failed: " . $stmt->error . "'); window.location.href='signup.html';</script>";
        }

        $stmt->close();
    }
}

$conn->close();
?>


