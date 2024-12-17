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
    $sqlAppointments = "SELECT b.booking_day, b.booking_time, u.fname, u.lname 
                        FROM booking b 
                        JOIN user_booking ub ON b.bookingID = ub.bookingID
                        JOIN user u ON ub.userID = u.userID
                        WHERE b.booking_day >= CURDATE() 
                        ORDER BY b.booking_day ASC";
    return $conn->query($sqlAppointments);
}

function getHealthData($patientID, $conn) {
   
    $sqlHealthData = "SELECT reading_type, reading_value, updated_at 
                      FROM monitor 
                      WHERE userID = '$patientID' 
                      ORDER BY updated_at DESC LIMIT 1";
    return $conn->query($sqlHealthData);
}

