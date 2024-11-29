<?php
session_start();
require 'config.php';  
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activate'])) {
    $query = "SELECT u.plan_id, s.status FROM users u 
              LEFT JOIN subscriptions s ON u.user_id = s.user_id
              WHERE u.user_id = $user_id";
    $result = $conn->query($query);
    $user = $result->fetch_assoc();

    if ($user['plan_id'] && $user['status'] === 'Inactive') {
        $update_status_query = "UPDATE subscriptions SET status = 'Active', 
                                start_date = CURRENT_TIMESTAMP, 
                                end_date = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 MONTH)
                                WHERE user_id = $user_id AND status = 'Inactive'";
        $conn->query($update_status_query);

        $_SESSION['message'] = 'Your subscription has been activated!';
        header('Location: home.php');  
        exit();
    } else {
        $_SESSION['message'] = 'Your subscription is already active or no plan is associated with your account.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="./assets/WhatsApp Image 2024-09-21 at 16.04.21.jpeg" />
    <title> P.U.S.H QR_Code</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #121212;
            color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        h1 {
            font-size: 2.5em;
            font-weight: bold;
            color: darkgoldenrod;
            margin-bottom: 20px;
            text-shadow: 0px 4px 8px rgba(0, 123, 255, 0.5);
        }

        .qr-container {
            width: 300px;
            height: 300px;
            background-color: #1e1e1e;
            border: 2px solid #007BFF;
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            transition: background-color 0.3s, transform 0.3s;
            cursor: pointer;
            box-shadow: 0px 4px 20px rgba(0, 123, 255, 0.3);
        }

        .qr-container:hover {
            background-color: #007BFF;
            transform: scale(1.05);
            box-shadow: 0px 4px 20px rgba(0, 123, 255, 0.6);
        }

        .qr-code {
            width: 80%;
            height: 80%;
            background-image: url('assets/scan1.png'); 
            background-size: cover;
            border-radius: 10px;
            transition: opacity 0.3s;
        }

        .qr-container:hover .qr-code {
            opacity: 0.8;
        }

        .dashboard-text{
            color: lightblue;
        }

        .qr-container:hover .dashboard-text {
            opacity: 1;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body>
    <h1>P.U.S.H QR Code</h1>

    <form method="POST" id="activate-form" style="display: none;">
        <input type="hidden" name="activate" value="1">
    </form>

    <div class="qr-container" id="qr-code">
        <div class="qr-code"></div>
    </div>

    <p class="dashboard-text"><u>Double Click To Activate Your Subscription</u></p>
    <div>
    <button onclick="window.location.href='payment.php'" class="btn">Back to Cart</button>
    <button onclick="window.location.href='home.php'" class="btn">Back to Home</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrCode = document.getElementById('qr-code');

            qrCode.addEventListener('dblclick', function() {
                document.getElementById('activate-form').submit();
            });
        });
    </script>
</body>
</html>
