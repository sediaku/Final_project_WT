<?php

include '../db/db-connection.php';


session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : NULL;
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = 1; 

    
    if (isset($_POST['doctor'])) {
        $role = 2;
    }

   
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    if ($username === NULL) {
        $username = $fname . '.' . $lname; 
    }

   
    $sql = "INSERT INTO user (username, fname, lname, email, password, role) VALUES ('$username', '$fname', '$lname', '$email', '$hashedPassword', '$role')";

    if ($conn->query($sql) === TRUE) {
        $userID = $conn->insert_id;

      
        $_SESSION['userID'] = $userID; 
        $_SESSION['username'] = $username; 
        $_SESSION['role'] = $role;  
       
        if ($role == 1) {
           
            header("Location: ../view/patient-details.php");
        } else {
           
            header("Location: ../view/doctor-details.php");
        }
        exit(); 
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
