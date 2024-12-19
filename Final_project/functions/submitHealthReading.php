<?php
include '../../db/db-connection.php';

function submitHealthReading($userID, $reading_type, $reading_value, $unit, $notes, $conn) {
    $stmt = $conn->prepare("INSERT INTO monitor (userID, reading_type, reading_value, unit, notes) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        return "Error preparing statement: " . $conn->error;
    }
    $stmt->bind_param("issss", $userID, $reading_type, $reading_value, $unit, $notes);

    if ($stmt->execute()) {
        $stmt->close();
        return "Success";
    } else {
        $stmt->close();
        return "Error: " . $conn->error;
    }
}



