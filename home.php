<?php
session_start();
include 'config.php';  // Ensure your database connection is included

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: welcome.html");
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
echo "<script>alert('Welcome, User : " . $username . "');</script>";

// Check subscription status
$subscriptionQuery = "SELECT plan_id, status FROM Subscriptions WHERE user_id = '$userId' ORDER BY start_date DESC LIMIT 1";
$result = mysqli_query($conn, $subscriptionQuery);

$isSubscribed = false;
$isActive = false;
$currentPlanId = null;

if (mysqli_num_rows($result) > 0) {
    $subscription = mysqli_fetch_assoc($result);
    $currentPlanId = $subscription['plan_id'];
    $isActive = $subscription['status'] === 'Active';
    $isSubscribed = true;
}


// Fetch user details and subscription data
$user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session after login
$user_query = "SELECT u.plan_id, p.plan_name, s.status
               FROM users u
               LEFT JOIN Subscriptions s ON u.user_id = s.user_id
               LEFT JOIN Plans p ON s.plan_id = p.plan_id
               WHERE u.user_id = $user_id AND s.status = 'Active'";

$user_result = $conn->query($user_query);
$user_data = $user_result->fetch_assoc();

$user_plan = $user_data['plan_name'] ?? 'No Plan';
$subscription_status = $user_data['status'] ?? 'Inactive';

// Fetch the features allowed for the user's plan
$features_query = "SELECT feature_name FROM Features WHERE plan_id = (SELECT plan_id FROM users WHERE user_id = $user_id)";
$features_result = $conn->query($features_query);

$features = [];
while ($row = $features_result->fetch_assoc()) {
    $features[] = $row['feature_name'];
}

// Pass PHP data to JavaScript
echo "<script>
        const userPlan = " . json_encode($user_plan) . ";
        const subscriptionStatus = " . json_encode($subscription_status) . ";
        const userFeatures = " . json_encode($features) . ";
      </script>";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/svg+xml" href="./assets/WhatsApp Image 2024-09-21 at 16.04.21.jpeg" />
    <title>P.U.S.H</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10.0.0/swiper-bundle.min.css" />
    <link rel="stylesheet" href="testimonial.css" >
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"/>

</head>

<body>
  <script>


document.addEventListener('DOMContentLoaded', function() {
    const userId = <?php echo json_encode($userId); ?>;
    const isSubscribed = <?php echo json_encode($isSubscribed); ?>;
    const isActive = <?php echo json_encode($isActive); ?>;
    const goldButton = document.getElementById('gold-subscribe');
    const platinumButton = document.getElementById('platinum-subscribe');
    const diamondButton = document.getElementById('diamond-subscribe');
    
    // Create a message element
    const messageBox = document.createElement('div');
    messageBox.id = 'subscription-message';
    messageBox.style.position = 'fixed';
    messageBox.style.bottom = '20px';
    messageBox.style.left = '50%';
    messageBox.style.transform = 'translateX(-50%)';
    messageBox.style.padding = '10px 20px';
    messageBox.style.backgroundColor = '#333';
    messageBox.style.color = '#fff';
    messageBox.style.borderRadius = '5px';
    messageBox.style.display = 'none';  // Initially hidden
    document.body.appendChild(messageBox);

    // Function to show message
    function showMessage(message) {
        messageBox.textContent = message;
        messageBox.style.display = 'block';
    }


    function hideMessage() {
        messageBox.style.display = 'none';
    }

    // Disable buttons if subscribed and active
    if (isSubscribed && isActive) {
        goldButton.disabled = true;
        platinumButton.disabled = true;
        diamondButton.disabled = true;
    } else {
        goldButton.disabled = false;
        platinumButton.disabled = false;
        diamondButton.disabled = false;
    }

    // Hover effect for the subscription buttons
    function handleHover(button, plan) {
        button.addEventListener('mouseover', () => {
            const selectedPlan = localStorage.getItem(`${userId}_selectedPlan`);
            if (isSubscribed && isActive) {
                showMessage(`You are already subscribed to the ${selectedPlan} plan.`);
            } else if (isSubscribed || isActive) {
                showMessage(`Complete payment to the ${selectedPlan} plan!`);
            } else {
                showMessage(`No plan is selected`);
            }
        });

        button.addEventListener('mouseout', hideMessage);
    }

    handleHover(goldButton, 'Gold');
    handleHover(platinumButton, 'Platinum');
    handleHover(diamondButton, 'Diamond');

    // Click event for subscribing
    function subscribe(planId, planType, price) {
        if (isSubscribed && !isActive) {
            fetch(`update_plan.php?user_id=${userId}&plan_id=${planId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        localStorage.setItem(`${userId}_selectedPlan`, planType);
                        localStorage.setItem(`${userId}_selectedPlanPrice`, price);
                        window.location.href = 'payment.php';
                    } else {
                        showMessage("Failed to update plan.");
                        hideMessage();
                    }
                })
                .catch(error => console.error("Error:", error));
        } else {
            localStorage.setItem(`${userId}_selectedPlan`, planType);
            localStorage.setItem(`${userId}_selectedPlanPrice`, price);
            window.location.href = 'payment.php';
        }
    }

    goldButton.addEventListener('click', () => subscribe(1, 'Gold', '499'));
    platinumButton.addEventListener('click', () => subscribe(2, 'Platinum', '799'));
    diamondButton.addEventListener('click', () => subscribe(3, 'Diamond', '999'));
});

// Update subscription details on the page
function updateSubscriptionDetails() {
    document.getElementById('user-plan').innerText = userPlan || 'No Plan';
    
    // Clear and update available features list
    const featureList = document.getElementById('available-features');
    featureList.innerHTML = ''; // Clear existing features
    userFeatures.forEach(feature => {
        const listItem = document.createElement('li');
        listItem.textContent = feature;
        featureList.appendChild(listItem);
    });
}


// JavaScript functions to manage feature access based on user subscription
function setMessage(message) {
    document.getElementById('message').innerText = message;
}

function accessFeature(link, feature) {
    if (subscriptionStatus !== 'Active') {
        setMessage("Please subscribe to access this feature.");
    } else if (!userFeatures.includes(feature)) {
        setMessage("Upgrade to a higher plan to access this feature.");
    } else {
        window.location.href = link;
    }
}

function hoverFeature(feature) {
    if (subscriptionStatus !== 'Active') {
        setMessage("Please subscribe to access this feature.");
    } else if (!userFeatures.includes(feature)) {
        setMessage("Upgrade to a higher plan to access this feature.");
    } else {
        setMessage(""); // Clear message if the feature is accessible
    }
}

function checkSubscriptionStatus() {
    fetch("subscription_validity.php")
        .then(response => response.json())
        .then(data => {
            if (data.status === 'Inactive') {
                alert("Your subscription has expired.");
                // Redirect to a limited-access page
                window.location.href = 'home.php';
            }
        });
}

window.onload = function() {
    checkSubscriptionStatus();
    updateSubscriptionDetails();
};



  </script>
        <header>
            <div class="container0">
                <div class="leftHeader">
                    <img src="./assets/tinywow_change_bg_photo_65176222.jpg" alt="">
                    <h2>P . U . S . H</h2>
                </div>
                <div class="rightHeader">
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#subscription">Subscription</a></li>
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
    
        <div class="video-wrapper">
          <video class="vdo" autoplay loop muted src="./assets/PUSH.mp4" onclick="this.muted = !this.muted"></video>
            <div id="quote">
                <p id="push">P.U.S.H.</p>
                <p id="pursue">Pursue Ultimate Strength and Health</p>
                <p id="quote-text">"Take care of your body. It's the only place you have to live." - Jim Rohn</p>
            </div>
        </div>
    
        <section id="features">
    <h1>WHY YOU SHOULD CHOOSE P.U.S.H</h1>
    <!-- Message Display Area -->
    <div id="subscription-details" style="margin-bottom: 20px;">
        <h2>Your Plan: <span id="user-plan"></span></h2>
        <h3>Available Features:</h3>
        <ul id="available-features"></ul>
        <div id="message" style="color: red; font-weight: bold; margin-bottom: 20px;"></div>
    </div>
    <div class="reasons-container">
        <!-- Diet Feature -->
        <div class="reason-box" id="diet-feature" onclick="accessFeature('diet.php', 'Diet')" onmouseover="hoverFeature('Diet')" style="cursor: pointer;">
            <h2>DIET</h2>
            <ul>
                <li>Custom diet plans for you</li>
                <li>Calorie tracking for precise nutrition management</li>
                <li>Custom meal plans for different dietary needs</li>
                <li>Use calorie tracker for your daily goals and follow diet plans catered to your B.M.I</li>
            </ul>
            <div class="reason-image">
                <img src="./assets/diet.jpg" alt="Diet image">
            </div>
        </div>

        <!-- Workout Feature -->
        <div class="reason-box" id="workout-feature" onclick="accessFeature('new.html', 'Workout')" onmouseover="hoverFeature('Workout')" style="cursor: pointer;">
            <h2>WORKOUT</h2>
            <ul>
                <li>Wide variety of workout routines</li>
                <li>One-on-one training sessions</li>
                <li>Customizable workout plans for all levels</li>
                <li>Workout plans made for your needs</li>
            </ul>
            <div class="reason-image">
                <img src="./assets/workout.jpg" alt="Workout image">
            </div>
        </div>

        <!-- Wellness Feature -->
        <div class="reason-box" id="wellness-feature" onclick="accessFeature('wellness.html', 'Wellness')" onmouseover="hoverFeature('Wellness')" style="cursor: pointer;">
            <h2>WELLNESS</h2>
            <ul>
                <li>Mindfulness and meditation techniques</li>
                <li>Yoga sessions for flexibility and balance</li>
                <li>Holistic wellness programs for mental well-being</li>
                <li>Custom meditation clock made for your well-being</li>
            </ul>
            <div class="reason-image">
                <img src="./assets/wellness.jpg" alt="Wellness image">
            </div>
        </div>

        <!-- Challenges Feature -->
        <div class="reason-box" id="challenges-feature" onclick="accessFeature('challenges.html', 'Challenges')" onmouseover="hoverFeature('Challenges')" style="cursor: pointer;">
            <h2>CHALLENGES</h2>
            <ul>
                <li>Challenges sorted by difficulty</li>
                <li>Exciting, engaging fitness activities</li>
                <li>Trackable progress with various duration options</li>
                <li>Healthy challenges offered with incentives</li>
            </ul>
            <div class="reason-image">
                <img src="./assets/challenge.jpg" alt="Challenges image">
            </div>
        </div>
    </div>
</section>

    <section>
        <div class="container">
                <h1 class="h">WHAT TO CHOOSE</h1>
            <div class="plans">
                <div class="plan-header">
                    <div class="plan-type platinum">PLATINUM</div>
                    <div class="plan-type diamond">DIAMOND</div>
                    <div class="plan-type gold">GOLD</div>
                    <div class="plan-type trial">TRIAL</div>
                </div>

                <div class="plan-features">
                    <div class="feature">
                        <span>Wellness</span>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item disabled"><span class="emoji">❌</span></div>
                        <div class="feature-item disabled"><span class="emoji">❌</span></div>
                        <div class="feature-item disabled"><span class="emoji">❌</span></div>
                    </div>
                    <div class="feature">
                        <span>Diet Plan</span>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item disabled"><span class="emoji">❌</span></div>
                        <div class="feature-item disabled"><span class="emoji">❌</span></div>
                    </div>
                    <div class="feature">
                        <span>Challenges</span>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item disabled"><span class="emoji">❌</span></div>
                    </div>
                    <div class="feature">
                        <span>Workout</span>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item disabled"><span class="emoji">❌</span></div>
                    </div>
                    <div class="feature">
                        <span>Website Visit</span>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                        <div class="feature-item"><span class="emoji">✅</span></div>
                    </div>
                </div>

            </div>
        </div>
    </section>
     <section class="container1">
        <div class="testimonial mySwiper">
          <div class="testi-content swiper-wrapper">
            <div class="slide swiper-slide">
              <img src="./assets/pic1.jpeg" alt="" class="image" />
              <p>
                P.U.S.H. has completely transformed my approach to fitness and health.
                 As a bodybuilder and content creator, I need a reliable, all-in-one 
                 resource, and this platform delivers.P.U.S.H. has completely 
                 transformed my approach to fitness and health.
              </p>
  
              <i class="bx bxs-quote-alt-left quote-icon"></i>
  
              <div class="details">
                <span class="name">Gaurav Taneja</span>
                <span class="job">Youtuber , Body Builder</span>
              </div>
            </div>
            <div class="slide swiper-slide">
              <img src="./assets/dalal-testimonial.webp" alt="" class="image" />
              <p>
                As a powerlifter and influencer, P.U.S.H. has become my go-to for 
                everything fitness. The strength-focused programs, tailored nutrition
                 advice, and mental wellness tips keep me at the top of my game. This
                  platform is a must for anyone looking to elevate their training and
                   well-being!
              </p>
  
              <i class="bx bxs-quote-alt-left quote-icon"></i>
  
              <div class="details">
                <span class="name">Rajat Dalal</span>
                <span class="job">Power Lifter</span>
              </div>
            </div>
            <div class="slide swiper-slide">
              <img src="./assets/chanu-testimonail.jpg" alt="" class="image" />
              <p>
                As a weightlifter, P.U.S.H. has been a game-changer. The expert
                 workout plans and nutrition guidance help me push my limits, while 
                 the mental wellness resources keep me focused. It’s everything I 
                 need in one platform to stay strong and balanced!
              </p>
  
              <i class="bx bxs-quote-alt-left quote-icon"></i>
  
              <div class="details">
                <span class="name">Mirabai Chani</span>
                <span class="job">Weight Lifter</span>
              </div>
            </div>
          </div>
          <div class="swiper-button-next nav-btn"></div>
          <div class="swiper-button-prev nav-btn"></div>
          <div class="swiper-pagination"></div>
        </div>
      </section>
      <div class="dark-theme-features">
        <div class="features-container">
          <div class="feature-card">
            <div class="feature-icon secured">
              <img src="./assets/icons8-goal-100.png" alt="Secured Payments" style="width: 24px;">
            </div>
            <div class="feature-title">Goal-Setting and Progress Tracking</div>
            <div class="feature-description">
              Users can set health and wellness goals related to mental health, nutrition, or lifestyle changes. The platform helps track progress and celebrate milestones, fostering motivation and accountability.
            </div>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon fast">
              <img src="./assets/icons8-meditation-100.png" alt="Fast & Easy to Load" style="width: 24px;">
            </div>
            <div class="feature-title">Mindfulness Meditation </div>
            <div class="feature-description">
              Participate in guided meditation sessions to boost mental clarity and emotional resilience. Choose from various styles tailored to your needs, promoting mindfulness and stress reduction.
            </div>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon light-dark">
              <img src="./assets/icons8-healthy-eating-100.png" alt="Light & Dark Version" style="width: 24px;">
            </div>
            <div class="feature-title">Nutrition and Meal Planning </div>
            <div class="feature-description">
              Get personalized meal plans and nutrition guidance tailored to your preferences. Track your food intake, and learn about balanced nutrition to enhance your wellness journey.
            </div>
          </div>
        </div>
      </div>
      <div class="pricing-container" id="subscription">
        <div class="pricing-card">
            <h3 class="plan-title">Gold Plan</h3>
            <div class="price">₹499</div>
            <div class="frequency">PER MONTH</div>
            <ul class="features">
                <li><span class="spn">✔</span>Workout</li>
                <li><span class="spn">✔</span>Challenges</li>
                <li>        </li>
                <li>         </li>
                <li>         </li>
                <li>         </li>
                <li>         </li>
                <li>         </li>
                <li>         </li>
                <li>         </li>
            </ul>
            <button id="gold-subscribe" class="subscribe-btn" >SUBSCRIBE NOW</button>
        </div>

        <div class="pricing-card ">
            <h3 class="plan-title">Platinum Plan</h3>
            <div class="price">₹799</div>
            <div class="frequency">PER MONTH</div>
            <ul class="features">
              <li><span class="spn">✔</span>Workout</li>
              <li><span class="spn">✔</span>Challenges</li>
              <li><span class="spn">✔</span>Diet</li>
              <li>         </li>
                <li>         </li>
                <li>         </li>
            </ul>
            <button id="platinum-subscribe" class="subscribe-btn">SUBSCRIBE NOW</button>
        </div>

        <div class="pricing-card">
            <h3 class="plan-title">Diamond Plan</h3>
            <div class="price">₹999</div>
            <div class="frequency">PER MONTH</div>
            <ul class="features">
                <li><span class="spn">✔</span>Workout</li>
                <li><span class="spn">✔</span>Challenges</li>
                <li><span class="spn">✔</span>Diet</li>
                <li><span class="spn">✔</span>Wellness</li>
            </ul>
            <button id="diamond-subscribe" class="subscribe-btn">SUBSCRIBE NOW</button>
        </div>
    </div>
   
      
    <footer id="contact">
            <p>&copy; 2024 Push Fitness. All rights reserved.</p>
            <p>Email: contact@pushfitness.com</p>
            <p>Phone: (123) 456-7890 </p>
    </footer>

</body>
</html>

