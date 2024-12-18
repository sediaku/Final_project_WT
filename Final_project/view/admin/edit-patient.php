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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            margin-bottom: 30px;
            color: #343a40;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .btn-submit {
            width: 100%;
        }

        .btn-secondary {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="form-container">
            <h2 class="form-title">Edit Patient</h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($patient['full_name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($patient['full_name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="disease" class="form-label">Disease</label>
                    <input type="text" class="form-control" id="disease" name="disease" value="<?php echo htmlspecialchars($patient['disease']); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary btn-submit">Save Changes</button>
                <a href="admin-dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>
</body>
</html>

