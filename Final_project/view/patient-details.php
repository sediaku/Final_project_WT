<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient's Form</title>
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
        .patient-form {
            background-color: white; 
            padding: 30px; 
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto; 
            max-width: 600px; 
        }
    </style>
</head>
<body>
    <h1>Patient's Form</h1>
    <form action="../actions/patient-details-action.php" method="POST" onsubmit="validateForm(event)">
        <div class="patient-form">
            <div class="form-group">
                <label for="disease">Indicate your medical condition</label>
                <select class="form-control" name="disease" id="disease-dropdown">
                    <option value="select">Select your condition</option>
                    <option value="Hypertension">Hypertension</option>
                    <option value="Diabetes-Type1">Diabetes Type 1</option>
                    <option value="Diabetes-Type2">Diabetes Type 2</option>
                    <option value="Asthma">Asthma</option>
                    <option value="Chronic-Kidney-Disease">Chronic Kidney Disease</option>
                </select>
            </div>

            <div class="form-group">
                <label for="allergies">Allergies</label>
                <textarea class="form-control" name="allergies" id="allergies" rows="3" placeholder="Enter all known allergies"></textarea>
            </div>

            <div class="form-group">
                <label for="medicalHistory">Medical History</label>
                <textarea class="form-control" name="medicalHistory" id="medicalHistory" rows="3" placeholder="Enter your medical history"></textarea>
            </div>

            <div class="form-group">
                <label for="monitoring_frequency">Monitoring Frequency</label>
                <input type="number" class="form-control" name="monitoring_freq" id="monitoring_frequency" step="1" placeholder="Enter how frequently you monitor your condition">
                <small class="form-text text-muted">day(s)</small>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </div>
    </form>

    <script>
        
        function validateForm(event) {
            
            const disease = document.getElementById("disease-dropdown").value;
            const allergies = document.getElementById("allergies").value.trim();
            const medicalHistory = document.getElementById("medicalHistory").value.trim();
            const monitoringFreq = document.getElementById("monitoring_frequency").value.trim();

            
            let errorMessage = "";

            
            if (disease === "select") {
                errorMessage += "Please select a valid medical condition.\n";
            }
            if (allergies === "") {
                errorMessage += "Allergies field cannot be empty.\n";
            }
            if (medicalHistory === "") {
                errorMessage += "Medical History field cannot be empty.\n";
            }
            if (monitoringFreq === "" || isNaN(monitoringFreq) || monitoringFreq <= 0) {
                errorMessage += "Please enter a valid monitoring frequency (positive number).\n";
            }

            
            if (errorMessage) {
                alert(errorMessage);
                event.preventDefault(); 
            }
        }
    </script>
</body>
</html>