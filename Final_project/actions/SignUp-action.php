<?php
// Include the database connection
include '../db/db-connection.php';

// Start the session to store user data
session_start();

// Sanitize and validate input data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : NULL;
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = 1; // Default role is patient

    // Check if user is a doctor or patient
    if (isset($_POST['doctor'])) {
        $role = 2; // Doctor role
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // If no username is provided, you can either leave it as NULL or generate a default one
    if ($username === NULL) {
        $username = $fname . '.' . $lname; // Default username
    }

    // Insert data into the 'user' table
    $sql = "INSERT INTO user (username, fname, lname, email, password, role) VALUES ('$username', '$fname', '$lname', '$email', '$hashedPassword', '$role')";

    if ($conn->query($sql) === TRUE) {
        $userID = $conn->insert_id; // Get the ID of the newly inserted user

        // Store user information in session
        $_SESSION['userID'] = $userID;  // Store the user ID
        $_SESSION['username'] = $username;  // Store the username
        $_SESSION['role'] = $role;  // Store the user role (patient or doctor)

        // Redirect based on role
        if ($role == 1) {
            // Redirect to patient dashboard or profile page
            header("Location: ../view/patient-details.php");
        } else {
            // Redirect to doctor details page
            header("Location: ../view/doctor-details.php");
        }
        exit();  // Ensure no further code is executed after redirection
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
