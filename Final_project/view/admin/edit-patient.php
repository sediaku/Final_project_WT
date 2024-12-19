<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../Login.php');
    exit();
}

include '../../db/db-connection.php';


if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    
    
    $sql = "
        SELECT p.userID, CONCAT(u.fname, ' ', u.lname) AS full_name, p.disease, u.email 
        FROM patient p
        JOIN user u ON p.userID = u.userID
        WHERE p.userID = ?
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        echo "Patient not found.";
        exit();
    }
} else {
    echo "No patient ID provided.";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $disease = $_POST['disease'];

    $update_sql = "
        UPDATE user u
        JOIN patient p ON u.userID = p.userID
        SET u.fname = ?, u.lname = ?, u.email = ?, p.disease = ?
        WHERE p.userID = ?
    ";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('ssssi', $fname, $lname, $email, $disease, $id);
    
    if ($stmt->execute()) {
        header('Location: admin-dashboard.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .edit-form-section {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-left: 4px solid #1e3a8a;
            max-width: 800px;
            margin: 0 auto;
        }

        .edit-form-section h2 {
            color: #1e3a8a;
            margin-bottom: 25px;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #1e3a8a;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.75rem;
            font-size: 1rem;
            transition: border-color 0.2s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25);
            outline: none;
        }

        .btn {
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            width: 100%;
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1e40af;
            border-color: #1e40af;
        }

        .btn-secondary {
            background-color: #64748b;
            border-color: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #475569;
            border-color: #475569;
            color: white;
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
            .edit-form-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Patient</h1>
        <div class="icons">
            <span class="material-icons" onclick="window.location.href='notifications.php'">notifications</span>
            <span class="material-icons" onclick="window.location.href='profile.php'">account_circle</span>
            <span class="material-icons" onclick="window.location.href='../Logout.php'">logout</span>
        </div>
    </header>

    <main>
        <section class="edit-form-section">
            <h2>Update Patient Information</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" 
                           value="<?php echo explode(' ', htmlspecialchars($patient['full_name']))[0]; ?>" required>
                </div>

                <div class="form-group">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" 
                           value="<?php echo explode(' ', htmlspecialchars($patient['full_name']))[1]; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($patient['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="disease" class="form-label">Disease</label>
                    <input type="text" class="form-control" id="disease" name="disease" 
                           value="<?php echo htmlspecialchars($patient['disease']); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="admin-dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>