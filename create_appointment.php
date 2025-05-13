<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create An Appointment</title>
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --success: #2ecc71;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 550px;
        }
        
        .box.form-box {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            width: 100%;
        }
        
        header {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .field {
            display: flex;
            flex-direction: column;
        }
        
        .field.full-width {
            grid-column: span 2;
        }
        
        label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark);
        }
        
        input, select {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: var(--transition);
            width: 100%;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        .message {
            text-align: center;
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            background-color: var(--success);
            color: white;
        }
        
        .links {
            margin-top: 20px;
            text-align: center;
        }
        
        .links a {
            color: var(--primary);
            text-decoration: none;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }
            
            .field.full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <?php 
             include("database/config.php");
             if(isset($_POST['submit'])){
                $username = $_POST['patientName'];
                $doctor = $_POST['DoctorName'];
                $phone = $_POST['phone'];
                $date = $_POST['Date'];
                $time = $_POST['time'];

                // find total appointments of a doctor from a date
                $data = mysqli_query($con,"SELECT COUNT(*) AS TotalAppointments FROM appointments WHERE DoctorID='$doctor' AND AppointmentDate='$date'") or die("Error Occurred");

                $total = mysqli_fetch_assoc($data);
                
                $total = $total['TotalAppointments'];
                $serialNo = $total + 1;
                if($serialNo > 20){
                    echo "<div class='message' style='background-color: #e74c3c;'>
                          <p>Sorry! No more appointments available for this doctor on this date</p>
                      </div>";
                    echo "<a href='appointment.php'><button class='btn'>Go Back</button>";
                    exit();
                }
                
                mysqli_query($con,"INSERT INTO appointments(patientName,PatientPhone,DoctorID,SerialNo,AppointmentDate,AppointmentTime) VALUES('$username','$phone','$doctor','$serialNo','$date','$time')") or die("Error Occurred");

                echo "<div class='message'>
                          <p>Appointment successfully created!</p>
                      </div>";
                echo "<a href='appointment.php'><button class='btn'>Go Home</button>";
             
             } else {
                $query = mysqli_query($con, "SELECT DoctorID, Name, Specialization FROM doctors");
                $doctors = mysqli_fetch_all($query, MYSQLI_ASSOC);
            ?>

            <header>Create An Appointment</header>

            <form action="" method="post">
                <div class="field">
                    <label for="username">Patient Name</label>
                    <input type="text" name="patientName" id="username" autocomplete="off" required>
                </div>
                
                <div class="field">
                    <label for="phone">Patient Phone</label>
                    <input type="text" name="phone" id="phone" autocomplete="off" required>
                </div>

                <div class="field full-width">
                    <label for="doctor">Doctor Name</label>
                    <select name="DoctorName" id="doctor" required>
                        <option value="" disabled selected>Select Doctor</option>
                        <?php foreach ($doctors as $doctor) { ?>
                            <option value="<?php echo $doctor['DoctorID']; ?>">
                                <?php echo $doctor['Name'] . ' - ' . $doctor['Specialization']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="field">
                    <label for="date">Appointment Date</label>
                    <input type="date" name="Date" id="date" required>
                </div>
                
                <div class="field">
                    <label for="time">Appointment Time</label>
                    <input type="time" name="time" id="time" autocomplete="off" required>
                </div>

                <div class="field full-width">
                    <input type="submit" class="btn" name="submit" value="Create Appointment">
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</body>
</html>