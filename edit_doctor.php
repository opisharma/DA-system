<?php
session_start();
include 'database/config.php';

// Redirect if not logged in
if (!isset($_SESSION['valid'])) {
    header('Location: patientLogin.php');
    exit();
}

// Get doctor ID from query string
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: doctor.php');
    exit();
}
$doctorId = (int)$_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation (you can expand this)
    $name          = trim($_POST['Name']);
    $specialization = trim($_POST['Specialization']);
    $contact       = trim($_POST['ContactNumber']);
    $email         = trim($_POST['Email']);

    // Update the doctor
    $stmt = $con->prepare("
        UPDATE doctors
        SET Name = ?, Specialization = ?, ContactNumber = ?, Email = ?
        WHERE DoctorID = ?
    ");
    $stmt->bind_param("ssssi", $name, $specialization, $contact, $email, $doctorId);

    if ($stmt->execute()) {
        header("Location: doctor.php?msg=" . urlencode("Doctor updated successfully"));
        exit();
    } else {
        $error = "Error updating doctor: " . $stmt->error;
    }
}

// On GET, fetch existing data
$stmt = $con->prepare("SELECT Name, Specialization, ContactNumber, Email FROM doctors WHERE DoctorID = ?");
$stmt->bind_param("i", $doctorId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // No such doctor
    header('Location: doctor.php');
    exit();
}

$doctor = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2>Edit Doctor</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="Name" class="form-control" 
                   value="<?= htmlspecialchars($doctor['Name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Specialization</label>
            <input type="text" name="Specialization" class="form-control" 
                   value="<?= htmlspecialchars($doctor['Specialization']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="text" name="ContactNumber" class="form-control" 
                   value="<?= htmlspecialchars($doctor['ContactNumber']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="Email" class="form-control" 
                   value="<?= htmlspecialchars($doctor['Email']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Doctor</button>
        <a href="doctor.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
</body>
</html>
