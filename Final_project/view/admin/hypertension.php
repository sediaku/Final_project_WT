<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../view/Login.php');
    exit();
}

include '../../db/db-connection.php';
include '../../functions/submitHealthReading.php'; 

$userID = $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $systolic_value = $_POST['systolic_value'];
    $diastolic_value = $_POST['diastolic_value'];
    $unit = $_POST['unit'];
    $notes = $_POST['notes'];

    // Submit systolic and diastolic readings separately
    $responseSystolic = submitHealthReading($userID, 'Systolic Blood Pressure', $systolic_value, $unit, $notes, $conn);
    $responseDiastolic = submitHealthReading($userID, 'Diastolic Blood Pressure', $diastolic_value, $unit, $notes, $conn);

    echo $responseSystolic . " " . $responseDiastolic;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hypertension Data</title>
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

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        form label {
            margin-top: 10px;
            font-weight: bold;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            background-color: #1e3a8a;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            padding: 10px 20px;
        }

        form input[type="submit"]:hover {
            background-color: #0d255c;
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

    </style>
</head>
<body>
    <header>
        <h1>Hypertension - Blood Pressure Entry</h1>
    </header>

    <main>
        <form method="POST" onsubmit="return validateForm()">
            <label for="systolic_value">Systolic Blood Pressure (e.g., 120):</label>
            <input type="text" id="systolic_value" name="systolic_value" required><br>

            <label for="diastolic_value">Diastolic Blood Pressure (e.g., 80):</label>
            <input type="text" id="diastolic_value" name="diastolic_value" required><br>

            <label for="unit">Unit (e.g., mmHg):</label>
            <input type="text" id="unit" name="unit" value="mmHg" required><br>

            <label for="notes">Personal Notes:</label>
            <textarea id="notes" name="notes"></textarea><br>

            <input type="submit" value="Submit">
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>

    <script>
        function validateForm() {
            const systolic_value = document.getElementById('systolic_value').value;
            const diastolic_value = document.getElementById('diastolic_value').value;
            const unit = document.getElementById('unit').value;
            const notes = document.getElementById('notes').value;

            if (!systolic_value || isNaN(systolic_value)) {
                alert("Please enter a valid systolic blood pressure value.");
                return false;
            }

            if (!diastolic_value || isNaN(diastolic_value)) {
                alert("Please enter a valid diastolic blood pressure value.");
                return false;
            }

            if (!unit) {
                alert("Please enter the unit.");
                return false;
            }

            return true;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
