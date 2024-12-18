<?php
include '../../db/db-connection.php';

function submitHealthReading($userID, $reading_type, $reading_value, $unit, $notes, $conn) {
   
    $sql = "INSERT INTO monitor (userID, reading_type, reading_value, unit, notes) 
            VALUES ('$userID', '$reading_type', '$reading_value', '$unit', '$notes')";

    if ($conn->query($sql) === TRUE) {
        return "Data recorded successfully!";
    } else {
        return "Error: " . $conn->error;
    }
}

