<?php 
   session_start();

   include("database/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: patientLogin.php");
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediBook - Home</title>
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
        .appointments-box {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .appointments-box h2 {
            font-weight: 600;
            margin-bottom: 25px;
            color: #1e2a38;
        }
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-form input {
            border-radius: 8px;
            padding: 8px 15px;
            border: 1px solid #ddd;
        }
        .search-form button {
            border-radius: 8px;
            background-color: #1e2a38;
            color: white;
            border: none;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }
        .search-form button:hover {
            background-color: #0d6efd;
        }
        .table {
            margin-top: 20px;
        }
        .table th {
            font-weight: 600;
            color: #1e2a38;
        }
        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 5px;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-edit {
            background-color: #28a745;
            color: white;
        }
        .btn-edit:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-2px);
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="home.php">Medi<span>Book</span></a>
            
            <div class="ms-auto d-flex align-items-center">
                <?php 
                $id = $_SESSION['id'];
                $query = mysqli_query($con, "SELECT * FROM users WHERE Id=$id");

                while($result = mysqli_fetch_assoc($query)){
                    $res_Uname = $result['Username'];
                    $res_Age = $result['Age'];
                    $res_id = $result['AppointmentId'];
                }
                
                // echo "<a href='edit.php?Id=$res_id'><button class='btn-icon'><i class='fa-solid fa-user'></i></button></a>";
                echo "<a href='doctor.php'><button class='btn btn-dark'></i>Doctor</button></a>";
                echo "<a href='logout.php'><button class='btn-icon'><i class='fa-solid fa-right-from-bracket'></i></button></a>";
                ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Welcome Box -->
        <div class="welcome-box">
            <div class="welcome-message">
                Hello <b><?php echo $res_Uname ?></b>, Welcome
            </div>
            <a href="create_appointment.php" class="btn btn-primary">Create An Appointment</a>
        </div>

        <!-- Appointments Box -->
        <div class="appointments-box">
            <h2>Appointments</h2>
            
            <!-- Search Form -->
            <form class="search-form" action="" method="post">
                <input type="date" name="date" class="form-control">
                <input type="text" name="name" placeholder="Doctor Name" class="form-control">
                <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            
            <!-- Appointments Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient Name</th>
                            <th>Patient Phone</th>
                            <th>Doctor Name</th>
                            <th>Serial No</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($_POST['name']) || isset($_POST['date'])){
                            $name = $_POST['name'] ?? "";
                            $date = $_POST['date'] ?? "";

                            $query = mysqli_query($con, "SELECT 
                                A.AppointmentID,
                                A.patientName,
                                A.PatientPhone,
                                D.Name,
                                A.SerialNo,
                                A.AppointmentDate,
                                A.AppointmentTime
                            FROM
                                appointments A
                            JOIN
                                Doctors D ON A.DoctorID = D.DoctorID
                            WHERE
                                D.Name = '$name' OR A.AppointmentDate = '$date';");
                            
                            $appointments = mysqli_fetch_all($query, MYSQLI_ASSOC);
                        } else {
                            $query = mysqli_query($con, "SELECT 
                                A.AppointmentID,
                                A.PatientName,
                                A.PatientPhone,
                                D.Name,
                                A.SerialNo,
                                A.AppointmentDate,
                                A.AppointmentTime
                            FROM 
                                appointments A
                            JOIN 
                                doctors D ON A.DoctorID = D.DoctorID;");
                            $appointments = mysqli_fetch_all($query, MYSQLI_ASSOC);
                        }

                        foreach ($appointments as $appointment) {
                            echo "<tr>";
                            echo "<td>{$appointment['AppointmentID']}</td>";
                            echo "<td>{$appointment['PatientName']}</td>";
                            echo "<td>{$appointment['PatientPhone']}</td>";
                            echo "<td>{$appointment['Name']}</td>";
                            echo "<td>{$appointment['SerialNo']}</td>";
                            echo "<td>{$appointment['AppointmentDate']}</td>";
                            echo "<td>{$appointment['AppointmentTime']}</td>";
                    
                            echo "<td>";
                            echo "<a href='update.php?id={$appointment['AppointmentID']}' class='action-btn btn-edit'><i class='fa-solid fa-pen-to-square'></i></a>";
                            echo "<a href='delete.php?id={$appointment['AppointmentID']}' class='action-btn btn-delete' onclick='return confirm(\"Are you sure you want to delete this appointment?\");'><i class='fa-solid fa-trash'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>