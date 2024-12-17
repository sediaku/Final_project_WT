<?php
include('../db/db-connection.php');
session_start();

$error = ''; // Initialize an error variable to store the error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check for username or email
    $query = "SELECT * FROM user WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Debugging: Print hashed and entered password
        // echo "Stored Hash: " . $user['password'] . "<br>";
        // echo "Entered Password: " . $password . "<br>";
        
        // Check if password matches
        if (password_verify($password, $user['password'])) {
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] == 1) {
                header("Location: ../view/admin/user-dashboard.php");
                exit();
            } elseif ($user['role'] == 2) {
                header("Location: ../view/admin/doctor-dashboard.php");
                exit();
            } else {
                $error = "Invalid user role.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "No user found with the provided username or email.";
    }

    // Store error in session and redirect
    $_SESSION['error'] = $error;
    header("Location: ../view/Login.php");
    exit();
}
