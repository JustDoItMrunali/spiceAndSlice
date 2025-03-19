<?php
// Database connection variables
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "tiffin_service"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format. Please try again.'); window.location.href='register.html';</script>";
        exit();
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check for existing username or email
    $checkQuery = "SELECT * FROM register1 WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or email already exists. Please try again.'); window.location.href='register.html';</script>";
        exit();
    }

    // Insert user data into the database
    $sql = "INSERT INTO register1 (name, username, email, password) 
            VALUES ('$name', '$username', '$email', '$hashed_password')";

    // Display success message if registration is successful
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Registered successfully! Welcome, $username');
            window.location.href = 'login.html';
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>
