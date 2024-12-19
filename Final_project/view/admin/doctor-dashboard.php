<?php
session_start();

include '../../db/db-connection.php';
include '../../functions/doctor-dashboard-info.php';

if (!isset($_SESSION['userID'])) {
    header('Location: ../Login.php');
    exit();
}

$userID = $_SESSION['userID'];


$doctor = getDoctorData($userID, $conn);
$resultPatients = getPatientsData($userID, $conn);
$resultAppointments = getUpcomingAppointments($conn);


$patientCount = $resultPatients->num_rows;


$todayAppointments = 0;
$today = date('Y-m-d');
$resultAppointments->data_seek(0);
while($row = $resultAppointments->fetch_assoc()) {
    if($row['booking_day'] == $today) {
        $todayAppointments++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
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

        .overview-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card.patients {
            border-left: 4px solid #4CAF50;
        }

        .card.appointments {
            border-left: 4px solid #2196F3;
        }

        .card h3 {
            margin: 0 0 10px 0;
            color: #1e3a8a;
        }

        .card .count {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .section h2 {
            color: #1e3a8a;
            margin-top: 0;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f1f5f9;
            color: #1e3a8a;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            background-color: #1e3a8a;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .appointments-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .appointment-item {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .appointment-item:last-child {
            border-bottom: none;
        }

        .appointment-time {
            color: #1e3a8a;
            font-weight: 600;
        }

        .patient-name {
            flex-grow: 1;
            margin: 0 15px;
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
            .overview-cards {
                grid-template-columns: 1fr;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            .appointment-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .appointment-time, .patient-name {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Doctor Dashboard</h1>
        <div class="icons">
            <span class="material-icons" onclick="window.location.href='notifications.php'">notifications</span>
            <span class="material-icons" onclick="window.location.href='doctor_profile.php'">account_circle</span>
            <span class="material-icons" onclick="window.location.href='../Logout.php'">logout</span>
        </div>
    </header>

    <main>
        <h2>Welcome, Dr. <?php echo htmlspecialchars($doctor['fname']) . " " . htmlspecialchars($doctor['lname']); ?></h2>

        <div class="overview-cards">
            <div class="card patients">
                <h3>Total Patients</h3>
                <div class="count"><?php echo $patientCount; ?></div>
            </div>
            <div class="card appointments">
                <h3>Today's Appointments</h3>
                <div class="count"><?php echo $todayAppointments; ?></div>
            </div>
        </div>

        <div class="section">
            <h2>Upcoming Appointments</h2>
            <div class="appointments-list">
                <?php
                $resultAppointments->data_seek(0);
                while ($appointment = $resultAppointments->fetch_assoc()) {
                    echo "<div class='appointment-item'>
                            <span class='appointment-time'>" . date('M d, Y', strtotime($appointment['booking_day'])) . " at " . 
                            date('g:i A', strtotime($appointment['booking_time'])) . "</span>
                            <span class='patient-name'>" . htmlspecialchars($appointment['fname']) . " " . 
                            htmlspecialchars($appointment['lname']) . "</span>
                          </div>";
                }
                ?>
            </div>
        </div>

        <div class="section">
            <h2>My Patients</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Condition</th>
                        <th>Last Visit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($patient = $resultPatients->fetch_assoc()) {
                        $disease = !empty($patient['disease']) ? htmlspecialchars($patient['disease']) : 'No data available';
                        echo "<tr>
                                <td>" . htmlspecialchars($patient['fname']) . "</td>
                                <td>" . $disease . "</td>
                                <td>" . (isset($patient['last_visit']) ? date('M d, Y', strtotime($patient['last_visit'])) : 'N/A') . "</td>
                                <td>
                                    <a href='view-patient.php?userID=" . $patient['userID'] . "' class='btn'>View Details</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>
</body>
</html>