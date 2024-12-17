<?php
include '../../db/db-connection.php';

function getUpcomingAppointments($userID) {
    global $conn;
    $sql = "SELECT b.booking_day, b.booking_time, u.fname, u.lname
        FROM booking b
        JOIN user_booking ub ON b.bookingID = ub.bookingID
        JOIN doctor d ON ub.userID = d.userID
        JOIN user u ON d.userID = u.userID
        WHERE ub.userID = ?";

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


function bookAppointment($userID, $doctor, $date, $time) {
    global $conn;
    
    $doctorQuery = "SELECT doctorID FROM doctor WHERE fname = ?";
    $stmt = $conn->prepare($doctorQuery);
    $stmt->bind_param("s", $doctor);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctorID = $result->fetch_assoc()['doctorID'];


    $insertBookingQuery = "INSERT INTO booking (booking_day, booking_time) VALUES (?, ?)";
    $stmt = $conn->prepare($insertBookingQuery);
    $stmt->bind_param("ss", $date, $time);
    $stmt->execute();

    
    $bookingID = $conn->insert_id;

    
    $insertUserBookingQuery = "INSERT INTO user_booking (userID, bookingID) VALUES (?, ?)";
    $stmt = $conn->prepare($insertUserBookingQuery);
    $stmt->bind_param("ii", $userID, $bookingID);
    $stmt->execute();
}
?>
