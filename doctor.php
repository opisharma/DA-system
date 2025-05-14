<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Specialization'])) {
    $specialization = urlencode($_POST['Specialization']);
    header("Location: doctors.php?Specialization=$specialization");
    exit();
}

include 'database/config.php';
if (!isset($_SESSION['valid'])) {
    header('Location: patientLogin.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediBook - Doctors</title>
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

        .doctors-box {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .doctors-box h2 {
            font-weight: 600;
            margin-bottom: 25px;
            color: #1e2a38;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
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

        .doctor-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .doctor-card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .doctor-card h3 {
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #0d6efd;
        }

        .doctor-card p {
            margin: 8px 0;
        }

        .specialization {
            font-style: italic;
            color: #555;
            font-weight: 500;
        }

        .contact-info {
            display: flex;
            align-items: center;
            margin: 5px 0;
            color: #666;
        }

        .contact-info i {
            margin-right: 10px;
            color: #0d6efd;
        }

        .no-results {
            text-align: center;
            padding: 20px;
            font-weight: 500;
            color: #666;
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
                
                while ($result = mysqli_fetch_assoc($query)) {
                    $res_Uname = $result['Username'];
                    $res_Age = $result['Age'];
                    $res_id = $result['Id'];
                }
                
                echo "<a href='edit.php?Id=$res_id'><button class='btn-icon'><i class='fa-solid fa-user'></i></button></a>";
                echo "<a href='appointment.php'><button class='btn btn-dark'>Appointments</button></a>";
                echo "<a href='php/logout.php'><button class='btn-icon'><i class='fa-solid fa-right-from-bracket'></i></button></a>";
                ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Doctors Box -->
        <div class="doctors-box">
            <h2>Doctors Directory</h2>

            <!-- Search Form -->
            <form class="search-form" action="" method="get">
                <input type="text" name="Specialization" placeholder="Search Doctor by Specialization"
                    class="form-control" value="<?php echo isset($_GET['Specialization']) ? htmlspecialchars($_GET['Specialization']) : ''; ?>">
                <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <!-- Doctors Cards -->
            <div class="doctor-cards">
                <?php
             if (isset($_GET['Specialization']) && !empty($_GET['Specialization'])) {
    $Specialization = mysqli_real_escape_string($con, $_GET['Specialization']);
    $query = mysqli_query($con, "SELECT * FROM doctors WHERE Specialization LIKE '%$Specialization%'");
    $doctors = mysqli_fetch_all($query, MYSQLI_ASSOC);

    if (count($doctors) == 0) {
        echo "<div class='no-results col-12'><h3>No doctor found in this specialization</h3></div>";
    }
} else {
    $query = mysqli_query($con, "SELECT * FROM doctors");
    $doctors = mysqli_fetch_all($query, MYSQLI_ASSOC);
}
                
                foreach ($doctors as $doctor) { ?>
                <div class="doctor-card">
                    <h3><?php echo $doctor['Name']; ?></h3>
                    <p class="specialization"><?php echo $doctor['Specialization']; ?></p>
                    <p class="contact-info"><i class="fas fa-phone"></i> <?php echo $doctor['ContactNumber']; ?></p>
                    <p class="contact-info"><i class="fas fa-envelope"></i> <?php echo $doctor['Email']; ?></p>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
