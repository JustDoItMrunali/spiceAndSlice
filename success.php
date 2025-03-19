<?php
session_start();
include 'db_config.php'; // Database connection file

if (!isset($_GET['payment_id'])) {
    echo "Invalid Payment!";
    exit;
}

// Collect order details
$orderId = $_GET['order_id'];
$paymentId = $_GET['payment_id'];
$amount = $_GET['amount'] ?? '0';
$mealName = $_GET['meal'] ?? 'Not Available';
$username = $_GET['username'] ?? 'Unknown User';
$address = $_GET['address'] ?? 'No Address Provided';

// Insert data into the database
$sql = "INSERT INTO orders (order_id, payment_id, username, meal_name, amount, address) 
        VALUES ('$orderId', '$paymentId', '$username', '$mealName', '$amount', '$address')";

if (mysqli_query($conn, $sql)) {
    echo "
    <html>
    <head>
        <title>Order Confirmation</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; }
            .container { max-width: 600px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(128, 0, 128, 0.4); text-align: center; }
            .success { color: green; font-weight: bold; font-size: 20px; }
            .button { display: inline-block; margin-top: 20px; padding: 12px 25px; background: purple; color: white; text-decoration: none; border-radius: 5px; font-size: 16px; transition: background 0.3s; border: none; cursor: pointer; }
            .button:hover { background: #7a00b0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Order Placed Successfully</h2>
            <p class='success'>Payment Successful</p>
            <p><strong>Order ID:</strong> $orderId</p>
            <p><strong>Meal:</strong> $mealName</p>
            <p><strong>Ordered By:</strong> $username</p>
            <p><strong>Delivery Address:</strong> $address</p>
            <p><strong>Amount Paid:</strong> ₹$amount</p>
            <a href='myorders.php' class='button'>View My Orders</a>
        </div>
    </body>
    </html>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
