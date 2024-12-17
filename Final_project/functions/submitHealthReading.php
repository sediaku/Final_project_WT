<?php
include '../../db/db-connection.php';

function submitHealthReading($userID, $reading_type, $reading_value, $unit, $notes, $conn) {
    // Insert the health data into the monitor table
    $sql = "INSERT INTO monitor (userID, reading_type, reading_value, unit, notes) 
            VALUES ('$userID', '$reading_type', '$reading_value', '$unit', '$notes')";

    if ($conn->query($sql) === TRUE) {
        return "Data recorded successfully!";
    } else {
        return "Error: " . $conn->error;
    }
}

