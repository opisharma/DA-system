<?php
session_start();
include("../database/config.php");

if(!isset($_SESSION['valid'])){
    header("Location: ../login.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $appointmentID = mysqli_real_escape_string($con, $_POST['appointmentID']);
    $patientName = mysqli_real_escape_string($con, $_POST['patientName']);
    $patientPhone = mysqli_real_escape_string($con, $_POST['patientPhone']);
    $doctorID = mysqli_real_escape_string($con, $_POST['doctorID']);
    $appointmentDate = mysqli_real_escape_string($con, $_POST['appointmentDate']);
    $appointmentTime = mysqli_real_escape_string($con, $_POST['appointmentTime']);
    
    // Check if doctor or date changed
    $checkQuery = mysqli_query($con, "SELECT DoctorID, AppointmentDate FROM appointments WHERE AppointmentID = '$appointmentID'");
    $currentData = mysqli_fetch_assoc($checkQuery);
    
    // If doctor or date changed, need to recalculate serial number
    if($currentData['DoctorID'] != $doctorID || $currentData['AppointmentDate'] != $appointmentDate) {
        // Get new serial number
        $serialQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM appointments 
                                          WHERE DoctorID = '$doctorID' 
                                          AND AppointmentDate = '$appointmentDate'
                                          AND AppointmentID != '$appointmentID'");
        $serialData = mysqli_fetch_assoc($serialQuery);
        $serialNo = $serialData['count'] + 1;
        
        // Update query with new serial number
        $updateQuery = "UPDATE appointments SET 
                        PatientName = '$patientName', 
                        PatientPhone = '$patientPhone', 
                        DoctorID = '$doctorID', 
                        SerialNo = '$serialNo', 
                        AppointmentDate = '$appointmentDate', 
                        AppointmentTime = '$appointmentTime' 
                        WHERE AppointmentID = '$appointmentID'";
    } else {
        // Update without changing serial number
        $updateQuery = "UPDATE appointments SET 
                        PatientName = '$patientName', 
                        PatientPhone = '$patientPhone', 
                        DoctorID = '$doctorID', 
                        AppointmentDate = '$appointmentDate', 
                        AppointmentTime = '$appointmentTime' 
                        WHERE AppointmentID = '$appointmentID'";
    }
    
    if(mysqli_query($con, $updateQuery)) {
        // Success
        header("Location: ../home.php");
        exit();
    } else {
        // Error
        echo "Error: " . mysqli_error($con);
        echo "<br><a href='../update.php?id=$appointmentID'>Go Back</a>";
    }
} else {
    // If not POST request
    header("Location: ../home.php");
    exit();
}
?>