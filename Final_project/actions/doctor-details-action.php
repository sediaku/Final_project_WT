<?php

include '../db/db-connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $hospital = mysqli_real_escape_string($conn, $_POST['hospital']);
    $availability = mysqli_real_escape_string($conn, $_POST['availability']);

    
    if (empty($specialization) || empty($hospital) || empty($availability)) {
        echo "All fields are required. Please fill in the form completely.";
        exit();
    }

    
    $sql = "INSERT INTO doctor (specialization, hospital, availability, status) VALUES ('$specialization', '$hospital', '$availability', 'active')";

    if ($conn->query($sql) === TRUE) {
       
        header("Location: ../view/admin/doctor-dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

