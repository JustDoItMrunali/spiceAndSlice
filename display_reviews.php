<?php
$conn = new mysqli('localhost', 'root', '', 'tiffin_service');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT userName, rating, comment FROM reviews ORDER BY id DESC");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='review-item'>";
        echo "<strong>" . htmlspecialchars($row['userName']) . "</strong> ";
        echo "<span>Rating: " . htmlspecialchars($row['rating']) . " / 5</span>";
        echo "<p>" . htmlspecialchars($row['comment']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No reviews yet.</p>";
}

$conn->close();
?>
