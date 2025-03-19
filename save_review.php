<?php
// Enable error reporting for better debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'tiffin_service');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert review data if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = htmlspecialchars($_POST['name']);
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : '';

    if (!empty($userName) && $rating > 0 && !empty($comment)) {
        $sql = "INSERT INTO reviews (userName, rating, comment) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $userName, $rating, $comment);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $errorMsg = "Please fill all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Page</title>
    <!-- Styles and Bootstrap -->
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tangerine">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .navbar-nav .nav-link {
            transition: all 0.3s ease-in-out; /* Smooth transition */
            padding: 8px 15px; /* Slightly increase padding for smoother effect */
            border-radius: 8px; /* Rounded corners for hover effect */
            font-weight: 500 !important;
        }

        .navbar-nav .nav-link:hover {
            background-color: #570151; /* Background color on hover */
            color: #fff !important; /* Text color on hover */
            transform: scale(1.1); /* Enlarges the link */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-light fixed-top">
    <div class="container-fluid">
        <a class="logo"><img src="logo.png" alt="" class="img-fluid" height="50px"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" >
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
                <li class="nav-item"><a class="nav-link" href="myorders.php">My order</a></li>

            </ul>
            <div id="user-info">
              <span id="user-name" class="me-3"></span>
              <a id="logout-btn" class="btn btn-outline-danger round-button" style="display: none; color: red !important" href="logout.php" >Logout</a>
              <button id="login-btn" class="btn btn-outline-primary round-button" onclick="location.href='login.html'">Login</button>
          </div>          
        </div>
    </div>
</nav>

<div style='padding-top: 80px;'>
    <?php
    if (!empty($errorMsg)) {
        echo "<p style='color: red; text-align: center;'>$errorMsg</p>";
    }

    // Display reviews
    echo "<div style='display: flex; justify-content: space-between; align-items: center; padding: 20px;'>";
    echo "<h2 style='color:rgb(93, 0, 140); text-align:center; '>REVIEW</h2>";
    echo "<a href='review.html' style='background-color:rgb(112, 19, 174); color: #fff !important; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Add Review</a>";
    echo "</div>";

    echo "<div style='display: flex; flex-wrap: wrap; gap: 20px; padding: 20px;'>";
    $result = $conn->query("SELECT userName, rating, comment FROM reviews ORDER BY id DESC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div style='width: 300px; border: 1px solid #ddd; border-radius: 12px; padding: 20px; background-color:rgb(245, 224, 250); color: black; box-shadow: 0 4px 8px rgb(202, 129, 250);'>";
            echo "<strong style='color:rgb(121, 0, 140); text-align: center;'>" . htmlspecialchars($row['userName']) . "</strong><br>";
            echo "<span style='color:rgb(81, 0, 97);'>Rating: " . htmlspecialchars($row['rating']) . "</span>";
            echo "<p style='color:rgb(82, 0, 97);'>" . nl2br(htmlspecialchars($row['comment'])) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align: center; color:rgb(0, 0, 0);'>No reviews yet. Be the first to review!</p>";
    }
    $conn->close();
    ?>
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
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-rbsA0mrUd3KArCY1S2iMfwubALqIoNHJOk5Hs9M38OexkTzE5jR5bBAc5UfnH3cZ" crossorigin="anonymous"></script>
</body>
</html>