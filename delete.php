<?php
session_start();
include("database/config.php");

if(!isset($_SESSION['valid'])){
    header("Location: login.php");
    exit();
}

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $appointmentID = mysqli_real_escape_string($con, $_GET['id']);
    
    // Get doctor ID and appointment date for serial number recalculation
    $appointmentQuery = mysqli_query($con, "SELECT DoctorID, AppointmentDate FROM appointments WHERE AppointmentID = '$appointmentID'");
    
    if(mysqli_num_rows($appointmentQuery) > 0) {
        $appointmentData = mysqli_fetch_assoc($appointmentQuery);
        $doctorID = $appointmentData['DoctorID'];
        $appointmentDate = $appointmentData['AppointmentDate'];
        
        // Delete the appointment
        $deleteQuery = "DELETE FROM appointments WHERE AppointmentID = '$appointmentID'";
        
        if(mysqli_query($con, $deleteQuery)) {
            // Update serial numbers for remaining appointments on the same day with the same doctor
            $updateQuery = "UPDATE appointments SET 
                            SerialNo = (
                                SELECT @row_number:=@row_number+1 
                                FROM (SELECT @row_number:=0) AS t
                            )
                            WHERE DoctorID = '$doctorID' 
                            AND AppointmentDate = '$appointmentDate'
                            ORDER BY AppointmentTime";
            
            // Execute update query
            mysqli_query($con, $updateQuery);
            
            // Redirect to home page
            header("Location: home.php");
            exit();
        } else {
            echo "Error deleting appointment: " . mysqli_error($con);
            echo "<br><a href='home.php'>Go Back to Home</a>";
        }
    } else {
        echo "Appointment not found.";
        echo "<br><a href='home.php'>Go Back to Home</a>";
    }
} else {
    header("Location: home.php");
    exit();
}
?>