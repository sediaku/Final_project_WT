<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header('Location: ../Login.php');
    exit();
}

include('../../db/db-connection.php');

$userID = $_SESSION['userID'];


$sql = "SELECT disease FROM patient WHERE userID = $userID";
$result = $conn->query($sql);
$diseaseData = $result->fetch_assoc();
$disease = $diseaseData['disease'];


if (isset($_GET['action']) && $_GET['action'] === 'health_data') {
    switch ($disease) {
        case 'Hypertension':
            header('Location: hypertension.php');
            exit();
        case 'Diabetes Type 1':
            header('Location: diabetes.php');
            exit();
        case 'Diabetes Type 2':
            header('Location: diabetes.php');
            exit();
        case 'Asthma':
            header('Location: asthma.php');
            exit();
        case 'Chronic Kidney Disease':
            header('Location: kidney.php');
            exit();
        default:
            header('Location: hypertension.php');
            exit();
    }
}

$sql = "SELECT * FROM monitor WHERE userID = $userID ORDER BY updated_at DESC LIMIT 1";
$result = $conn->query($sql);
$latestMonitorData = $result->fetch_assoc();


$sql = "SELECT booking.booking_day, booking.booking_time 
        FROM booking
        JOIN user_booking ON user_booking.bookingID = booking.bookingID
        WHERE user_booking.userID = $userID
        ORDER BY booking.booking_day ASC LIMIT 1";
$result = $conn->query($sql);
$nextAppointment = $result->fetch_assoc();


$sql = "SELECT * FROM medication WHERE userID = $userID ORDER BY timing ASC LIMIT 1";
$result = $conn->query($sql);
$medicationReminder = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Disease Management</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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

        .overview-section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .overview-section h2 {
            color: #1e3a8a;
            margin-top: 0;
        }

        .health-stats {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
        }

        .health-stats p {
            margin: 8px 0;
            color: #1e3a8a;
            font-weight: 500;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); 
            gap: 20px;
            margin-top: 20px;
        }

        .dashboard-card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            text-decoration: none;
            color: inherit;
            border-left: 4px solid #1e3a8a; 
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            text-decoration: none;
            color: inherit;
        }

        .dashboard-card h3 {
            color: #1e3a8a;
            margin: 0 0 10px 0;
        }

        .dashboard-card p {
            margin: 0;
            color: #666;
        }

        .card-lifestyle { border-left-color: #4CAF50; }
        .card-medication { border-left-color: #2196F3; }
        .card-health { border-left-color: #FF9800; }
        .card-appointments { border-left-color: #9C27B0; }

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
            .dashboard-grid {
                grid-template-columns: 1fr; 
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Patient Dashboard</h1>
        <div class="icons">
            <span class="material-icons" onclick="window.location.href='notifications.php'">notifications</span>
            <span class="material-icons" onclick="window.location.href='profile.php'">account_circle</span>
            <span class="material-icons" onclick="window.location.href='../Logout.php'">logout</span>
        </div>
    </header>

    <main>
        <section class="overview-section">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <div class="health-stats">
                <?php
                if ($disease === 'Hypertension') {
                    echo "<p>Last BP Reading: " . ($latestMonitorData ? $latestMonitorData['reading_value'] . " " . $latestMonitorData['unit'] : "No data available") . "</p>";
                } elseif ($disease === 'Diabetes Type 1') {
                    echo "<p>Glucose Level: " . ($latestMonitorData ? $latestMonitorData['reading_value'] . " mg/dL" : "No data available") . "</p>";
                } elseif ($disease === 'Asthma') {
                    echo "<p>Airflow: " . ($latestMonitorData ? $latestMonitorData['reading_value'] . " mg/dL" : "No data available") . "</p>";
                } elseif ($disease === 'Chronic Kidney Disease') {
                    echo "<p>Last BP Reading: " . ($latestMonitorData ? $latestMonitorData['reading_value'] . " " . $latestMonitorData['unit'] : "No data available") . "</p>";
                    echo "<p>Last BMI Reading: " . ($latestMonitorData ? $latestMonitorData['reading_value'] . " " . $latestMonitorData['unit'] : "No data available") . "</p>";
                }
                ?>
                <p>Next Appointment: <?php echo $nextAppointment ? date('F j, Y', strtotime($nextAppointment['booking_day'])) : "No upcoming appointments"; ?></p>
                <?php if ($medicationReminder): ?>
                <p>Next Medication: <?php echo $medicationReminder['medication'] . " at " . date('h:i A', strtotime($medicationReminder['timing'])); ?></p>
                <?php endif; ?>
            </div>
        </section>

        <div class="dashboard-grid">
            <a href="lifestyle.php" class="dashboard-card card-lifestyle">
                <h3>Lifestyle Tips</h3>
                <p>Get personalized recommendations for maintaining a healthy lifestyle</p>
            </a>

            <a href="drug-management.php" class="dashboard-card card-medication">
                <h3>Drug Management</h3>
                <p>Track and manage your medications and schedules</p>
            </a>

            <a href="?action=health_data" class="dashboard-card card-health">
                <h3>Health Data</h3>
                <p>Monitor and record your vital health measurements</p>
            </a>

            <a href="bookings.php" class="dashboard-card card-appointments">
                <h3>Appointments</h3>
                <p>Schedule and manage your doctor appointments</p>
            </a>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>
</body>
</html>