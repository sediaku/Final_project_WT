<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../view/Login.php');
    exit();
}

include '../../db/db-connection.php';
include '../../functions/submitHealthReading.php'; 
$userID = $_SESSION['userID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $peakFlow = $_POST['peakFlow'];
    $unit = $_POST['unit'];
    $notes = $_POST['notes'];

    $response = submitHealthReading($userID, 'Peak Flow', $peakFlow, $unit, $notes, $conn);
    echo $response;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asthma Health Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f9fc;
        }
        h1 {
            text-align: center;
            color: #0056b3;
            margin-top: 20px;
        }
        .health-data-form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <h1>Asthma Health Data Input</h1>
    <form method="POST" action="" onsubmit="return validateForm()">
        <div class="health-data-form">
            <div class="form-group">
                <label for="peakFlow">Peak Flow Measurement:</label>
                <input type="number" class="form-control" id="peakFlow" name="peakFlow" required>
            </div>

            <div class="form-group">
                <label for="unit">Unit:</label>
                <input type="text" class="form-control" id="unit" name="unit" required>
            </div>

            <div class="form-group">
                <label for="notes">Personal Notes:</label>
                <textarea class="form-control" id="notes" name="notes" rows="4"></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </div>
    </form>

    <script>
        function validateForm() {
            const peakFlow = document.getElementById('peakFlow').value;
            const unit = document.getElementById('unit').value;

            if (!peakFlow || isNaN(peakFlow)) {
                alert("Please enter a valid peak flow measurement.");
                return false;
            }

            if (!unit) {
                alert("Please enter the unit for peak flow.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>