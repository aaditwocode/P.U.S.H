<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Retrieve the user's current coins
$sql = "SELECT coins FROM Challenges WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch coins: ' . mysqli_error($conn)]);
    exit;
}

$row = mysqli_fetch_assoc($result);
$currentCoins = $row ? intval($row['coins']) : 0;

// Check if new coins are provided in the request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['totalCoins'])) {
    $newCoins = intval($data['totalCoins']); // Sanitize and validate totalCoins

    // Update the coins in the database (adding new coins)
    // No need to manually reset or add the previous coins in PHP, the database update will handle it
    $updateSql = "UPDATE Challenges SET coins = coins + $newCoins WHERE user_id = $user_id";
    if (!mysqli_query($conn, $updateSql)) {
        echo json_encode(['success' => false, 'message' => 'Failed to update coins: ' . mysqli_error($conn)]);
        exit;
    }
}

// After update, fetch the latest total coins from the database
$sql = "SELECT coins FROM Challenges WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch coins: ' . mysqli_error($conn)]);
    exit;
}

$row = mysqli_fetch_assoc($result);
$currentCoins = $row ? intval($row['coins']) : 0;

// Respond with the user's current coins
echo json_encode(['success' => true, 'coins' => $currentCoins]);
?>