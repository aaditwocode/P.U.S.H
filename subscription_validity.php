<?php
session_start();
$user_id = $_SESSION['user_id'];
$current_date = date('Y-m-d');

$conn = new mysqli('localhost', 'root', '', 'push_db');

$sql = "SELECT end_date FROM Subscriptions WHERE user_id = $user_id AND status = 'Active'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['end_date'] < $current_date) {
        $updateSql = "UPDATE Subscriptions SET status = 'Inactive' WHERE user_id = $user_id";
        $conn->query($updateSql);
    }
}

$statusQuery = "SELECT status FROM Subscriptions WHERE user_id = $user_id";
$statusResult = $conn->query($statusQuery);
$statusRow = $statusResult->fetch_assoc();

echo json_encode(['status' => $statusRow['status']]);
$conn->close();
?>
