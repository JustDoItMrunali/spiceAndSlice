<?php
session_start();

// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$database = "tiffin_service";

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve contact data
$sql = "SELECT name, email, phone, comment FROM contact_us";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <br><br>
    <title>Contact Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
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

    </style>
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
    <div class="container mt-5">
        <h2 class="text-center mb-4">Contact Details</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['phone']) . "</td>
                                <td>" . htmlspecialchars($row['comment']) . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No data found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
