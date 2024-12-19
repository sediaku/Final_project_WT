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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
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
            padding: 20px;
            flex-grow: 1;
        }

        .profile-section {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-left: 4px solid #1e3a8a;
        }

        .form-group label {
            color: #1e3a8a;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.75rem;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25);
        }

        .btn-primary {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #1e40af;
            border-color: #1e40af;
        }

        .alert {
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-info {
            background-color: #e0f2fe;
            border-color: #bae6fd;
            color: #1e3a8a;
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
            .profile-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>User Profile</h1>
        <div class="icons">
            <span class="material-icons" onclick="window.location.href='notifications.php'">notifications</span>
            <span class="material-icons" onclick="window.location.href='profile.php'">account_circle</span>
            <span class="material-icons" onclick="window.location.href='../Logout.php'">logout</span>
        </div>
    </header>

    <main>
        <section class="profile-section">
            <?php if (isset($message)): ?>
                <div class="alert alert-info" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="profile.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" 
                           value="<?php echo htmlspecialchars($user['fname']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" 
                           value="<?php echo htmlspecialchars($user['lname']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Leave blank to keep current password">
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>
</body>
</html>