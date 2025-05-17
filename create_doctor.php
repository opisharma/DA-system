<?php
session_start();
include 'database/config.php';

// Check if user is logged in
if (!isset($_SESSION['valid'])) {
    header('Location: patientLogin.php');
    exit();
}

// Handle form submission
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize inputs
    $name = mysqli_real_escape_string($con, trim($_POST['Name']));
    $specialization = mysqli_real_escape_string($con, trim($_POST['Specialization']));
    $contact = mysqli_real_escape_string($con, trim($_POST['ContactNumber']));
    $email = mysqli_real_escape_string($con, trim($_POST['Email']));

    // Basic validation
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (empty($specialization)) {
        $errors[] = 'Specialization is required.';
    }
    if (empty($contact)) {
        $errors[] = 'Contact Number is required.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid Email is required.';
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $sql = "INSERT INTO doctors (Name, Specialization, ContactNumber, Email) VALUES ('{$name}', '{$specialization}', '{$contact}', '{$email}')";
        if (mysqli_query($con, $sql)) {
            header('Location: doctor.php');
            exit();
        } else {
            $errors[] = 'Database error: ' . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Doctor - MediBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background-color: #f5f7fa; color: #1e2a38; }
        .form-container { max-width: 600px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .btn-back { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="mb-4">Add New Doctor</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="mb-3">
                    <label for="Name" class="form-label">Name</label>
                    <input type="text" name="Name" id="Name" class="form-control" value="<?php echo isset($_POST['Name']) ? htmlspecialchars($_POST['Name']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="Specialization" class="form-label">Specialization</label>
                    <input type="text" name="Specialization" id="Specialization" class="form-control" value="<?php echo isset($_POST['Specialization']) ? htmlspecialchars($_POST['Specialization']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="ContactNumber" class="form-label">Contact Number</label>
                    <input type="text" name="ContactNumber" id="ContactNumber" class="form-control" value="<?php echo isset($_POST['ContactNumber']) ? htmlspecialchars($_POST['ContactNumber']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="Email" class="form-label">Email</label>
                    <input type="email" name="Email" id="Email" class="form-control" value="<?php echo isset($_POST['Email']) ? htmlspecialchars($_POST['Email']) : ''; ?>">
                </div>
                <div class="d-flex justify-content-end">
                    <a href="doctor.php" class="btn btn-secondary btn-back">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Doctor</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
