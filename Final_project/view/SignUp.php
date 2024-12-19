<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <form action="../actions/SignUp-action.php" method="POST" class="mt-4" style="width: 100%; max-width: 600px;">
            <h1 class="text-center text-primary mb-4">Sign Up</h1>
            <div class="card p-4">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="username">Username (Optional)</label>
                        <input type="text" name="username" class="form-control" id="username">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" class="form-control" id="fname" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" class="form-control" id="lname" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cpassword">Confirm Password</label>
                        <input type="password" name="cpassword" class="form-control" id="cpassword" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Are you a:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="patient" id="patient">
                        <label class="form-check-label" for="patient">Patient</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="doctor" id="doctor">
                        <label class="form-check-label" for="doctor">Doctor</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                <p class="text-center mt-3">Already have an account? <a href="Login.php">Login</a></p>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../assets/js/SignUp.js"></script>
</body>
</html>