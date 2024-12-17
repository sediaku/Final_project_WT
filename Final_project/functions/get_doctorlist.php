<?php
include '../../db/db-connection.php';

// Function to get a list of doctors
function getDoctorsList() {
    global $conn;
    $sql = "SELECT u.userID, u.fname, u.lname 
            FROM doctor d
            JOIN user u ON d.userID = u.userID
            WHERE d.status = 'active'"; 
    $result = $conn->query($sql);
    $doctors = [];
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
    return $doctors;
}

