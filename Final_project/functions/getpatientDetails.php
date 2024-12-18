<?php

function getPatientData($patientID, $conn) {
   
    $userIDQuery = "SELECT userID FROM patient WHERE patientID = ?";
    $stmt = $conn->prepare($userIDQuery);
    if ($stmt === false) {
        error_log("User ID Query Prepare Error: " . $conn->error);
        throw new Exception("Failed to prepare user ID query: " . $conn->error);
    }
    $stmt->bind_param('i', $patientID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        throw new Exception("No user found for patient ID: $patientID");
    }
    
    $userData = $result->fetch_assoc();
    $userID = $userData['userID'];

    // Patient Query
    $patientQuery = "SELECT user.fname, user.lname, patient.disease, patient.doctors_notes
                     FROM patient
                     INNER JOIN user ON patient.userID = user.userID
                     WHERE patient.patientID = ?";
    
    $stmt = $conn->prepare($patientQuery);
    if ($stmt === false) {
        error_log("Patient Query Prepare Error: " . $conn->error);
        throw new Exception("Failed to prepare patient query: " . $conn->error);
    }
    $stmt->bind_param('i', $patientID);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    
    $healthDataQuery = "SELECT updated_at, reading_value, unit, notes AS patient_notes
                        FROM monitor
                        WHERE userID = ?
                        ORDER BY updated_at DESC LIMIT 3";
    
    $stmt = $conn->prepare($healthDataQuery);
    if ($stmt === false) {
        error_log("Health Data Query Prepare Error: " . $conn->error);
        throw new Exception("Failed to prepare health data query: " . $conn->error);
    }
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $healthDataResult = $stmt->get_result();
    
    $healthData = [];
    while ($row = $healthDataResult->fetch_assoc()) {
        $healthData[] = $row;
    }

   
    $medicationQuery = "SELECT medication.medication, medication.dosage, medication.unit, medication.timing
                        FROM medication
                        INNER JOIN patient_medicine ON medication.medicationID = patient_medicine.medicationID
                        WHERE patient_medicine.patientID = ?";
    
    $stmt = $conn->prepare($medicationQuery);
    if ($stmt === false) {
        error_log("Medication Query Prepare Error: " . $conn->error);
        throw new Exception("Failed to prepare medication query: " . $conn->error);
    }
    $stmt->bind_param('i', $patientID);
    $stmt->execute();
    $medicationResult = $stmt->get_result();
    
    $medications = [];
    while ($row = $medicationResult->fetch_assoc()) {
        $medications[] = $row;
    }

   
    $appointmentQuery = "SELECT booking.booking_day, doctor.doctorID
                         FROM booking
                         INNER JOIN patient ON booking.bookingID = patient.bookingID
                         INNER JOIN doctor ON patient.doctorID = doctor.doctorID
                         WHERE patient.patientID = ?
                         ORDER BY booking.booking_day DESC";
    
    $stmt = $conn->prepare($appointmentQuery);
    if ($stmt === false) {
        error_log("Appointment Query Prepare Error: " . $conn->error);
        throw new Exception("Failed to prepare appointment query: " . $conn->error);
    }
    $stmt->bind_param('i', $patientID);
    $stmt->execute();
    $appointmentResult = $stmt->get_result();
    
    $appointments = [];
    while ($row = $appointmentResult->fetch_assoc()) {
        $appointments[] = $row;
    }

    return [
        'patient' => $patient,
        'healthData' => $healthData,
        'medications' => $medications,
        'appointments' => $appointments
    ];
}