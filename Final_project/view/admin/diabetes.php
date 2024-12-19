<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../Login.php');
    exit();
}

include '../../db/db-connection.php';
include '../../functions/submitHealthReading.php'; 

$userID = $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $glucose_value = $_POST['glucose_value'];
    $unit = $_POST['unit'];
    $notes = $_POST['notes'];
    
    $response = submitHealthReading($userID, 'Blood Glucose', $glucose_value, $unit, $notes, $conn);
    
    if ($response === "Success") {
        header('Location: user-dashboard.php');
        exit();
    } else {
        echo "Error: $response";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diabetes Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
            padding: 30px 15px;
            flex-grow: 1;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #1e3a8a;
        }

        form label {
            display: block;
            margin-top: 15px;
            margin-bottom: 8px;
            color: #1e3a8a;
            font-weight: 500;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }

        form input:focus,
        form textarea:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25);
            outline: none;
        }

        form textarea {
            min-height: 100px;
            resize: vertical;
        }

        form input[type="submit"] {
            background-color: #1e3a8a;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            padding: 12px 24px;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }

        form input[type="submit"]:hover {
            background-color: #1e40af;
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
            main {
                padding: 15px;
            }
            
            form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Diabetes - Blood Glucose Entry</h1>
        <div class="icons">
            <span class="material-icons" onclick="window.location.href='notifications.php'">notifications</span>
            <span class="material-icons" onclick="window.location.href='profile.php'">account_circle</span>
            <span class="material-icons" onclick="window.location.href='../Logout.php'">logout</span>
        </div>
    </header>

    <main>
        <form method="POST" onsubmit="return validateForm()">
            <label for="glucose_value">Blood Glucose Level:</label>
            <input type="text" id="glucose_value" name="glucose_value" placeholder="Enter your blood glucose reading" required>

            <label for="unit">Unit of Measurement:</label>
            <input type="text" id="unit" name="unit" value="mg/dL" required>

            <label for="notes">Personal Notes:</label>
            <textarea id="notes" name="notes" placeholder="Add any relevant notes about your reading (e.g., before/after meal, time of day, etc.)"></textarea>

            <input type="submit" value="Submit Reading">
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>

    <script>
        function validateForm() {
            const glucose_value = document.getElementById('glucose_value').value;
            const unit = document.getElementById('unit').value;

            if (!glucose_value || isNaN(glucose_value)) {
                alert("Please enter a valid blood glucose value.");
                return false;
            }

            if (!unit) {
                alert("Please enter the unit of measurement.");
                return false;
            }

            return true;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>