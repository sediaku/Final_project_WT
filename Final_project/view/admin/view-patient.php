<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../Login.php');
    exit();
}

include '../../db/db-connection.php';

if (isset($_GET['userID'])) {
    $userID = intval($_GET['userID']); 
    
    // Fetch patient details
    $sql = "
        SELECT p.userID, CONCAT(u.fname, ' ', u.lname) AS full_name, p.disease, u.email, p.allergies, p.medicalHistory, p.status, p.`doctors-notes`
        FROM patient p
        JOIN user u ON p.userID = u.userID
        WHERE p.userID = ?
    ";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('MySQL prepare error: ' . $conn->error);
    }
    
    // Bind parameters and execute
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        echo "Patient not found.";
        exit();
    }

    // Fetch medications
    $medications_sql = "
        SELECT medication, dosage, unit, timing 
        FROM medication
        WHERE userID = ?
    ";
    $medications_stmt = $conn->prepare($medications_sql);
    if (!$medications_stmt) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $medications_stmt->bind_param('i', $userID);
    $medications_stmt->execute();
    $medications_result = $medications_stmt->get_result();

    // Fetch recent monitor readings
    $monitor_sql = "
        SELECT reading_type, reading_value, unit, notes, updated_at
        FROM monitor
        WHERE userID = ?
        ORDER BY updated_at DESC LIMIT 3
    ";
    $monitor_stmt = $conn->prepare($monitor_sql);
    if (!$monitor_stmt) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $monitor_stmt->bind_param('i', $userID);
    $monitor_stmt->execute();
    $monitor_result = $monitor_stmt->get_result();

} else {
    echo "No patient ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
        .patient-info, .medication-info, .monitor-info, .doctor-notes {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .patient-info h3, .medication-info h3, .monitor-info h3, .doctor-notes h3 {
            margin-bottom: 20px;
        }
        .btn-blue {
            background-color: #007bff;
            color: white;
            border: none;
        }
        .btn-blue:hover {
            background-color: #0056b3;
        }
        .monitor-item {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="patient-info">
            <h3>Patient Information</h3>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($patient['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['email']); ?></p>
            <p><strong>Disease:</strong> <?php echo htmlspecialchars($patient['disease']); ?></p>
            <p><strong>Allergies:</strong> <?php echo htmlspecialchars($patient['allergies']); ?></p>
            <p><strong>Medical History:</strong> <?php echo htmlspecialchars($patient['medicalHistory']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($patient['status']); ?></p>
        </div>

        <div class="medication-info">
            <h3>Medications</h3>
            <?php if ($medications_result->num_rows > 0) : ?>
                <ul>
                    <?php while ($medication = $medications_result->fetch_assoc()) : ?>
                        <li><strong><?php echo htmlspecialchars($medication['medication']); ?></strong> - 
                            <?php echo htmlspecialchars($medication['dosage']); ?> <?php echo htmlspecialchars($medication['unit']); ?> 
                            (Timing: <?php echo htmlspecialchars($medication['timing']); ?>)
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else : ?>
                <p>No medications available.</p>
            <?php endif; ?>
        </div>

        <div class="monitor-info">
            <h3>Recent Readings</h3>
            <?php if ($monitor_result->num_rows > 0) : ?>
                <?php while ($monitor = $monitor_result->fetch_assoc()) : ?>
                    <div class="monitor-item">
                        <p><strong><?php echo htmlspecialchars($monitor['reading_type']); ?>:</strong> 
                            <?php echo htmlspecialchars($monitor['reading_value']); ?> <?php echo htmlspecialchars($monitor['unit']); ?>
                        </p>
                        <p><strong>Notes:</strong> <?php echo htmlspecialchars($monitor['notes']); ?></p>
                        <p><strong>Last Updated:</strong> <?php echo htmlspecialchars($monitor['updated_at']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No recent readings available.</p>
            <?php endif; ?>
        </div>

        <div class="doctor-notes">
            <h3>Doctor's Notes</h3>
            <p><?php echo htmlspecialchars($patient['doctors-notes']); ?></p>
        </div>

        <a href="admin-dashboard.php" class="btn btn-blue">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
