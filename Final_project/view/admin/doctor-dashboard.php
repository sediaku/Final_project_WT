<?php
session_start();

include '../../db/db-connection.php';
include '../../functions/doctor-dashboard-info.php';

if (!isset($_SESSION['userID'])) {
    header('Location: ../view/Login.php');
    exit();
}

$userID = $_SESSION['userID'];

$doctor = getDoctorData($userID, $conn);
$resultPatients = getPatientsData($userID, $conn);
$resultAppointments = getUpcomingAppointments($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's Dashboard</title>
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
            text-align: center;
        }

        header h1 {
            margin-bottom: 10px;
        }

        header a {
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 0 10px;
        }

        header a:hover {
            background-color: #374151;
            border-radius: 5px;
        }

        #overview {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #overview h2 {
            color: #1e3a8a;
        }

        #patients {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #patients h2 {
            color: #1e3a8a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f1f5f9;
        }

        td a {
            color: #1e3a8a;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        #appointments {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #appointments h2 {
            color: #1e3a8a;
        }

        #appointments ul {
            list-style-type: none;
            padding: 0;
        }

        #appointments li {
            background-color: #f9fafb;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        }

        #health-data {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #health-data h2 {
            color: #1e3a8a;
        }

        #health-data a {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: bold;
        }

        #health-data a:hover {
            text-decoration: underline;
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

        .icons {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .icons span {
            font-size: 25px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome, Dr. <?php echo htmlspecialchars($doctor['fname']) . " " . htmlspecialchars($doctor['lname']); ?></h1>
    <div class="icons">
        <span class="material-icons" onclick="window.location.href='doctor_profile.php'">account_circle</span>
        <span class="material-icons" onclick="window.location.href='../Logout.php'">logout</span>
    </div>
</header>

<main>

    <section id="overview">
        <h2>Dashboard Overview</h2>
        <p>Manage patient health data, appointments, and treatment plans.</p>
    </section>

    <section id="patients">
        <h2>Patients</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Condition</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($patient = $resultPatients->fetch_assoc()) {
                    $patientID = $patient['patientID'];
                    $resultHealthData = getHealthData($patientID, $conn);
                    $healthData = $resultHealthData->fetch_assoc();
                    $lastVisit = $healthData ? date('M d, Y', strtotime($healthData['updated_at'])) : 'N/A';
                    echo "<tr>
                            <td>" . htmlspecialchars($patient['fname']) . " " . htmlspecialchars($patient['lname']) . "</td>
                            <td>" . htmlspecialchars($patient['disease']) . "</td>
                            <td><a href='view-patient.php?patientID=" . $patient['patientID'] . "'>View</a></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <section id="appointments">
        <h2>Upcoming Appointments</h2>
        <ul>
            <?php
            while ($appointment = $resultAppointments->fetch_assoc()) {
                echo "<li>" . date('M d, Y', strtotime($appointment['booking_day'])) . " - " . htmlspecialchars($appointment['fname']) . " " . htmlspecialchars($appointment['lname']) . " at " . date('g:i A', strtotime($appointment['booking_time'])) . "</li>";
            }
            ?>
        </ul>
    </section>

    <section id="health-data">
        <h2>Patient Health Data</h2>
        <p>Access uploaded health data (e.g., blood pressure, glucose levels) from your patients.</p>
        <a href="health-data.html">View Health Data</a>
    </section>

</main>

<footer>
    <p>&copy; 2024 Disease Management Platform</p>
</footer>

</body>
</html>
