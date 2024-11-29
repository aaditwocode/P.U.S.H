<?php
session_start();
require 'config.php';  

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$plans_query = "SELECT * FROM Plans";
$plans_result = $conn->query($plans_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_plan = mysqli_real_escape_string($conn, $_POST['selectedPlan']); // Escape user input

    $plan_query = "SELECT plan_id FROM Plans WHERE plan_name = '$selected_plan'";
    $result = $conn->query($plan_query);

    if ($result && $result->num_rows > 0) {
        $plan_data = $result->fetch_assoc();
        $plan_id = $plan_data['plan_id'];

        $update_plan_query = "UPDATE users SET plan_id = $plan_id WHERE user_id = $user_id";
        $conn->query($update_plan_query);
        
        header("Location: QR.php");
        exit();
    } else {
        echo "Invalid plan selection!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="./assets/WhatsApp Image 2024-09-21 at 16.04.21.jpeg" />
    <title>P.U.S.H Payment</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1a1a1a;
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border-radius: 8px;
            overflow: hidden;
            background-color: #222;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
        .success-message {
            background-color: #4CAF50;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px 8px 0 0;
            color: #fff;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .cart-table th, .cart-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        .cart-table th {
            background-color: #4CAF50;
            color: #fff;
        }
        .price, .subtotal {
            color: #FFC107;
            font-weight: bold;
        }
        .coupon-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .coupon-section input {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            margin-bottom: 10px;
        }
        .coupon-section .btn {
            align-self: flex-start;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .coupon-section .btn:hover {
            background-color: #45a049;
        }
        .coupon-section .tagline {
            color: #ccc;
            font-size: 0.9em;
            margin-top: 10px;
            text-align: center;
        }
        .cart-totals {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 8px;
        }
        .cart-totals h3 {
            margin-top: 0;
            color: #FFC107;
        }
        .cart-totals .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-paypal {
            background-color: #ffc439;
            color: #000;
        }
        .btn-card {
            background-color: transparent;
            border: 1px solid #ffffff;
            color: white;
            transition: border-color 0.3s ease;
        }
        .btn-card:hover {
            border-color: #ffc439;
        }
        .separator {
            text-align: center;
            margin: 10px 0;
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-message">
            <span id="success-text"></span>
            <button class="btn">Continue Shopping</button>
        </div>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>PRODUCT</th>
                    <th>PRICE</th>
                    <th>QUANTITY</th>
                    <th>SUBTOTAL</th>
                </tr>
            </thead>
            <tbody id="cart-items">
                <tr>
                    <td id="plan-name"></td>
                    <td id="plan-price" class="price"></td>
                    <td>1</td>
                    <td id="plan-subtotal" class="subtotal"></td>
                </tr>
            </tbody>
        </table>
        <div class="coupon-section">
            <input type="text" placeholder="Enter coupon code">
            <button class="btn">Apply Coupon</button>
            <p class="tagline">Boost your journey with exclusive discounts tailored just for you!</p> <!-- New tagline -->
        </div>
        <div class="cart-totals">
            <h3>CART TOTALS</h3>
            <div class="total-row">
                <span>Subtotal</span>
                <span id="subtotal" class="price"></span>
            </div>
            <div class="total-row">
                <span>Total</span>
                <span id="total" class="price"></span>
            </div>
            <form action="payment.php" method="POST">
                <input type="hidden" name="selectedPlan" id="selectedPlan">
                <button type="submit" class="btn" id="checkout-btn">Proceed To Checkout</button>
            </form>
            <button onclick="window.location.href='home.php'" class="btn">Back to Home</button>
        </div>
    </div>

    <script>
            document.addEventListener('DOMContentLoaded', function() {
    const userId = <?php echo $_SESSION['user_id']; ?>;  

    const planName = localStorage.getItem(`${userId}_selectedPlan`);
    const planPrice = parseFloat(localStorage.getItem(`${userId}_selectedPlanPrice`));
    const quantity = 1;
    
            document.getElementById('success-text').innerText = `"${planName}" has been added to your cart.`;
            document.getElementById('plan-name').innerText = planName;
            document.getElementById('plan-price').innerText = `₹${planPrice.toFixed(2)}`;

            const total = planPrice * quantity;
            document.getElementById('plan-subtotal').innerText = `₹${total.toFixed(2)}`;
            document.getElementById('subtotal').innerText = `₹${total.toFixed(2)}`;
            document.getElementById('total').innerText = `₹${total.toFixed(2)}`;

            document.getElementById('selectedPlan').value = planName;

            function applyCoupon() {
                const couponInput = document.querySelector('.coupon-section input').value;
                let discount = 0;

                if (couponInput === 'PUSH50') {
                    discount = 0.50;
                }

                const discountedTotal = total * (1 - discount);
                document.getElementById('total').innerText = `₹${discountedTotal.toFixed(2)}`;
                if (discount > 0) {
                    alert('Coupon applied! You received a 50% discount.');
                } else {
                    alert('Invalid coupon code. Please try again.');
                }
            }

            document.querySelector('.coupon-section button').addEventListener('click', applyCoupon);

            document.getElementById('checkout-btn').addEventListener('click', function() {
            });
        });
    </script>

</body>
</html>
