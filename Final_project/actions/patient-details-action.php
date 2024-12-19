<?php

include '../db/db-connection.php';


session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $disease = mysqli_real_escape_string($conn, $_POST['disease']);
    $allergies = mysqli_real_escape_string($conn, $_POST['allergies']);
    $medicalHistory = mysqli_real_escape_string($conn, $_POST['medicalHistory']);
    $monitoring_freq = mysqli_real_escape_string($conn, $_POST['monitoring_freq']);

   
    if (!isset($_SESSION['userID'])) {
        echo "User is not logged in.";
        exit();
    }

   
    $userID = $_SESSION['userID'];

   
    if ($disease === "select" || empty($allergies) || empty($medicalHistory) || empty($monitoring_freq)) {
        echo "All fields are required. Please fill in the form completely.";
        exit();
    }

  
    $sql = "INSERT INTO patient (userID, disease, allergies, medicalHistory, monitoring_freq, status) 
            VALUES ('$userID', '$disease', '$allergies', '$medicalHistory', '$monitoring_freq', 'active')";

    if ($conn->query($sql) === TRUE) {
       
        header("Location: ../view/admin/user-dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
