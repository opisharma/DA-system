<?php
session_start();

// Show all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("database/config.php");
if (!isset($_SESSION['valid'])) {
    header("Location: patientLogin.php");
    exit();
}

// Validate appointment ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: appointment_p_v.php");
    exit();
}
$appointmentId = (int)$_GET['id'];

// Prepare delete statement
$deleteSql = "DELETE FROM appointments WHERE AppointmentID = ?";
$stmt = $con->prepare($deleteSql);
if (!$stmt) {
    die("Prepare failed: " . $con->error);
}
$stmt->bind_param('i', $appointmentId);

// Execute and redirect
if ($stmt->execute()) {
    header("Location: appointment_p_v.php?msg=deleted");
    exit();
} else {
    // On failure, display error and back link
    $error = "Delete failed: " . $con->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Delete Appointment</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <a href="appointment_p_v.php" class="btn btn-secondary">Back to Appointments</a>
    <?php endif; ?>
</div>
</body>
</html>
