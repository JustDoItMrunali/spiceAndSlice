<?php
$host = "localhost";
$user = "root";      // Your MySQL username
$pass = "";          // Your MySQL password
$db = "tiffin_service";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
