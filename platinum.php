<?php
session_start();
require 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT u.name, u.username, u.email, u.dob, u.phone, u.age, u.signup_date, u.password, 
                 p.plan_name, s.start_date, s.end_date, s.status, c.coins, d.weight, d.height, d.bmi, d.gender 
          FROM users u 
          LEFT JOIN Subscriptions s ON u.user_id = s.user_id 
          LEFT JOIN Plans p ON s.plan_id = p.plan_id 
          LEFT JOIN Challenges c ON u.user_id = c.user_id
          LEFT JOIN Diet d ON u.user_id = d.user_id 
          WHERE u.user_id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$user_data = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Dashboard</title>
    <style>
        :root {
            --bg-dark: #0a0c0f;
            --bg-light: #1a1d21;
            --card-hover: #2c2f34;
            --text-primary: #ffffff;
            --text-secondary: #8b8d91;
            --accent-green: #4CAF50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-primary);
        }

        .container {
            display: grid;
            grid-template-columns: 300px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            background-color: var(--bg-light);
            padding: 20px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #333;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #444;
        }

        .sidebar p {
            margin-bottom: 10px;
        }

        .menu-item {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .menu-item:hover {
            background-color: var(--card-hover);
            color: var(--text-primary);
        }

        .main-content {
            padding: 20px;
        }

        .hero {
            background: linear-gradient(135deg, #2a2d35 0%, #1a1d21 100%);
            padding: 2rem;
            border-radius: 20px;
            position: relative;
            margin-bottom: 20px;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 40%;
            height: 100%;
            background: url('https://source.unsplash.com/random/800x600/?fitness') center/cover no-repeat;
            border-radius: 20px;
            opacity: 0.3;
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background-color: var(--bg-light);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-top: 10px;
        }

        .user-details-section {
            background-color: var(--bg-light);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 20px;
        }

        .user-details-section h2 {
            margin-bottom: 10px;
            border-bottom: 1px solid var(--text-secondary);
            padding-bottom: 5px;
            font-size: 1.5rem;
        }

        .user-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .user-grid div {
            margin-bottom: 10px;
        }

        .user-grid p {
            margin-bottom: 5px;
            color: var(--text-secondary);
        }

        .user-grid .value {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            margin-top: 20px;
            background: var(--accent-green);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

.profile {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 30px;
}

.profile-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #444;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--text-primary);
    font-weight: bold;
    text-transform: uppercase;
    overflow: hidden;
}

.profile div {
    display: flex;
    flex-direction: column;
}

.profile .text-lg {
    font-size: 1.2rem;
    font-weight: bold;
    color: var(--text-primary);
}

.profile .text-sm {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.profile-img:hover {
    background-color: var(--card-hover);
    transition: all 0.3s ease;
}


        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .hero::after {
                display: none;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .user-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile">
                <div class="profile-img">
    <?php 
    $name = $user_data['name'];
    $name_parts = explode(" ", $name);
    $initials = strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
    echo $initials;
    ?>
</div><br>
                <div><br>
                    <p class="text-lg font-bold"><?php echo htmlspecialchars($user_data['name']); ?></p>
                    <p class="text-sm"><?php echo htmlspecialchars($user_data['username']); ?></p>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="hero">
                <h1>Welcome Back!</h1>
                <p>Track your fitness journey and achieve your goals with personalized insights.</p>
                <a href="home.php" class="btn">HOME PAGE</a>
            </div>
            <div class="stats-grid">
                <div class="card">
                    <p>Weight Balance</p>
                    <p class="stat-value"><?php echo $user_data['weight'] ?: 'N/A'; ?> kg</p>
                </div>
                <div class="card">
                    <p>Height</p>
                    <p class="stat-value"><?php echo $user_data['height'] ?: 'N/A'; ?> m</p>
                </div>
                <div class="card">
                    <p>BMI</p>
                    <p class="stat-value"><?php echo $user_data['bmi'] ?: 'N/A'; ?> kg/m2</p>
                </div>
            </div>
            <div class="user-details-section">
                <h2>Personal Information</h2>
                <div class="user-grid">
                    <div><p>Username</p><p class="value"><?php echo htmlspecialchars($user_data['username']); ?></p></div>
                    <div><p>Age</p><p class="value"><?php echo $user_data['age']; ?></p></div>
                    <div><p>Date of Birth</p><p class="value"><?php echo $user_data['dob']; ?></p></div>
                    <div><p>Gender</p><p class="value"><?php echo $user_data['gender'] ?: 'N/A'; ?></p></div>
                    <div><p>Email</p><p class="value"><?php echo htmlspecialchars($user_data['email']); ?></p></div>
                    <div><p>Phone No.</p><p class="value"><?php echo $user_data['phone']; ?></p></div>
                </div>
            </div>
            <div class="user-details-section">
                <h2>Subscription Details</h2>
                <div class="user-grid">
                    <div><p>Plan Type</p><p class="value"><?php echo $user_data['plan_name'] ?: 'N/A'; ?></p></div>
                    <div><p>Coin Tracker</p><p class="value"><?php echo $user_data['coins'] ?: 0; ?></p></div>
                    <div><p>SignUp Date</p><p class="value"><?php echo $user_data['signup_date']; ?></p></div>
                    <div><p>Password</p><p class="value"><?php echo str_repeat('*', strlen($user_data['password'])); ?></p></div>
                    <div><p>Start Date</p><p class="value"><?php echo $user_data['start_date'] ?: 'N/A'; ?></p></div>
                    <div><p>End Date</p><p class="value"><?php echo $user_data['end_date'] ?: 'N/A'; ?></p></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>