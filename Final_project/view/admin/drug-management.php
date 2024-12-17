<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../view/Login.php');
    exit();
}

include '../../db/db-connection.php';  
include '../../functions/fetch_medication.php';  

$userID = $_SESSION['userID'];
$medications = getMedications($userID, $conn); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drug Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #1e3a8a;
            color: white;
            padding: 30px;
            text-align: center;
        }

        header h1 {
            margin-bottom: 15px;
        }

        main {
            padding: 30px 15px;
            flex-grow: 1;
        }

        section {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            margin-bottom: 20px;
        }

        footer {
            background-color: #1e3a8a;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
        }

        footer p {
            margin: 0;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        ul li {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        ul li:last-child {
            border-bottom: none;
        }

        .no-data {
            color: #999;
        }
    </style>
</head>
<body>
    <header>
        <h1>Drug Management</h1>
    </header>
    <main>
        <section>
            <h2>Current Medications</h2>
            <?php if ($medications): ?>
                <ul>
                    <?php foreach ($medications as $medication): ?>
                        <li>
                            <?php echo htmlspecialchars($medication['medication']); ?> - 
                            <?php echo htmlspecialchars($medication['dosage']); ?><?php echo htmlspecialchars($medication['unit']); ?>, 
                            <?php echo htmlspecialchars($medication['day']); ?> at <?php echo date('h:i A', strtotime($medication['time'])); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-data">No medications yet.</p>
            <?php endif; ?>
        </section>
        <section>
            <h2>Upcoming Reminders</h2>
            <p class="no-data">You have no reminders set for the next 24 hours.</p>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
