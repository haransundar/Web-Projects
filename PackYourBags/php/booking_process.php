<?php
include 'db_connect.php'; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $destination = $conn->real_escape_string($_POST['destination']);
    $date = $conn->real_escape_string($_POST['date']);

    $sql = "INSERT INTO bookings (name, email, destination, date) VALUES ('$name', '$email', '$destination', '$date')";

    if ($conn->query($sql) === TRUE) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
