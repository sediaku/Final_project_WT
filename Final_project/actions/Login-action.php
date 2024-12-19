<?php
include('../db/db-connection.php');
session_start();

$error = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

   
    $query = "SELECT * FROM user WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

           
            if ($user['role'] == 1) {
                header("Location: ../view/admin/user-dashboard.php");
                exit();
            } elseif ($user['role'] == 2) {
                header("Location: ../view/admin/doctor-dashboard.php");
                exit();
            } elseif ($user['role'] == 3) {
                header("Location: ../view/admin/admin-dashboard.php");
                exit();
            }else{
                $error = "Invalid user role.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "No user found with the provided username or email.";
    }

   
    $_SESSION['error'] = $error;
    header("Location: ../view/Login.php");
    exit();
}
