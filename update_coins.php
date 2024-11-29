<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT coins FROM Challenges WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch coins: ' . mysqli_error($conn)]);
    exit;
}

$row = mysqli_fetch_assoc($result);
$currentCoins = $row ? intval($row['coins']) : 0;

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['totalCoins'])) {
    $newCoins = intval($data['totalCoins']);

    $updateSql = "UPDATE Challenges SET coins = coins + $newCoins WHERE user_id = $user_id";
    if (!mysqli_query($conn, $updateSql)) {
        echo json_encode(['success' => false, 'message' => 'Failed to update coins: ' . mysqli_error($conn)]);
        exit;
    }
}

$sql = "SELECT coins FROM Challenges WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch coins: ' . mysqli_error($conn)]);
    exit;
}

$row = mysqli_fetch_assoc($result);
$currentCoins = $row ? intval($row['coins']) : 0;

echo json_encode(['success' => true, 'coins' => $currentCoins]);
?>