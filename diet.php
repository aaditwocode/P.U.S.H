<?php
session_start();
include 'config.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: welcome.html");
    exit();
}

$userId = $_SESSION['user_id'];

if (isset($_POST['update_info'])) {
    $height = floatval($_POST['height']);
    $weight = floatval($_POST['weight']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    
    if ($height <= 0 || $height > 3 || !is_numeric($height)) {
        die("Invalid height. Please enter a value between 0.01 and 3.00 meters.");
    }
    if ($weight <= 0 || $weight > 500 || !is_numeric($weight)) {
        die("Invalid weight. Please enter a value between 0.1 and 500.0 kg.");
    }

    $sql = "UPDATE diet SET height = '$height', weight = '$weight', gender = '$gender' WHERE user_id = $userId";
    $conn->query($sql);
}

$sql = "SELECT height, weight, gender FROM diet WHERE user_id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $height = $row['height'];
    $weight = $row['weight'];
    $gender = strtolower($row['gender']);
} else {
    $height = '';
    $weight = '';
    $gender = '';
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="./assets/WhatsApp Image 2024-09-21 at 16.04.21.jpeg" />
    <title>P.U.S.H Diet</title>
    <link rel="stylesheet" href="diet.css">
</head>

<body>
    <script src="diet.js"></script>
    <header>
        <div class="container0">
            <div class="leftHeader">
                <img src="./assets/tinywow_change_bg_photo_65176222.jpg" alt="">
                <h2>P . U . S . H</h2>
            </div>
            <div class="rightHeader">
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="home.php#features">Features</a></li>
                    <li><a href="home.php#subscription">Subscription</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="logout.php">LogOut</a></li>
                </ul>
                <div class="avatar-stack">
                    <div class="avatar">
                    <a href="platinum.php"><img src="./assets/profile2.jpg" alt="Remy Sharp"></a>
                    </div>
                  </div>
            </div>
        </div>
    </header>

    <section id="hero">
        <h2>Eat Healthy, Live Better</h2>
        <h2>Welcome to Your Diet Plan</h2>
        <p>Track your calories and discover personalized diet plans and tips to improve your health.</p>
        <h3>WORLD HEALTH ORGANIZATION</h3>
        <button onclick="window.location.href='https://www.who.int/news-room/fact-sheets/detail/healthy-diet'">Learn
            More</button>
    </section>

    <section>
        <h1 class="type">Types of Physique</h1>
        <div class="container1">
            <div class="physique-box">
                <h2>SLIM </h2>
                <ol>
                    <li>A slim physique typically has a low body fat percentage and muscle mass.</li>
                    <li>People with this physique tend to have a lean body structure with little visible muscle
                        definition.</li>
                </ol>
                <ol type="I">
                    <li>Muscle Mass: 35% - 45%</li>
                    <li>Body Fat: 10% - 15%</li>
                    <li>Body Mass Index (BMI): 18 - 22</li>
                </ol>
            </div>

            <div class="physique-box">
                <h2>BULK</h2>
                <ol>
                    <li>A bulk physique is characterized by high muscle mass with a moderate to high body fat
                        percentage.</li>
                    <li>This physique often emphasizes strength and size over leanness.</li>
                </ol>
                <ol type="I">
                    <li>Muscle Mass: 50% - 60%</li>
                    <li>Body Fat: 15% - 25%</li>
                    <li>BMI: 25 - 30</li>
                </ol>
            </div>

            <div class="physique-box">
                <h2>TONED</h2>
                <ol>
                    <li>A toned physique combines a balanced amount of muscle mass with a low body fat percentage.</li>
                    <li>This leads to visible muscle definition without excessive bulk.</li>
                </ol>
                <ol type="I">
                    <li>Muscle Mass: 40% - 50%</li>
                    <li>Body Fat: 8% - 12%</li>
                    <li>BMI: 20 - 24</li>
                </ol>
            </div>
        </div>
    </section>

  
        <div class="container">
        <section class="user-details-section" id="user-details-section">
    <h2>Enter Your Details</h2>
    <form action="diet.php" method="post">
        <label for="user-weight">Weight (kg):</label>
        <input type="number" name="weight" id="user-weight" 
               value="<?php echo htmlspecialchars($weight); ?>" 
               placeholder="Enter your weight" 
               step="0.1" min="0" required><br>
        
        <label for="user-height">Height (m):</label>
        <input type="number" step="0.01" name="height" id="user-height" 
               value="<?php echo htmlspecialchars($height); ?>" 
               placeholder="Enter your height" 
               min="0" required><br>
        
        <label>Gender:</label>
        <label><input type="radio" name="gender" value="male" 
               <?php echo ($gender === 'male') ? 'checked' : ''; ?>> Male</label>
        <label><input type="radio" name="gender" value="female" 
               <?php echo ($gender === 'female') ? 'checked' : ''; ?>> Female</label>
        
        <button type="submit" name="update_info">Update Info</button>
    </form>
</section>


        <section class="user-details-section">
            <h1>BMI Calculator</h1>
            <div class="container2">
            <form action="diet.php" method="post">
                <button name="bmi">Calculate BMI</button>
</form>
                <?php 
                if (isset($_POST['bmi'])) {
                if ($weight > 0 && $height > 0) {
    $bmi = round($weight / ($height * $height), 2);

    if ($gender === 'male') {
        if ($bmi < 20) {
            $category = 'Underweight';
            $analysis = 'You are considered underweight. It may be beneficial to follow our bulk plan followed by the toned plan.';
        } elseif ($bmi < 25) {
            $category = 'Normal weight';
            $analysis = 'Congratulations! You have a normal weight. Maintain the balanced diet that you are currently taking. We recommend you try all the plans.';
        } elseif ($bmi < 30) {
            $category = 'Overweight';
            $analysis = 'You are classified as overweight. Consider adopting our slim weight plan.';
        } else {
            $category = 'Obesity';
            $analysis = 'You are considered obese. It is highly recommended to seek advice from a healthcare provider and to strictly follow the slim diet plan.';
        }
    } elseif ($gender === 'female') {
        if ($bmi < 18.5) {
            $category = 'Underweight';
            $analysis = 'You are considered underweight. It may be beneficial to follow our bulk plan followed by the toned plan.';
        } elseif ($bmi < 24.9) {
            $category = 'Normal weight';
            $analysis = 'Congratulations! You have a normal weight. Maintain a balanced diet that you are currently taking. We recommend you try all the plans.';
        } elseif ($bmi < 29.9) {
            $category = 'Overweight';
            $analysis = 'You are classified as overweight. Consider adopting our slim weight plan.';
        } else {
            $category = 'Obesity';
            $analysis = 'You are considered obese. It is highly recommended to seek guidance from a healthcare provider and to strictly follow the slim diet plan.';
        }
    }
} else {
    $bmi = 'Invalid weight or height.';
    $category = '';
    $analysis = '';
}
echo "<div id='result'><strong>Your BMI is:</strong> $bmi - $category</div>";
echo "<div id='analysis'>$analysis</div>";
$sql = "UPDATE diet SET bmi = '$bmi' WHERE user_id = $userId";
$conn->query($sql);
}?>
            </div>
        </section>

        <section class="body-type-selector" id="body-type-section">
            <h2>Select Your Body Type</h2>
            <div class="body-container">
                <div class="body-selector">
                    <h3>Bulk</h3>
                    <button onclick="selectBodyType('bulk')">Select Bulk Plan</button>
                </div>
                <div class="body-selector">
                    <h3>Slim</h3>
                    <button onclick="selectBodyType('slim')">Select Slim Plan</button>
                </div>
                <div class="body-selector">
                    <h3>Tone</h3>
                    <button onclick="selectBodyType('tone')">Select Tone Plan</button>
                </div>
            </div>
        </section>

        <section class="meal-plan-section" id="meal-plan-section" style="display:none;">
            <h2>Your Custom Meal Plan</h2>
            <section class="progress-container">
                <h3>Set Start Date for Calorie Tracking</h3>
                <div>
                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date" />
                    <button onclick="setStartDate()">Set Start Date</button>
                </div>
            </section>

            <section class="progress-container" id="progress-section" style="display:none;">
                <h2>Calorie Progress Tracker</h2>
                <div>
                    <label for="daily-calorie-input">Daily Calorie Goal:</label>
                    <input type="number" id="daily-calorie-input" placeholder="Enter daily calorie goal" />
                    <button onclick="setDailyCalorieGoal()">Set Daily Calorie Goal</button>
                </div>
                <p>Total Calories for the Month: <strong id="total-calories-display">0 kcal</strong></p>
                <div class="progress-bar">
                    <div class="progress" id="progress-bar"></div>
                </div>
                <div>
                    <p id="progress-text">Progress: 0%</p>
                </div>
                <div>
                    <label for="calories-consumed">Calories Consumed:</label>
                    <input type="number" id="calories-consumed" placeholder="Enter calories" />
                    <button onclick="addCalories()">Add Calories</button>
                    <button onclick="resetProgress()">Reset Calories</button>
                </div>

                <div id="results-section" style="display: none;">
                    <h3>Results</h3>
                    <p>Start Date: <span id="start-date-display"></span></p>
                    <p>Current Date: <span id="current-date-display"></span></p>

                    <p id="final-results"></p>
                    <p id="remaining-calories-display"></p>
                </div>

                <div id="reset-section" style="display: none;">
                    <h3>Monthly Tracking Results Before Reset</h3>
                    <p id="tracking-results"></p>
                    <p id="new-tracking-period"></p>
                </div>
            </section>
            <section class="meal">
            <label>Select Diet Type:</label><br>
            <input type="radio" id="vegetarian" name="diet" value="vegetarian" checked>
            <label for="vegetarian">Vegetarian</label><br>
            <input type="radio" id="non-vegetarian" name="diet" value="non-vegetarian">
            <label for="non-vegetarian">Non-Vegetarian</label><br>
            <button onclick="generateMealPlan()">Generate Meal Plan</button>
            <div id="meal-plan"></div>
            </section>
        </section>
    </div>

    <footer id="contact">
            <p>&copy; 2024 Push Fitness. All rights reserved.</p>
            <p>Email: contact@pushfitness.com</p>
            <p>Phone: (123) 456-7890 </p>
    </footer>
</body>

</html>