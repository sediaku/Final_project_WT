<?php
session_start();

include '../../db/db-connection.php';
include '../../functions/booking_functions.php';
include '../../functions/get_doctorlist.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['userID'];
$appointments = getUpcomingAppointments($userID);
$doctors = getDoctorsList();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    bookAppointment($userID, $doctor, $date, $time);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #e9ecef;
        }
        h1, h2 {
            text-align: center;
            color: #343a40;
        }
        main {
            padding: 20px;
        }
        section {
            margin: 20px 0;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #f8f9fa;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Appointments</h1>
    </header>
    <main>
        <section>
            <h2>Upcoming Appointments</h2>
            <ul>
                <?php foreach ($appointments as $appointment): ?>
                    <li>
                        <?php echo $appointment['booking_day'] . ' - ' . $appointment['fname'] . ' ' . $appointment['lname'] . ', ' . $appointment['booking_time']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <section>
            <h2>Book New Appointment</h2>
            <form action="#" method="POST">
                <label for="doctor">Choose Doctor:</label>
                <select id="doctor" name="doctor" class="form-control">
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?php echo $doctor['userID']; ?>">
                            <?php echo 'Dr. ' . $doctor['fname'] . ' ' . $doctor['lname']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br>
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" class="form-control">
                <br>
                <label for="time">Time:</label>
                <input type="time" id="time" name="time" class="form-control">
                <br>
                <button type="submit">Book Appointment</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>
</body>
</html>
