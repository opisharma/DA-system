<?php 
   session_start();

   include("database/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: login.php");
    exit();
   }

   // Check if ID is provided
   if(!isset($_GET['id']) || empty($_GET['id'])) {
       header("Location: appointment.php");
       exit();
   }

   $appointmentID = mysqli_real_escape_string($con, $_GET['id']);
   
   // Get appointment details
   $query = mysqli_query($con, "SELECT 
        A.AppointmentID,
        A.PatientName,
        A.PatientPhone,
        A.DoctorID,
        D.Name AS DoctorName,
        A.SerialNo,
        A.AppointmentDate,
        A.AppointmentTime
    FROM 
        appointments A
    JOIN 
        doctors D ON A.DoctorID = D.DoctorID
    WHERE 
        A.AppointmentID = '$appointmentID'");
   
   if(mysqli_num_rows($query) == 0) {
       header("Location: appointment.php");
       exit();
   }
   
   $appointment = mysqli_fetch_assoc($query);
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
        .appointment-form {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .appointment-form h2 {
            font-weight: 600;
            margin-bottom: 25px;
            color: #1e2a38;
        }
        .form-label {
            font-weight: 500;
            color: #1e2a38;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
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
            background-color: #6c757d;
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .appointment-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="appointment.php">Medi<span>Book</span></a>
            
            <div class="ms-auto d-flex align-items-center">
                <?php 
                $id = $_SESSION['id'];
                $query = mysqli_query($con, "SELECT * FROM users WHERE Id=$id");

                while($result = mysqli_fetch_assoc($query)){
                    $res_Uname = $result['Username'];
                    $res_Age = $result['Age'];
                    $res_id = $result['Id'];
                }
                
                // echo "<a href='edit.php?Id=$res_id'><button class='btn-icon'><i class='fa-solid fa-user'></i></button></a>";
                // echo "<a href='doctor.php'><button class='btn-icon'><i class='fa-solid fa-hospital-user'></i></button></a>";
                echo "<a href='php/logout.php'><button class='btn-icon'><i class='fa-solid fa-right-from-bracket'></i></button></a>";
                ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="appointment-form">
            <h2>Update Appointment</h2>
            
            <form action="php/update_appointment.php" method="post">
                <input type="hidden" name="appointmentID" value="<?php echo $appointment['AppointmentID']; ?>">
                
                <div class="mb-3">
                    <label for="patientName" class="form-label">Patient Name</label>
                    <input type="text" class="form-control" id="patientName" name="patientName" value="<?php echo $appointment['PatientName']; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="patientPhone" class="form-label">Patient Phone</label>
                    <input type="text" class="form-control" id="patientPhone" name="patientPhone" value="<?php echo $appointment['PatientPhone']; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="doctorID" class="form-label">Doctor</label>
                    <select class="form-control" id="doctorID" name="doctorID" required>
                        <option value="">Select Doctor</option>
                        <?php
                        $query = mysqli_query($con, "SELECT * FROM doctors ORDER BY Name");
                        while($doctor = mysqli_fetch_assoc($query)) {
                            $selected = ($doctor['DoctorID'] == $appointment['DoctorID']) ? "selected" : "";
                            echo "<option value='{$doctor['DoctorID']}' $selected>{$doctor['Name']} - {$doctor['Specialization']}</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="appointmentDate" class="form-label">Appointment Date</label>
                    <input type="date" class="form-control" id="appointmentDate" name="appointmentDate" value="<?php echo $appointment['AppointmentDate']; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="appointmentTime" class="form-label">Appointment Time</label>
                    <input type="time" class="form-control" id="appointmentTime" name="appointmentTime" value="<?php echo $appointment['AppointmentTime']; ?>" required>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="appointment.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Appointment</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>