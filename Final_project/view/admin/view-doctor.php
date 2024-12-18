<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../Login.php');
    exit();
}

include '../../db/db-connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    
    $sql = "
        SELECT d.doctorID, CONCAT(u.fname, ' ', u.lname) AS full_name, d.specialization, u.email 
        FROM doctor d
        JOIN user u ON d.userID = u.userID
        WHERE d.doctorID = ?
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $doctor = $result->fetch_assoc();
    } else {
        echo "Doctor not found.";
        exit();
    }
} else {
    echo "No doctor ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
        .doctor-info {
            padding: 30px;
            margin-bottom: 40px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .doctor-info h3 {
            margin-bottom: 25px;
        }
        .doctor-info p {
            margin-bottom: 15px;
            font-size: 1.1em;
        }
        .btn-blue {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1.1em;
            text-align: center;
            border-radius: 8px;
        }
        .btn-blue:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="doctor-info">
            <h3>Doctor Information</h3>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($doctor['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['email']); ?></p>
            <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialization']); ?></p>
        </div>

        <a href="admin-dashboard.php" class="btn btn-blue">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


