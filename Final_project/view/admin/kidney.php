<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: ../view/Login.php');
    exit();
}

include '../../db/db-connection.php';
include '../../functions/submitHealthReading.php'; 

$userID = $_SESSION['userID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $creatinine = $_POST['creatinine'];
    $unit = $_POST['unit'];
    $notes = $_POST['notes'];

    $response_creatinine = submitHealthReading($userID, 'Creatinine', $creatinine, $unit, $notes, $conn);

    $bmi = $_POST['bmi'];
    $bmi_unit = $_POST['bmi_unit']; 
    $bmi_notes = $_POST['bmi_notes'];

    $systolic = $_POST['systolic'];
    $diastolic = $_POST['diastolic'];
    $bp_unit = $_POST['bp_unit']; 
    $bp_notes = $_POST['bp_notes'];

    $response_bmi = submitHealthReading($userID, 'BMI', $bmi, $bmi_unit, $bmi_notes, $conn);
    $response_bp = submitHealthReading($userID, 'Blood Pressure', $systolic . '/' . $diastolic, $bp_unit, $bp_notes, $conn);

    echo $response_creatinine;
    echo $response_bmi;
    echo $response_bp;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chronic Kidney Disease Health Data</title>
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
        <h1>Chronic Kidney Disease Health Data Input</h1>
    </header>

    <main>
        <form method="POST" action="" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="creatinine" class="form-label">Creatinine Level:</label>
                <input type="number" step="0.01" class="form-control" id="creatinine" name="creatinine" required>
            </div>

            <div class="mb-3">
                <label for="unit" class="form-label">Unit (Creatinine):</label>
                <input type="text" class="form-control" id="unit" name="unit" required>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Personal Notes (Creatinine):</label>
                <textarea class="form-control" id="notes" name="notes" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label for="bmi" class="form-label">BMI:</label>
                <input type="number" step="0.01" class="form-control" id="bmi" name="bmi" required>
            </div>

            <div class="mb-3">
                <label for="bmi_unit" class="form-label">Unit (BMI):</label>
                <input type="text" class="form-control" id="bmi_unit" name="bmi_unit" required>
            </div>

            <div class="mb-3">
                <label for="bmi_notes" class="form-label">Personal Notes (BMI):</label>
                <textarea class="form-control" id="bmi_notes" name="bmi_notes" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label for="systolic" class="form-label">Systolic (Blood Pressure):</label>
                <input type="number" step="1" class="form-control" id="systolic" name="systolic" required>
            </div>

            <div class="mb-3">
                <label for="diastolic" class="form-label">Diastolic (Blood Pressure):</label>
                <input type="number" step="1" class="form-control" id="diastolic" name="diastolic" required>
            </div>

            <div class="mb-3">
                <label for="bp_unit" class="form-label">Unit (Blood Pressure):</label>
                <input type="text" class="form-control" id="bp_unit" name="bp_unit" required>
            </div>

            <div class="mb-3">
                <label for="bp_notes" class="form-label">Personal Notes (Blood Pressure):</label>
                <textarea class="form-control" id="bp_notes" name="bp_notes" rows="4"></textarea>
            </div>

            <input type="submit" class="btn btn-primary" value="Submit">
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>

    <script>
        function validateForm() {
            const creatinine = document.getElementById('creatinine').value;
            const unit = document.getElementById('unit').value;
            const bmi = document.getElementById('bmi').value;
            const bmi_unit = document.getElementById('bmi_unit').value;
            const systolic = document.getElementById('systolic').value;
            const diastolic = document.getElementById('diastolic').value;
            const bp_unit = document.getElementById('bp_unit').value;

            if (!creatinine || isNaN(creatinine)) {
                alert("Please enter a valid creatinine level.");
                return false;
            }

            if (!unit) {
                alert("Please enter the unit for creatinine.");
                return false;
            }

            if (!bmi || isNaN(bmi)) {
                alert("Please enter a valid BMI value.");
                return false;
            }

            if (!bmi_unit) {
                alert("Please enter the BMI unit.");
                return false;
            }

            if (!systolic || isNaN(systolic)) {
                alert("Please enter a valid systolic blood pressure.");
                return false;
            }

            if (!diastolic || isNaN(diastolic)) {
                alert("Please enter a valid diastolic blood pressure.");
                return false;
            }

            if (!bp_unit) {
                alert("Please enter the unit for blood pressure.");
                return false;
            }

            return true;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

