<?php
session_start();
include 'db_config.php';

// Retrieve all orders for general view
$sql = "SELECT order_id, username, meal_name, amount, order_date, address FROM orders ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Orders</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            display: flex; 
            justify-content: center; 
            padding: 20px; 
        }
        .container { 
            max-width: 900px; 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(128, 0, 128, 0.4); 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: center; 
        }
        th { 
            background-color: purple; 
            color: white; 
        }
        .no-orders { 
            color: red; 
            font-weight: bold; 
        }
        .content { margin-top: 15%; }
        .navbar-nav .nav-link {
    color: #fff !important; /* Text color changed to white */
    transition: all 0.3s ease-in-out;
    padding: 8px 15px;
    border-radius: 8px;
    font-weight: 500 !important;
}

.navbar-nav .nav-link:hover {
    background-color: #570151; /* Background color on hover */
    color: #fff !important; /* Text color remains white on hover */
    transform: scale(1.1);
}
.top{
    margin-top:5%;
}

    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item"><a class="nav-link" href="admin.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="imageup.html">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="orderDetails.php"> Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="displayContact.php"> Contact </a></li>



            </ul>
            <div id="user-info">
              <span id="user-name" class="me-3"></span>
              <a id="logout-btn" class="btn btn-outline-danger round-button" style="display: none; color: red !important" href="logout.php" >Logout</a>
              <button id="login-btn" class="btn btn-outline-primary round-button" onclick="location.href='login.html'">Login</button>
          </div>          
        </div>
    </div>
</nav>
    <div class="container top">
        <h2 class="text-center">All Orders</h2>
        <?php if (mysqli_num_rows($result) > 0) : ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Meal Name</th>
                    <th>Amount Paid (₹)</th>
                    <th>Order Date</th>
                    <th>Delivery Address</th> <!-- New Address Column -->
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['order_id']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['meal_name']) ?></td>
                        <td><?= htmlspecialchars($row['amount']) ?></td>
                        <td><?= htmlspecialchars($row['order_date']) ?></td>
                        <td><?= htmlspecialchars($row['address'] ?? 'No Address Provided') ?></td> <!-- Address Display -->
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else : ?>
            <p class="no-orders text-center">No orders found.</p>
        <?php endif; ?>
    </div>
    <script type="module">
        document.addEventListener("DOMContentLoaded", async () => {
          const userInfo = await fetchUserDetails();
          const userNameElement = document.getElementById("user-name");
          const loginBtn = document.getElementById("login-btn");
          const logoutBtn = document.getElementById("logout-btn");
      
          // If the user is logged in, show their name, otherwise show "Guest"
          if (userInfo && userInfo.name) {
            userNameElement.textContent = `Welcome, ${userInfo.name}`;
            loginBtn.style.display = 'none';
            logoutBtn.style.display = 'inline-block';
          } else {
            userNameElement.textContent = "Welcome, Guest";
            loginBtn.style.display = 'inline-block';
            logoutBtn.style.display = 'none';
          }
      
          // Logout functionality
          logoutBtn.addEventListener("click", () => {
            clearUserSession();
            window.location.href = "login.html"; // Redirect to login page after logout
          });
        });
      
        // Function to fetch user details from the backend (getUserDetails.php)
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
      
        // Utility to clear session (logout action)
        function clearUserSession() {
          localStorage.removeItem("user");
        }
      </script>
</body>

</html>

<?php
$conn->close();
?>
