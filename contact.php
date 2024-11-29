<?php
session_start();
include 'config.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: welcome.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    $wellness_query = "SELECT wellness_id FROM Wellness WHERE user_id = '$user_id'";
    $result = $conn->query($wellness_query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $wellness_id = $row['wellness_id'];

        $sql = "INSERT INTO WellnessContact (wellness_id, full_name, email, subject, message) 
                VALUES ('$wellness_id', '$name', '$email', '$subject', '$message')";

        if ($conn->query($sql) === TRUE) {
            // echo "Message sent successfully!";
        } else {
            // echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // echo "No wellness ID found for the current user.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="./assets/WhatsApp Image 2024-09-21 at 16.04.21.jpeg" />
    <title>P.U.S.H Reach Out for Support</title>
    <link rel="stylesheet" href="contact.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <div class="container0">
            <div class="leftHeader">
                <img src="./assets/tinywow_change_bg_photo_65176222.jpg" alt="">
                <h2 class="head">P . U . S . H</h2>
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
    <header class="header">
        <h1>Reach Out for Support</h1>
        <p>"Strength comes from seeking help. We are here to listen and guide you."</p>
    </header>

    <main>
        <section class="additional-content">
            <p><i class="fas fa-user-shield"></i> We provide a safe, confidential space for you to express your feelings and get support. Whether you're facing a tough challenge or just need to talk, we're here for you.</p>
        </section>

        <section class="quotes">
            <blockquote><i class="fas fa-quote-left"></i> “The greatest glory in living lies not in never falling, but in rising every time we fall.” – Nelson Mandela <i class="fas fa-quote-right"></i></blockquote>
            <blockquote><i class="fas fa-quote-left"></i> “Your mental health is a priority. Your happiness is essential.” <i class="fas fa-quote-right"></i></blockquote>
        </section>

        <section class="contact-info">
            <h2>Connect with Us</h2>
            <div class="contact-methods">
                <div class="method">
                    <i class="fas fa-phone-alt"></i>
                    <h3>Phone</h3>
                    <p><a href="tel:+15555551212">+91-0000000000</a></p>
                </div>
                <div class="method">
                    <i class="far fa-envelope"></i>
                    <h3>Email</h3>
                    <p><a href="mailto:support@example.com">support@mail.com</a></p>
                </div>
            </div>
            <div class="operating-hours">
                <h3>Operating Hours:</h3>
                <p>Monday - Friday: 9:00 AM - 5:00 PM </p>
            </div>
        </section>

        <section class="form-and-footer">
            <div class="footer-images">
                <img src="./assets/contactHeroImg.jpg" alt="Support and Care" class="main-image">
            </div>

            <section class="form-section">
                <h2>Send a Message</h2>
                <p>We are here to listen. Feel free to share your thoughts and concerns with us.</p>
                <form id="contact-form" action="contact.php" method="post">
                    <input type="text" id="name" name="name" placeholder="Your Full Name" required>
                    <input type="email" id="email" name="email" placeholder="Your Email" required>
                    <input type="text" id="subject" name="subject" placeholder="Subject" required>
                    <textarea id="message" name="message" placeholder="Your Message" required></textarea>
                    <button type="submit" class="cta-btn"><i class="fas fa-paper-plane">Send Message</i></button>
                    <button class="cta-btn"><a href="wellness.html" style="color: white; text-decoration: none;">Return Back</a></button>
                </form>
            </section>
        </section>
        
        <section class="accordion-section">
            <h2>Frequently Asked Questions</h2>
            <div class="accordion">
                <button class="accordion-btn">What services do you provide?</button>
                <div class="panel">
                    <p><i class="a"> We offer confidential mental health support, counseling, and a safe space to talk.</i></p>
                </div>

                <button class="accordion-btn">How can I get in touch with you?</button>
                <div class="panel">
                    <p><i class="b">You can contact us via phone or email. See our contact details above.</i></p>
                </div>

                <button class="accordion-btn">Is my information kept confidential?</button>
                <div class="panel">
                    <p><i class="c"> Yes, your privacy is important to us. All communication is kept confidential.</i></p>
                </div>
            </div>
        </section>

    </main>

    <footer id="contact">
        <p>&copy; 2024 Push Fitness. All rights reserved.</p>
        <p>Email: contact@pushfitness.com</p>
        <p>Phone: (123) 456-7890 </p>
    </footer>
    <script src="contact.js"></script>
</body>

</html>
