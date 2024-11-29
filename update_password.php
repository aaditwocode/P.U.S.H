<?php
header('Content-Type: application/json');
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = mysqli_real_escape_string($conn, $data['email']);
$new_password = password_hash($data['password'], PASSWORD_DEFAULT);

$sql = "SELECT user_id FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $sql_update = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
    if ($conn->query($sql_update) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Email not registered']);
}

$conn->close();
?>
