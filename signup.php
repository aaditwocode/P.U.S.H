<?php
session_start();
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "push_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phoneno = mysqli_real_escape_string($conn, $_POST['phoneno']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $checkQuery = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already exists'); window.location.href='signup.html';</script>";
    } else {
        $sql = "INSERT INTO users (name, username, email, dob, phone, password) 
                VALUES ('$name', '$username', '$email', '$dob', '$phoneno' , '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Sign Up Successful!'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href='signup.html';</script>";
        }
    }
}

$conn->close();
?>
