<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../Login.php');
    exit();
}

include '../../db/db-connection.php';

if (isset($_GET['userID'])) {
    $userID = intval($_GET['userID']); 
    
   
    $sql = "
        SELECT p.userID, CONCAT(u.fname, ' ', u.lname) AS full_name, p.disease, u.email, p.allergies, p.medicalHistory, p.status, p.`doctors-notes`
        FROM patient p
        JOIN user u ON p.userID = u.userID
        WHERE p.userID = ?
    ";
    
  
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('MySQL prepare error: ' . $conn->error);
    }
    
    
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        echo "Patient not found.";
        exit();
    }

    
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #1e3a8a;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        .icons {
            display: flex;
            gap: 15px;
        }

        .icons span {
            font-size: 25px;
            cursor: pointer;
            color: white;
        }

        main {
            padding: 20px;
            flex-grow: 1;
        }

        .info-section {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-left: 4px solid #1e3a8a;
        }

        .info-section h3 {
            color: #1e3a8a;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .info-section p {
            margin-bottom: 12px;
            line-height: 1.6;
        }

        .info-section strong {
            color: #1e3a8a;
            font-weight: 600;
        }

        .medication-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .medication-list li {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            line-height: 1.6;
        }

        .medication-list li:last-child {
            border-bottom: none;
        }

        .monitor-item {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .monitor-item:last-child {
            margin-bottom: 0;
        }

        .monitor-item p {
            margin-bottom: 8px;
        }

        .monitor-item p:last-child {
            margin-bottom: 0;
        }

        .btn-back {
            background-color: #1e3a8a;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s ease;
        }

        .btn-back:hover {
            background-color: #1e40af;
            color: white;
        }

        footer {
            background-color: #1e3a8a;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: auto;
        }

        footer p {
            margin: 0;
        }

        @media (max-width: 768px) {
            .info-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Patient Details</h1>
        <div class="icons">
            <span class="material-icons" onclick="window.location.href='notifications.php'">notifications</span>
            <span class="material-icons" onclick="window.location.href='profile.php'">account_circle</span>
            <span class="material-icons" onclick="window.location.href='../Logout.php'">logout</span>
        </div>
    </header>

    <main>
        <div class="info-section">
            <h3>Patient Information</h3>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($patient['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['email']); ?></p>
            <p><strong>Disease:</strong> <?php echo htmlspecialchars($patient['disease']); ?></p>
            <p><strong>Allergies:</strong> <?php echo htmlspecialchars($patient['allergies']); ?></p>
            <p><strong>Medical History:</strong> <?php echo htmlspecialchars($patient['medicalHistory']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($patient['status']); ?></p>
        </div>

        <div class="info-section">
            <h3>Medications</h3>
            <?php if ($medications_result->num_rows > 0) : ?>
                <ul class="medication-list">
                    <?php while ($medication = $medications_result->fetch_assoc()) : ?>
                        <li>
                            <strong><?php echo htmlspecialchars($medication['medication']); ?></strong> - 
                            <?php echo htmlspecialchars($medication['dosage']); ?> <?php echo htmlspecialchars($medication['unit']); ?> 
                            (Timing: <?php echo htmlspecialchars($medication['timing']); ?>)
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else : ?>
                <p>No medications available.</p>
            <?php endif; ?>
        </div>

        <div class="info-section">
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

        <div class="info-section">
            <h3>Doctor's Notes</h3>
            <p><?php echo htmlspecialchars($patient['doctors-notes']); ?></p>
        </div>

        <a href="admin-dashboard.php" class="btn-back">Back to Dashboard</a>
    </main>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>