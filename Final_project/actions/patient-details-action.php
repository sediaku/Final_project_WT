<?php
// Include the database connection
include '../db/db-connection.php';

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user inputs
    $disease = mysqli_real_escape_string($conn, $_POST['disease']);
    $allergies = mysqli_real_escape_string($conn, $_POST['allergies']);
    $medicalHistory = mysqli_real_escape_string($conn, $_POST['medicalHistory']);
    $monitoring_freq = mysqli_real_escape_string($conn, $_POST['monitoring_freq']);

    // Ensure the user is logged in
    if (!isset($_SESSION['userID'])) {
        echo "User is not logged in.";
        exit();
    }

    // Get the logged-in user's ID
    $userID = $_SESSION['userID'];

    // Validate required fields
    if ($disease === "select" || empty($allergies) || empty($medicalHistory) || empty($monitoring_freq)) {
        echo "All fields are required. Please fill in the form completely.";
        exit();
    }

    // Insert data into the `patient` table
    $sql = "INSERT INTO patient (userID, disease, allergies, medicalHistory, monitoring_freq, status) 
            VALUES ('$userID', '$disease', '$allergies', '$medicalHistory', '$monitoring_freq', 'active')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the user dashboard
        header("Location: ../view/admin/user-dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
