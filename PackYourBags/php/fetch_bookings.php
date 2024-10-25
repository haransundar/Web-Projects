<?php
include 'db_connect.php'; // Ensure this path is correct

// Fetch bookings from the database
$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);

$bookings = [];

if ($result->num_rows > 0) {
    // Fetch all rows as an associative array
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

echo json_encode($bookings); // Return the bookings as JSON

$conn->close(); // Close the database connection
?>
