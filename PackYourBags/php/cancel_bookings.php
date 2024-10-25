<?php

header('Content-Type: application/json');

try {
    // Include your database connection file
    include 'db_connect.php';

    // Get the incoming request data and decode it as JSON
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['bookingIds']) && is_array($data['bookingIds'])) {
        $bookingIds = $data['bookingIds'];

        // Sanitize the IDs to ensure they're all integers
        $bookingIds = array_map('intval', $bookingIds);

        // Check if there are valid booking IDs
        if (!empty($bookingIds)) {
            // Prepare a placeholder for the IN clause
            $placeholders = implode(',', array_fill(0, count($bookingIds), '?'));

            // Prepare the SQL delete query
            $sql = "DELETE FROM bookings WHERE id IN ($placeholders)";
            $stmt = $conn->prepare($sql);

            // Check if the SQL statement prepared correctly
            if ($stmt === false) {
                throw new Exception("SQL preparation error: " . $conn->error);
            }

            // Bind the booking IDs to the SQL query
            $stmt->bind_param(str_repeat('i', count($bookingIds)), ...$bookingIds); // Bind all booking IDs as integers

            // Execute the query and check for success
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception("Error executing SQL: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("No valid booking IDs provided");
        }
    } else {
        throw new Exception("Invalid input");
    }
} catch (Exception $e) {
    // Catch any exception and return a JSON error response
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
