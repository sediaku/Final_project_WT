<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's Form</title>
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
        .doctor-form {
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
    <h1>Doctor's Form</h1>
    <form action="../actions/doctor-details-action.php" method="POST">
        <div class="doctor-form">
            <div class="form-group">
                <label for="specialization">Specialization</label>
                <input type="text" class="form-control" name="specialization">
            </div>

            <div class="form-group">
                <label for="hospital">Affiliated Hospital</label>
                <input type="text" class="form-control" name="hospital">
            </div>

            <div class="form-group">
                <label for="availability">Availability</label>
                <input type="text" class="form-control" name="availability">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </div>
    </form>

    <script>
        function validateForm(event) {
            event.preventDefault();
            const specialization = document.querySelector('input[name="specialization"]');
            const hospital = document.querySelector('input[name="hospital"]');
            const availability = document.querySelector('input[name="availability"]');

            let isValid = true;
            let errorMessage = "";

            if (!specialization.value.trim()) {
                isValid = false;
                errorMessage += "Specialization is required.\n";
            }

            if (!hospital.value.trim()) {
                isValid = false;
                errorMessage += "Affiliated Hospital is required.\n";
            }

            if (!availability.value.trim()) {
                isValid = false;
                errorMessage += "Availability is required.\n";
            }

            if (!isValid) {
                alert(errorMessage);
            } else {
                event.target.submit();
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            const form = document.querySelector("form");
            form.addEventListener("submit", validateForm);
        });
    </script>
</body>
</html>