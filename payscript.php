<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$apiKey = "rzp_test_thI4BwSeVHe98o";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = isset($_POST['amount']) ? $_POST['amount'] : 0;
    $mealName = isset($_POST['mealName']) ? htmlspecialchars($_POST['mealName']) : 'Meal Order';
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'Unknown User';
    $address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : 'No address provided';
    $orderId = 'OID' . rand(1000, 9999); 
    $name = htmlspecialchars($_POST['username']); 
    $email = 'user@example.com';
    $mobile = '1234567890';
} else {
    echo "No POST data received!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Checkout</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .checkout-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        #pay-button {
            background-color: #6a0dad;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }
        #pay-button:hover { background-color: #5a0db0; }
    </style>
</head>
<body>
    <div class="checkout-container">
    <h2>Checkout</h2>
        <p>Meal Name: <?php echo htmlspecialchars($mealName); ?></p>
        <p>Total Amount: ₹<?php echo $amount; ?></p>
        <p>Ordered By: <strong><?php echo htmlspecialchars($username); ?></strong></p>
        <p>Delivery Address: <?php echo htmlspecialchars($address); ?></p>
        <button id="pay-button">Pay ₹<?php echo $amount; ?></button>
    </div>

    <script>
        var options = {
        "key": "<?php echo $apiKey; ?>",
        "amount": "<?php echo $amount * 100; ?>",
        "currency": "INR",
        "name": "Meal Service",
        "description": "<?php echo htmlspecialchars($mealName) . ' (Ordered by: ' . htmlspecialchars($username) . ') - Address: ' . htmlspecialchars($address); ?>",
        "prefill": {
            "name": "<?php echo $name; ?>",
            "email": "<?php echo $email; ?>",
            "contact": "<?php echo $mobile; ?>"
        },
        "theme": { "color": "#0e9e4f" },
        "handler": function (response) {
    window.location.href = "success.php?order_id=<?php echo $orderId; ?>&payment_id="
    + response.razorpay_payment_id
    + "&amount=<?php echo $amount; ?>"
    + "&meal=<?php echo htmlspecialchars($mealName); ?>"
    + "&username=<?php echo htmlspecialchars($username); ?>"
    + "&address=<?php echo htmlspecialchars($address); ?>"; // ✅ Added address here
},

        "modal": { "ondismiss": function () { window.location.href = "failure.php"; } }
    };

    var rzp = new Razorpay(options);
    document.getElementById('pay-button').onclick = function (e) {
        rzp.open();
        e.preventDefault();
    };
    </script>
</body>
</html>
