<?php
session_start();
include 'db_config.php';

// Assume username is stored in the session after login
$username = $_SESSION['username'] ?? 'Unknown User';

$sql = "SELECT * FROM orders WHERE username = '$username' ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; padding: 20px; }
        .container { max-width: 800px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(128, 0, 128, 0.4); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        th { background-color: purple; color: white; }
        .no-orders { color: red; font-weight: bold; }
        .content { margin-top: 15%; }
    </style>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-light fixed-top">
    <div class="container-fluid">
        <a class="logo"><img src="logo.png" alt="" class="img-fluid" height="50px"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="fetch.php">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contactUs.html">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="save_review.php">Review</a></li>
                <li class="nav-item"><a class="nav-link" href="customised.php">Customised Meal</a></li>
                <li class="nav-item"><a class="nav-link" href="chatbot.html">Help Me</a></li>
            </ul>
            <div id="user-info">
              <span id="user-name" class="me-3"></span>
              <a id="logout-btn" class="btn btn-outline-danger round-button" style="display: none; color: red !important" href="logout.php">Logout</a>
              <button id="login-btn" class="btn btn-outline-primary round-button" onclick="location.href='login.html'">Login</button>
          </div>          
        </div>
    </div>
</nav>

<div class="content">
    <h2>My Orders</h2>
    <?php if (mysqli_num_rows($result) > 0) : ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Meal Name</th>
                <th>Amount Paid (₹)</th>
                <th>Order Date</th>
                <th>Delivery Address</th> <!-- New Address Column -->
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                    <td><?= htmlspecialchars($row['meal_name']) ?></td>
                    <td><?= htmlspecialchars($row['amount']) ?></td>
                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                    <td><?= htmlspecialchars($row['address'] ?? 'No Address Provided') ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p class="no-orders">No orders found.</p>
    <?php endif; ?>
</div>

<script type="module">
document.addEventListener("DOMContentLoaded", async () => {
    const userInfo = await fetchUserDetails();
    const userNameElement = document.getElementById("user-name");
    const loginBtn = document.getElementById("login-btn");
    const logoutBtn = document.getElementById("logout-btn");

    if (userInfo && userInfo.name) {
        userNameElement.textContent = `Welcome, ${userInfo.name}`;
        loginBtn.style.display = 'none';
        logoutBtn.style.display = 'inline-block';
    } else {
        userNameElement.textContent = "Welcome, Guest";
        loginBtn.style.display = 'inline-block';
        logoutBtn.style.display = 'none';
    }

    logoutBtn.addEventListener("click", () => {
        clearUserSession();
        window.location.href = "login.html";
    });
});

async function fetchUserDetails() {
    try {
        const response = await fetch('getUserDetails.php');
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching user details:', error);
        return null;
    }
}

function clearUserSession() {
    localStorage.removeItem("user");
}
</script>
</body>
</html>
