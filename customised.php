<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Customizer</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: Arial, sans-serif; display: flex; flex-direction: column; align-items: center; padding: 20px; }
        .content { display: flex; width: 100%; max-width: 900px; gap: 20px; margin-top: 8px; }
        .meal-options, .meal-details { flex: 1; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .meal-option { margin: 10px 0; }
        .meal-option select { padding: 5px; width: 100%; }
        .btn { background-color: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; width: 100%; }
        .btn:hover { background-color: #45a049; }
        .proceed-btn { background-color: purple; color: white; border: none; padding: 10px 20px; cursor: pointer; margin-top: 20px; display: block; width: 200px; text-align: center; }
        .proceed-btn:hover { background-color: #800080; }
    </style>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
  
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
                    <li class="nav-item"><a class="nav-link" href="contactUs.html">Contact Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="save_review.php">Review</a></li>
                    <li class="nav-item"><a class="nav-link" href="customisable.html">Customised Meal</a></li>
                </ul>
                <div id="user-info" class="d-flex align-items-center gap-2">
                    <span id="user-name"></span>
                    <a id="logout-btn" class="btn btn-outline-danger round-button" 
                       style="display: none; color: red !important;" 
                       href="logout.php">Logout</a>
                    <button id="login-btn" class="btn btn-outline-primary round-button" 
                       onclick="location.href='login.html'">Login</button>
                </div>
                        
            </div>
        </div>
    </nav>
    <h1>Customize Your Meal</h1>
    <div class="content">
        <div class="meal-options">
            <form method="POST" action="payscript.php">
                <div class="meal-option">
                    <label for="dish">Dish:</label>
                    <select name="dish" id="dish" onchange="updateMeal()">
                        <option value="">Select a dish</option>
                        <option value="Paneer Masala" data-price="150">Paneer Masala - ₹150</option>
                        <option value="Butter Chicken" data-price="200">Butter Chicken - ₹200</option>
                    </select>
                </div>

                <div class="meal-option">
                    <label for="roti">Roti:</label>
                    <select name="roti" id="roti" onchange="updateMeal()">
                        <option value="">Select a roti</option>
                        <option value="Tandoori Roti" data-price="20">Tandoori Roti - ₹20</option>
                        <option value="Butter Naan" data-price="30">Butter Naan - ₹30</option>
                    </select>
                </div>
                <div class="meal-option">
    <label for="salad">Salad:</label>
    <select name="salad" id="salad" onchange="updateMeal()">
        <option value="">Select a salad</option>
        <option value="Vegetable Salad" data-price="20">Vegetable Salad - ₹20</option>
        <option value="Fruit Salad" data-price="30">Fruit Salad - ₹30</option>
    </select>
</div>

<div class="meal-option">
    <label for="pickle">Pickle:</label>
    <select name="pickle" id="pickle" onchange="updateMeal()">
        <option value="">Select a pickle</option>
        <option value="Mango Pickle" data-price="10">Mango Pickle - ₹10</option>
        <option value="Lemon Pickle" data-price="10">Lemon Pickle - ₹10</option>
    </select>
</div>

                <div id="meal-details" class="meal-details"></div>
                <input type="hidden" name="username" id="username">
                <input type="hidden" name="amount" id="total-amount">
                <button class="proceed-btn" type="submit">Proceed to Pay</button>
            </form>
        </div>
    </div>
    <script type="module">
  document.addEventListener("DOMContentLoaded", async () => {
    const userInfo = await fetchUserDetails();
    const userNameElement = document.getElementById("user-name");
    const loginBtn = document.getElementById("login-btn");
    const logoutBtn = document.getElementById("logout-btn");
    if (userInfo && userInfo.name) {
    document.getElementById('username').value = userInfo.name;
}
    // If the user is logged in, show their name, otherwise show "Guest"
    if (userInfo && userInfo.name) {
      userNameElement.textContent = `${userInfo.name}`;
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
    <script>
       function updateMeal() {
    const selections = ['dish', 'roti', 'salad', 'pickle'];
    let totalAmount = 0;
    let mealDetails = `<h2 style="color: black !important;">Your Customized Meal:</h2>`;

    selections.forEach(selection => {
        const element = document.getElementById(selection);
        const selectedOption = element.options[element.selectedIndex];
        const itemName = selectedOption.value || 'Not selected';
        const itemPrice = selectedOption.dataset.price ? `₹${selectedOption.dataset.price}` : '₹0';
        totalAmount += Number(selectedOption.dataset.price || 0);
        mealDetails += `<p style="color: black;">${selection.toUpperCase()}: ${itemName} - ${itemPrice}</p>`;
    });

    mealDetails += `<hr><h3 style="color: purple;">Total Amount: ₹${totalAmount}</h3>`;
    document.getElementById('meal-details').innerHTML = mealDetails;
    document.getElementById('total-amount').value = totalAmount;
}

    </script>
 
</body>
</html>
