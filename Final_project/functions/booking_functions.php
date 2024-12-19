<?php
include '../../db/db-connection.php';

function getUpcomingAppointments($userID) {
    global $conn;
    $sql = "SELECT b.booking_day, b.booking_time, d.fname AS doctor_fname, d.lname AS doctor_lname
    FROM booking b
    JOIN user_booking ub ON b.bookingID = ub.bookingID
    JOIN user d ON ub.userID = d.userID
    WHERE ub.userID != ?";  

    

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
    return $appointments;
}


function bookAppointment($userID, $doctorUserID, $date, $time) {
    global $conn;

    
    $insertBookingQuery = "INSERT INTO booking (booking_day, booking_time) VALUES (?, ?)";
    $stmt = $conn->prepare($insertBookingQuery);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ss", $date, $time);

    if (!$stmt->execute()) {
        die("Error inserting booking: " . $stmt->error);
    }

    $bookingID = $conn->insert_id; 
    $stmt->close();

   
    $insertUserBookingQuery = "INSERT INTO user_booking (userID, bookingID) VALUES (?, ?)";
    $stmt = $conn->prepare($insertUserBookingQuery);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ii", $userID, $bookingID);

    if (!$stmt->execute()) {
        die("Error inserting user booking: " . $stmt->error);
    }
    $stmt->close();

    
    $fetchDoctorIDQuery = "SELECT doctorID FROM doctor WHERE userID = ?";
    $stmt = $conn->prepare($fetchDoctorIDQuery);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $doctorUserID);

    if (!$stmt->execute()) {
        die("Error fetching doctor ID: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();
    $doctorID = $doctor['doctorID'];
    $stmt->close();

    
    $updatePatientBookingQuery = "UPDATE patient SET bookingID = ?, doctorID = ? WHERE userID = ?";
    $stmt = $conn->prepare($updatePatientBookingQuery);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("iii", $bookingID, $doctorID, $userID);

    if (!$stmt->execute()) {
        die("Error updating patient booking: " . $stmt->error);
    }
    $stmt->close();

    return "Appointment successfully booked!";
}
