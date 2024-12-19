<?php
include '../../db/db-connection.php';

session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: ../Login.php");
    exit();
}

$userID = $_SESSION['userID']; 

$currentDay = date('l');

$medicationsQuery = "
    SELECT 
        medication, 
        dosage, 
        unit, 
        timing 
    FROM 
        medication 
    WHERE 
        userID = $userID";

$medicationsResult = mysqli_query($conn, $medicationsQuery);

if (!$medicationsResult) {
    die("Error fetching medications: " . mysqli_error($conn));
}

$filteredMedications = [];
while ($medication = mysqli_fetch_assoc($medicationsResult)) {
    $timingParts = explode(',', $medication['timing']);
    foreach ($timingParts as $timing) {
        if (stripos($timing, $currentDay) !== false) {
            $filteredMedications[] = [
                'medication' => $medication['medication'],
                'dosage' => $medication['dosage'],
                'unit' => $medication['unit'],
                'time' => trim(explode(' ', $timing)[1])
            ];
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication Reminders</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

        .medication-section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-left: 4px solid #1e3a8a;
        }

        .medication-section h2 {
            color: #1e3a8a;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .table {
            background-color: #ffffff;
            border-radius: 6px;
            overflow: hidden;
        }

        .table thead {
            background-color: #1e3a8a;
            color: white;
        }

        .table th {
            border-top: none;
            font-weight: 500;
        }

        .table td {
            vertical-align: middle;
        }

        .table-bordered {
            border: none;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #e2e8f0;
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

        .no-medications {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            color: #666;
        }

        @media (max-width: 768px) {
            .medication-section {
                padding: 15px;
            }
            
            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Medication Reminders</h1>
        <div class="icons">
            <span class="material-icons" onclick="window.location.href='notifications.php'">notifications</span>
            <span class="material-icons" onclick="window.location.href='profile.php'">account_circle</span>
            <span class="material-icons" onclick="window.location.href='../Logout.php'">logout</span>
        </div>
    </header>

    <main>
        <section class="medication-section">
            <h2>Your Medication Reminders for <?php echo $currentDay; ?></h2>
            <?php if (!empty($filteredMedications)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Medication</th>
                            <th>Dosage</th>
                            <th>Unit</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($filteredMedications as $medication): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($medication['medication']); ?></td>
                                <td><?php echo htmlspecialchars($medication['dosage']); ?></td>
                                <td><?php echo htmlspecialchars($medication['unit']); ?></td>
                                <td><?php echo htmlspecialchars($medication['time']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-medications">
                    <p>No medications scheduled for today.</p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>
</body>
</html>