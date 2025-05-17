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

// Get username for welcome message
$res_Uname = '';
if (isset($_SESSION['id'])) {
    $id = (int)$_SESSION['id'];
    $q = mysqli_query($con, "SELECT Username FROM users WHERE Id={$id}");
    if ($q && $user = mysqli_fetch_assoc($q)) {
        $res_Uname = $user['Username'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediBook - Update Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            color: #1e2a38;
        }
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #1e2a38;
        }
        .navbar-brand span {
            color: #0d6efd;
        }
        .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #1e2a38;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-icon:hover {
            background-color: #0d6efd;
            transform: translateY(-2px);
        }
        .form-container {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .form-container h2 {
            font-weight: 600;
            margin-bottom: 25px;
            color: #1e2a38;
            border-bottom: 2px solid #f5f7fa;
            padding-bottom: 15px;
        }
        .form-label {
            font-weight: 500;
            color: #1e2a38;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        .btn-primary {
            background-color: #1e2a38;
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0d6efd;
            transform: translateY(-2px);
        }
        .btn-secondary {
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
        }
        .welcome-box {
            background-color: white;
            border-radius: 12px;
            padding: 20px 30px;
            margin-top: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .welcome-message {
            font-weight: 500;
            font-size: 1.2rem;
            color: #1e2a38;
        }
        .welcome-message b {
            font-weight: 700;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="appointment.php">Medi<span>Book</span></a>
            <div class="ms-auto d-flex align-items-center">
                <?php
                // echo "<a href='doctor.php' class='btn btn-dark me-3'>Doctor</a>";
                echo "<a href='logout.php'><button class='btn-icon'><i class='fa-solid fa-right-from-bracket'></i></button></a>";
                ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-box">
            <div class="welcome-message">
                Hello <b><?php echo htmlspecialchars($res_Uname); ?></b>, Update Appointment
            </div>
            <a href="appointment.php" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left me-2"></i>Back to Appointments
            </a>
        </div>

        <div class="form-container">
            <h2><i class="fa-regular fa-calendar-check me-2"></i>Update Appointment</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Patient Name</label>
                        <input type="text" name="patient_name" class="form-control" value="<?= htmlspecialchars($appointment['patientName']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Patient Phone</label>
                        <input type="text" name="patient_phone" class="form-control" value="<?= htmlspecialchars($appointment['PatientPhone']) ?>" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
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
                  
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Appointment Date</label>
                        <input type="date" name="appointment_date" class="form-control" value="<?= htmlspecialchars($appointment['AppointmentDate']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Appointment Time</label>
                        <input type="time" name="appointment_time" class="form-control" value="<?= htmlspecialchars($appointment['AppointmentTime']) ?>" required>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Update Appointment
                    </button>
                    <a href="appointment.php" class="btn btn-secondary ms-2">
                        <i class="fa-solid fa-xmark me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>