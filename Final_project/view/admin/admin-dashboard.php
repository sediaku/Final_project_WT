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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            padding-top: 70px; 
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar .navbar-brand, .navbar .nav-link {
            color: white;
        }
        .navbar .nav-link:hover {
            color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        footer {
            margin-top: 30px;
            text-align: center;
        }
        .logout-icon {
            color: white;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <div class="d-flex">
                <a href="../Logout.php" class="nav-link text-white">
                    <span class="material-icons logout-icon">logout</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Greeting Heading -->
    <div class="container">
        <h1 class="my-5">Hello, <?php echo htmlspecialchars($fullName); ?></h1>

        <div class="row">
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Patients</h5>
                        <p class="card-text display-4"><?php echo $patientCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Doctors</h5>
                        <p class="card-text display-4"><?php echo $doctorCount; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="my-4">Doctors</h2>
        <table class="table table-striped">
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
                                    <a href='edit-doctor.php?id={$row['doctorID']}' class='btn btn-primary btn-sm'>Edit</a>
                                    <a href='?delete={$row['doctorID']}&type=doctor' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                    <a href='view-doctor.php?id={$row['doctorID']}' class='btn btn-info btn-sm'>View More</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No doctors found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2 class="my-4">Patients</h2>
        <table class="table table-striped">
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
                                    <a href='edit-patient.php?id={$row['userID']}' class='btn btn-primary btn-sm'>Edit</a>
                                    <a href='?delete={$row['userID']}&type=patient' class='btn btn-primary btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                    <a href='view-patient.php?userID={$row['userID']}' class='btn btn-primary btn-sm'>View More</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>
</body>
</html>



