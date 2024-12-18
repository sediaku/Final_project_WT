<?php
include '../../db/db-connection.php';

if (isset($_GET['id'])) {
    $doctorID = $_GET['id'];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $full_name = $_POST['full_name'];
        $specialization = $_POST['specialization'];
        $email = $_POST['email'];

        $update_sql = "UPDATE doctor d 
                       JOIN user u ON d.userID = u.userID 
                       SET u.fname = ?, u.lname = ?, d.specialization = ?, u.email = ? 
                       WHERE d.doctorID = ?";
        $stmt = $conn->prepare($update_sql);
        $name_parts = explode(" ", $full_name);
        $stmt->bind_param('ssssi', $name_parts[0], $name_parts[1], $specialization, $email, $doctorID);
        $stmt->execute();
        
        header('Location: admin-dashboard.php');
        exit();
    }

    $sql = "SELECT u.fname, u.lname, d.specialization, u.email 
            FROM doctor d 
            JOIN user u ON d.userID = u.userID 
            WHERE d.doctorID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $doctorID);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor</title>
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
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="form-container">
            <h2 class="form-title">Edit Doctor</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo $result['fname'] . ' ' . $result['lname']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="specialization" class="form-label">Specialization</label>
                    <input type="text" id="specialization" name="specialization" class="form-control" value="<?php echo $result['specialization']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $result['email']; ?>" required>
                </div>

                <button type="submit" class="btn btn-primary btn-submit">Update</button>
                <a href="admin-dashboard.php" class="btn btn-secondary btn-submit mt-3">Cancel</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <footer>
        <p>&copy; 2024 Disease Management Platform</p>
    </footer>
</body>
</html>

