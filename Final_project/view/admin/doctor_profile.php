<?php
session_start();
include '../../db/db-connection.php';

if (!isset($_SESSION['userID'])) {
    header('Location: ../view/Login.php');
    exit();
}

$userID = $_SESSION['userID'];

$sql = "SELECT * FROM user WHERE userID = '$userID'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$doctorSql = "SELECT * FROM doctor WHERE userID = '$userID'";
$doctorResult = $conn->query($doctorSql);
$doctor = $doctorResult->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newFname = $_POST['fname'];
    $newLname = $_POST['lname'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['password'];

    if (!empty($newPassword)) {
        $newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    } else {
        $newPassword = $user['password'];  
    }

    $updateUserSql = "UPDATE user SET fname = '$newFname', lname = '$newLname', email = '$newEmail', password = '$newPassword' WHERE userID = '$userID'";
    if ($conn->query($updateUserSql) === TRUE) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . $conn->error;
    }

    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
    $doctorResult = $conn->query($doctorSql);
    $doctor = $doctorResult->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
            padding: 30px;
            text-align: center;
        }

        header h1 {
            margin-bottom: 15px;
        }

        header a {
            color: white;
            text-decoration: none;
            padding: 12px;
            margin: 0 15px;
        }

        header a:hover {
            background-color: #374151;
            border-radius: 5px;
        }

        main {
            padding: 30px 15px;
            flex-grow: 1;
        }

        form {
            background-color: #ffffff;
            padding: 30px;
            margin: 30px auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            border-radius: 8px;
        }

        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        form button {
            background-color: #1e3a8a;
            color: white;
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        form button:hover {
            background-color: #374151;
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

        .icons {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
        }

        .icons span {
            font-size: 28px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>
    <h1>Doctor Profile</h1>
    <div class="icons">
        <span class="material-icons" onclick="window.location.href='../Logout.php'">logout</span>
    </div>
</header>

<main>

    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

    <form action="profile.php" method="POST">
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" required><br>

        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <label for="password">New Password (Leave blank to keep current password):</label>
        <input type="password" id="password" name="password"><br>

        <button type="submit">Update Profile</button>
    </form>

</main>

<footer>
    <p>&copy; 2024 Disease Management Platform</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

