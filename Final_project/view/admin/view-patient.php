<?php

include '../../functions/getpatientDetails.php';
include '../../db/db-connection.php';


if (isset($_GET['patientID'])) {
    $patientID = $_GET['patientID'];
} else {
    
    header('Location: error.php');
    exit();
}


$patientData = getPatientData($patientID, $conn);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient - <?php echo $patientData['patient']['fname'] . ' ' . $patientData['patient']['lname']; ?></title>
</head>
<body>
    <header>
        <h1>Patient Profile: <?php echo $patientData['patient']['fname'] . ' ' . $patientData['patient']['lname']; ?></h1>
    </header>
    <main>
     
        <section id="patient-info">
            <h2>Basic Information</h2>
            <p><strong>Full Name:</strong> <?php echo $patientData['patient']['fname'] . ' ' . $patientData['patient']['lname']; ?></p>
            <p><strong>Condition:</strong> <?php echo $patientData['patient']['disease']; ?></p>
            <p><strong>Doctor's Notes:</strong> <?php echo $patientData['patient']['doctors_notes']; ?></p>
        </section>

        <section id="health-data">
            <h2>Recent Health Data</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reading Type</th>
                        <th>Value</th>
                        <th>Unit</th>
                        <th>Patient's Notes</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patientData['healthData'] as $data): ?>
                        <tr>
                            <td><?php echo $data['updated_at']; ?></td>
                            <td><?php echo $data['reading_type']; ?></td>
                            <td><?php echo $data['reading_value']; ?></td>
                            <td><?php echo $data['unit']; ?></td>
                            <td><?php echo $data['patient_notes']; ?></td> 
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section id="treatment-plan">
            <h2>Treatment Plan</h2>
            <p><strong>Medications:</strong></p>
            <ul>
                <?php foreach ($patientData['medications'] as $medication): ?>
                    <li><?php echo $medication['medication'] . ' - ' . $medication['dosage'] . ' ' . $medication['unit'] . ' (' . $medication['timing'] . ')'; ?></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section id="appointment-history">
            <h2>Appointment History</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Doctor's Notes</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patientData['appointments'] as $appointment): ?>
                        <tr>
                            <td><?php echo $appointment['booking_day']; ?></td>
                            <td>Doctor's notes here...</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>
</body>
</html>
