<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="./assets/WhatsApp Image 2024-09-21 at 16.04.21.jpeg" />
    <title>P.U.S.H User_Dashboard</title>
    <style>
        :root {
            --bg-color: #0a0c0f;
            --card-bg: #1a1d21;
            --card-hover: #22262b;
            --text-color: #ffffff;
            --text-muted: #8b8d91;
            --accent-pink: #ffd0e7;
            --accent-blue: #d0e6ff;
            --accent-green: #4CAF50;
            --gradient-1: linear-gradient(135deg, #2a2d35 0%, #1a1d21 100%);
            --gradient-2: linear-gradient(135deg, #1e2227 0%, #16181c 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 100vh;
        }

        /* Hero Section */
        .hero {
            background: var(--gradient-1);
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 40%;
            height: 100%;
            background: url('https://source.unsplash.com/random/800x600/?fitness') center/cover;
            border-radius: 20px;
        }

        .hero-content {
            width: 60%;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        /* Sidebar */
        .sidebar {
            background: var(--gradient-2);
            padding: 2rem;
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: var(--card-hover);
        }

        /* Main Content */
        .main-content {
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--gradient-2);
            padding: 1.5rem;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin: 0.5rem 0;
        }

        /* Goals Section */
        .goals-section {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .goal-percentage {
            background: var(--gradient-2);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
        }

        .goal-details {
            background: var(--gradient-2);
            padding: 2rem;
            border-radius: 15px;
        }

        /* Feature Cards */
        .feature-cards {
            display: grid;
            grid-template-columns: repeat(400px, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .feature-card {
            background: var(--gradient-2);
            padding: 2rem;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-card:hover .card-details {
            opacity: 1;
            transform: translateY(0);
        }

        .card-details {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.9);
            padding: 2rem;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
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

        /* Charts */
        .chart-container {
            background: var(--gradient-2);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        /* Progress Circle */
        .progress-circle {
            width: 200px;
            height: 200px;
            position: relative;
            margin: 0 auto;
        }

        .progress-circle svg {
            transform: rotate(-90deg);
        }

        .progress-circle circle {
            fill: none;
            stroke-width: 8;
            stroke-linecap: round;
        }

        .progress-bg {
            stroke: var(--card-hover);
        }

        .progress-bar {
            stroke: var(--accent-green);
            transition: stroke-dashoffset 0.3s ease;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .progress-value {
            font-size: 2rem;
            font-weight: bold;
        }

        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .goals-section {
                grid-template-columns: 1fr;
            }
        }
        
        .container {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background-color: var(--bg-dark);
            padding: 20px;
            border-right: 1px solid #333;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            margin-bottom: 30px;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #444;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            padding: 12px;
            margin: 4px 0;
            border-radius: 8px;
            cursor: pointer;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-item:hover, .nav-item.active {
            background-color: var(--card-bg);
            color: var(--text-primary);
        }

        .stats-grid {
            display: flex;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
            flex-direction: column;
        }

        .stat-card {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 15px;
        }

        .stat-label {
            color: var(--text-secondary);
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
        }

        .stat-unit {
            color: var(--text-secondary);
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
         
        <div class="sidebar">
            <div class="profile">
                <div class="profile-img"></div>
                <div>
                    <div>Michael Brown</div>
                    <div style="color: var(--text-secondary)">@Michaelbrown07</div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Weight balance</div>
                    <div class="stat-value">73 <span class="stat-unit">kg</span></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Height</div>
                    <div class="stat-value" <span class="stat-unit">cm</span></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">BMI</div>
                    <div class="stat-value">86 <span class="stat-unit">kg/m2</span></div>
                </div>
            </div>
            
        </div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Hero Section -->
            <section class="hero">
                <div class="hero-content">
                    <h1>Welcome Back!</h1>
                    <p>Track your fitness journey and achieve your goals with personalized insights.</p>
                    <a href="home.php" class="btn">HOME PAGE</a>
                </div>
            </section>

            <!-- Feature Cards -->
            <div class="feature-cards">
                <div class="feature-card">
                    <h3></h3>
                    <p></p>
                    <div class="card-details">
                        <h4></h4>
                        <p></p>
                        <a href="" class="btn"></a>
                    </div>
                </div>
                <div class="feature-card">
                            <h3>Subscription Info</h3>
                            <p>View your info</p>
                    <div class="card-details">
                                <h4>Subscription Type:</h4>
                                <p></p>
                                <h4>Start Date:</h4>
                                <p></p>
                                <h4>End Date:</h4>
                                <p></p>
                                <a href="home.php#subscription" class="btn">Explore</a>
                    </div>
                </div>
        </main>
        
    </div>
</body>
</html>