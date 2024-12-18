<?php
session_start();
include '../../db/db-connection.php';

if (!isset($_SESSION['userID'])) {
    header('Location: ../Login.php');
    exit();
}

$userID = $_SESSION['userID'];

$sql = "SELECT * FROM user WHERE userID = '$userID'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUsername = $_POST['username'];
    $newFname = $_POST['fname'];
    $newLname = $_POST['lname'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['password'];
    
    if (!empty($newPassword)) {
        $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    } else {
        $newPassword = $user['password'];  
    }

    $updateSql = "UPDATE user SET username = '$newUsername', fname = '$newFname', lname = '$newLname', email = '$newEmail', password = '$newPassword' WHERE userID = '$userID'";
    
    if ($conn->query($updateSql) === TRUE) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . $conn->error;
    }

    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .main-content {
            padding: 20px;
            border-radius: 8px;
            background-color: #e0f7fa;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        h1 {
            color: #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container main-content">
        <div class="header">
            <h1>User Profile</h1>
            <div>
                <a href="../Logout.php" class="text-white">Logout</a>
            </div>
        </div>

        <?php if (isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>

        <form action="profile.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" required>
            </div>

            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">New Password (Leave blank to keep current password):</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2 /umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>