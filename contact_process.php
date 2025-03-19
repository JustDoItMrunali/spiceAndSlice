<?php
session_start(); // Start session to access stored user data

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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch the correct name from session
    $name = isset($_SESSION['username']) ? mysqli_real_escape_string($conn, $_SESSION['username']) : '';
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Insert data into the database
    $sql = "INSERT INTO contact_us (name, email, phone, comment) 
            VALUES ('$name', '$email', '$phone', '$comment')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message sent successfully!'); window.location.href='contactUs.html';</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "\\n" . $conn->error . "'); window.location.href='contactUs.html';</script>";
    }
}

$conn->close();
?>
