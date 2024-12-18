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
        case 'Diabetes':
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
    <title>User Dashboard - Disease Management</title>
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
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        h2 {
            color: #007bff;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        .card {
            margin: 10px 0;
            border: 1px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container main-content">
        <div class="header">
            <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
            <div>
                <a href="notifications.php" class="text-white">Notifications</a> | 
                <a href="profile.php" class="text-white">Profile</a> | 
                <a href="../Logout.php" class="text-white">Logout</a>
            </div>
        </div>

        <div id="overview">
            <h2>Overview</h2>
            <p>Your health at a glance:</p>
            <ul class="list-group">
                <?php
                if ($disease === 'Hypertension') {
                    echo "<li class='list-group-item'>Last BP Reading: " . ($latestMonitorData ? $latestMonitorData['reading_value'] . " " . $latestMonitorData['unit'] : "No data available") . "</li>";
                } elseif ($disease === 'Diabetes') {
                    echo "<li class='list-group-item'>Glucose Level: " . ($latestMonitorData ? $latestMonitorData['reading_value'] . " mg/dL" : "No data available") . "</li>";
                } elseif ($disease === 'Asthma') {
                    echo "<li class='list-group-item'>Asthma Overview: Manage your breathing and triggers.</li>";
                } elseif ($disease === 'Chronic Kidney Disease') {
                    echo "<li class='list-group-item'>Kidney Function Overview: Track your kidney health regularly.</li>";
                }
                ?>
                <li class='list-group-item'>Next Appointment: 
                    <?php 
                    echo $nextAppointment ? date('F j, Y', strtotime($nextAppointment['booking_day'])) : "No upcoming appointments"; 
                    ?>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-6">
                <a href="lifestyle.php">
                    <div class="card">
                        <div class="card-body">
                            <h2>Lifestyle Tips</h2>
                            <p>Stay hydrated and exercise daily for 30 minutes.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="drug-management.php">
                    <div class="card">
                        <div class="card-body">
                            <h2>Drug Management</h2>
                            <p>Upcoming Medication Reminder: 
                                <?php 
                                echo $medicationReminder ? $medicationReminder['medication'] . " at " . date('h:i A', strtotime($medicationReminder['timing'])) : "No medication reminders set"; 
                                ?>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="?action=health_data">
                    <div class="card">
                        <div class="card-body">
                            <h2>Health Data</h2>
                            <p>Track and upload your BP and glucose readings here.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="bookings.php">
                    <div class="card">
                        <div class="card-body">
                            <h2>Appointments</h2>
                            <p>Book or manage your appointments with your doctor.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>