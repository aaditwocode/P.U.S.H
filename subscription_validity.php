<?php
session_start();
$user_id = $_SESSION['user_id'];
$current_date = date('Y-m-d');

// Connect to the database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check the subscription
$sql = "SELECT end_date FROM Subscriptions WHERE user_id = $user_id AND status = 'Active'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['end_date'] < $current_date) {
        // Update the status to 'Inactive'
        $updateSql = "UPDATE Subscriptions SET status = 'Inactive' WHERE user_id = $user_id";
        $conn->query($updateSql);
    }
}

// Retrieve the updated status
$statusQuery = "SELECT status FROM Subscriptions WHERE user_id = $user_id";
$statusResult = $conn->query($statusQuery);
$statusRow = $statusResult->fetch_assoc();

echo json_encode(['status' => $statusRow['status']]);
$conn->close();
?>
