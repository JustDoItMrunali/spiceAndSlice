<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "tiffin_service";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, trim($_POST['email'])) : '';
    $input_password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Check if email and password are empty
    if (empty($email) || empty($input_password)) {
        echo "<script>alert('Please fill in all fields.'); window.location.href='login.html';</script>";
        exit;
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT * FROM register1 WHERE email = ?");
    if (!$stmt) {
        die("Error preparing SQL statement: " . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param("s", $email);

    // Execute query
    if (!$stmt->execute()) {
        die("Error executing query: " . htmlspecialchars($stmt->error));
    }

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Check if password matches
        if (password_verify($input_password, $user['password'])) {
            // Regenerate session ID for security
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['username'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            // Check domain for admin redirection
            if (strpos($email, '@admin.com') !== false) {
                echo "<script>alert('Admin login successful! Redirecting to admin page.'); window.location.href='admin.html';</script>";
            } else {
                echo "<script>alert('Login successful! Redirecting to home.'); window.location.href='index.html';</script>";
            }
        } else {
            // Incorrect password
            echo "<script>alert('Incorrect password. Please try again.'); window.location.href='login.html';</script>";
        }
    } else {
        // Email not found
        echo "<script>alert('Email not found. Please register first.'); window.location.href='register.html';</script>";
    }

    // Close statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
