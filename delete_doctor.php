
<!----new---->
<!-- <?php

 session_start();

 if (!isset($_SESSION['valid'])) {
    header('Location: patientLogin.php');
    exit();
}

      $id = $_GET['id'];
      include("database/config.php");
        $query = mysqli_query($con, "DELETE FROM doctors WHERE DoctorID = '$id'") or die("Error Occurred");
        header("Location: doctor.php");
?> -->


<?php
// delete_appointment.php

// 1) DEBUG: show all errors (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2) Grab and validate the ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid doctor ID.');
}
$doctorID = (int) $_GET['id'];

// 3) Connect
include __DIR__ . '/database/config.php';

// 4) Prepare & execute DELETE
$stmt = $con->prepare("DELETE FROM doctors WHERE DoctorID = ?");
if (!$stmt) {
    die('Prepare failed: ' . $con->error);
}
$stmt->bind_param('i', $doctorID);

if (!$stmt->execute()) {
    die('Delete failed: ' . $stmt->error);
}

$stmt->close();

// 5) Redirect back to your home page
//    Uses relative path, so it will look for home.php in the same folder.
header('Location: doctor.php');
exit;
