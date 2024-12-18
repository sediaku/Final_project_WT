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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .main-content {
            padding: 20px;
            border-radius: 8px;
            background-color: #e0f7fa;
        }
        h1, h2 {
            color: #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container main-content">
        <header>
            <h1>Medication Reminders</h1>
        </header>
        <main>
            <section id="medication-reminders">
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
                    <p>No notifications for today.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>
    <footer class="footer">
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>