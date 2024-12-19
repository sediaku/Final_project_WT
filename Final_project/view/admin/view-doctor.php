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

        .doctor-info {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            border-left: 4px solid #1e3a8a;
        }

        .doctor-info h3 {
            color: #1e3a8a;
            margin: 0 0 25px 0;
            font-size: 1.5rem;
        }

        .doctor-info p {
            margin-bottom: 15px;
            font-size: 1.1em;
            line-height: 1.6;
        }

        .doctor-info strong {
            color: #1e3a8a;
            font-weight: 600;
        }

        .btn-back {
            background-color: #1e3a8a;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            font-size: 1.1em;
            transition: background-color 0.2s ease;
        }

        .btn-back:hover {
            background-color: #1e40af;
            color: white;
            text-decoration: none;
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
            .doctor-info {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Doctor Details</h1>
        <div class="icons">
            <span class="material-icons" onclick="window.location.href='notifications.php'">notifications</span>
            <span class="material-icons" onclick="window.location.href='profile.php'">account_circle</span>
            <span class="material-icons" onclick="window.location.href='../Logout.php'">logout</span>
        </div>
    </header>

    <main>
        <div class="doctor-info">
            <h3>Doctor Information</h3>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($doctor['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['email']); ?></p>
            <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialization']); ?></p>
        </div>

        <a href="admin-dashboard.php" class="btn-back">Back to Dashboard</a>
    </main>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


