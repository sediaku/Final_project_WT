<?php
// Include the database connection
include '../db/db-connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user inputs and sanitize them
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $hospital = mysqli_real_escape_string($conn, $_POST['hospital']);
    $availability = mysqli_real_escape_string($conn, $_POST['availability']);

    // Validate inputs
    if (empty($specialization) || empty($hospital) || empty($availability)) {
        echo "All fields are required. Please fill in the form completely.";
        exit();
    }

    // Insert the doctor's details into the database
    $sql = "INSERT INTO doctor (specialization, hospital, availability, status) VALUES ('$specialization', '$hospital', '$availability', 'active')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the doctor dashboard upon successful insertion
        header("Location: ../view/admin/doctor-dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

