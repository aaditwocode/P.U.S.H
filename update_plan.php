<?php
include 'config.php';

$userId = $_GET['user_id'];
$planId = $_GET['plan_id'];

$updateSubscription = "UPDATE Subscriptions SET plan_id = '$planId' WHERE user_id = '$userId'";
$updateUser = "UPDATE Users SET plan_id = '$planId' WHERE user_id = '$userId'";

if (mysqli_query($conn, $updateSubscription) && mysqli_query($conn, $updateUser)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>
