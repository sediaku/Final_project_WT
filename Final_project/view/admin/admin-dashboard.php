<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../Login.php');
    exit();
}

include '../../db/db-connection.php';


$userID = $_SESSION['userID'];
$sql = "SELECT fname, lname FROM user WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userID);
$stmt->execute();
$userResult = $stmt->get_result();
$userData = $userResult->fetch_assoc();
$fullName = $userData['fname'] . ' ' . $userData['lname'];


$sql = "SELECT COUNT(*) AS patient_count FROM patient";
$result = $conn->query($sql);
$patientData = $result->fetch_assoc();
$patientCount = $patientData['patient_count'];

$sql = "SELECT COUNT(*) AS doctor_count FROM doctor";
$result = $conn->query($sql);
$doctorData = $result->fetch_assoc();
$doctorCount = $doctorData['doctor_count'];


$d_sql = "
    SELECT d.doctorID, CONCAT(u.fname, ' ', u.lname) AS full_name, d.specialization, u.email 
    FROM doctor d
    JOIN user u ON d.userID = u.userID
";
$doctorResult = $conn->query($d_sql);

$p_sql = "
    SELECT p.userID, CONCAT(u.fname, ' ', u.lname) AS full_name, p.disease, u.email 
    FROM patient p
    JOIN user u ON p.userID = u.userID
";
$patientResult = $conn->query($p_sql);

if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = intval($_GET['delete']);
    $type = $_GET['type'];
    
    if ($type === 'doctor') {
        $delete_sql = "DELETE FROM doctor WHERE doctorID = ?";
    } elseif ($type === 'patient') {
        $delete_sql = "DELETE FROM patient WHERE userID = ?";
    }

    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    header('Location: admin-dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .card.doctors {
            border-left: 4px solid #4CAF50;
        }

        .card.patients {
            border-left: 4px solid #2196F3;
        }

        .card h3 {
            margin: 0 0 10px 0;
            color: #666;
        }

        .card .count {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .data-section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .data-section h2 {
            color: #1e3a8a;
            margin-top: 0;
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
        }

        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            margin-right: 5px;
            display: inline-block;
        }

        .btn-primary {
            background-color: #1e3a8a;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
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
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <div class="icons">
            <span class="material-icons" onclick="window.location.href='admin_profile.php'">account_circle</span>
            <span class="material-icons" onclick="window.location.href='../Logout.php'">logout</span>
        </div>
    </header>

    <main>
        <h2>Welcome, <?php echo htmlspecialchars($fullName); ?></h2>

        <div class="overview-cards">
            <div class="card patients">
                <h3>Total Patients</h3>
                <div class="count"><?php echo $patientCount; ?></div>
            </div>
            <div class="card doctors">
                <h3>Total Doctors</h3>
                <div class="count"><?php echo $doctorCount; ?></div>
            </div>
        </div>

        <div class="data-section">
            <h2>Doctors</h2>
            <table>
                <thead>
                    <tr>
                        <th>Doctor ID</th>
                        <th>Full Name</th>
                        <th>Specialization</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($doctorResult->num_rows > 0) {
                        while ($row = $doctorResult->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['doctorID']}</td>
                                    <td>{$row['full_name']}</td>
                                    <td>{$row['specialization']}</td>
                                    <td>{$row['email']}</td>
                                    <td>
                                        <a href='edit-doctor.php?id={$row['doctorID']}' class='btn btn-primary'>Edit</a>
                                        <a href='?delete={$row['doctorID']}&type=doctor' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                        <a href='view-doctor.php?id={$row['doctorID']}' class='btn btn-primary'>View</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No doctors found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="data-section">
            <h2>Patients</h2>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Disease</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($patientResult->num_rows > 0) {
                        while ($row = $patientResult->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['userID']}</td>
                                    <td>{$row['full_name']}</td>
                                    <td>{$row['disease']}</td>
                                    <td>{$row['email']}</td>
                                    <td>
                                        <a href='edit-patient.php?id={$row['userID']}' class='btn btn-primary'>Edit</a>
                                        <a href='?delete={$row['userID']}&type=patient' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                        <a href='view-patient.php?userID={$row['userID']}' class='btn btn-primary'>View</a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No patients found</td></tr>";
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