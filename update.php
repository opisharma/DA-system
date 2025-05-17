<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("database/config.php");
if (!isset($_SESSION['valid'])) {
    header("Location: patientLogin.php");
    exit();
}

// Get appointment ID from query string
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: appointment.php");
    exit();
}
$appointmentId = (int)$_GET['id'];

// Fetch appointment details
$sql = "SELECT * FROM appointments WHERE AppointmentID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $appointmentId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: appointment.php");
    exit();
}
$appointment = $result->fetch_assoc();

// Fetch doctors for dropdown
$docRes = $con->query("SELECT DoctorID, Name FROM doctors ORDER BY Name ASC");
$doctors = $docRes ? $docRes->fetch_all(MYSQLI_ASSOC) : [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientName  = trim($_POST['patient_name']);
    $patientPhone = trim($_POST['patient_phone']);
    $doctorId     = (int)$_POST['doctor_id'];
    $serialNo     = (int)$_POST['serial_no'];
    $apptDate     = trim($_POST['appointment_date']);
    $apptTime     = trim($_POST['appointment_time']);

    $updateSql = "UPDATE appointments SET PatientName = ?, PatientPhone = ?, DoctorID = ?, SerialNo = ?, AppointmentDate = ?, AppointmentTime = ? WHERE AppointmentID = ?";
    $updateStmt = $con->prepare($updateSql);
    $updateStmt->bind_param('ssisssi', $patientName, $patientPhone, $doctorId, $serialNo, $apptDate, $apptTime, $appointmentId);
    if ($updateStmt->execute()) {
        header("Location: appointment.php?msg=updated");
        exit();
    } else {
        $error = "Update failed: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment - MediBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Update Appointment</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="" method="post">
        <div class="mb-3">
            <label class="form-label">Patient Name</label>
            <input type="text" name="patient_name" class="form-control" value="<?= htmlspecialchars($appointment['patientName']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Patient Phone</label>
            <input type="text" name="patient_phone" class="form-control" value="<?= htmlspecialchars($appointment['PatientPhone']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Doctor</label>
            <select name="doctor_id" class="form-select" required>
                <option value="">Select Doctor</option>
                <?php foreach ($doctors as $doc): ?>
                    <option value="<?= $doc['DoctorID'] ?>" <?= $doc['DoctorID'] == $appointment['DoctorID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($doc['Name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Serial No</label>
            <input type="number" name="serial_no" class="form-control" value="<?= htmlspecialchars($appointment['SerialNo']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Appointment Date</label>
            <input type="date" name="appointment_date" class="form-control" value="<?= htmlspecialchars($appointment['AppointmentDate']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Appointment Time</label>
            <input type="time" name="appointment_time" class="form-control" value="<?= htmlspecialchars($appointment['AppointmentTime']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Appointment</button>
        <a href="appointment.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
