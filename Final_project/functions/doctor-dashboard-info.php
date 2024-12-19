<?php

function getDoctorData($userID, $conn) {
    
    $sqlDoctor = "SELECT u.fname, u.lname, d.* FROM doctor d
                  JOIN user u ON d.userID = u.userID
                  WHERE u.userID = ?";
    $stmtDoctor = $conn->prepare($sqlDoctor);
    $stmtDoctor->bind_param("i", $userID);  
    $stmtDoctor->execute();
    $resultDoctor = $stmtDoctor->get_result();
    return $resultDoctor->fetch_assoc();  
}



function getPatientsData($userID, $conn) {
    
    $sqlPatients = "SELECT u.fname, u.lname, p.* FROM patient p
                    JOIN user u ON p.userID = u.userID
                    WHERE p.doctorID IS NULL OR p.doctorID = (SELECT doctorID FROM doctor WHERE userID = ?)";
    $stmtPatients = $conn->prepare($sqlPatients);
    $stmtPatients->bind_param("i", $userID);  
    $stmtPatients->execute();
    return $stmtPatients->get_result();
}



function getUpcomingAppointments($conn) {
    $sql = "SELECT booking.bookingID, booking.booking_day, booking.booking_time, user.fname, user.lname 
            FROM booking 
            JOIN user_booking ON booking.bookingID = user_booking.bookingID 
            JOIN user ON user_booking.userID = user.userID 
            WHERE booking.booking_day >= CURDATE() 
            ORDER BY booking.booking_day ASC, booking.booking_time ASC";
    
    return $conn->query($sql);
}



