<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediBook - Healthcare Appointment System</title>
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
        .hero-section {
            background: linear-gradient(135deg, #1e2a38 0%, #2f3f52 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero-section h1 {
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .hero-section p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
            color: #cbd5e1;
        }
        .login-options {
            background-color: white;
            border-radius: 15px;
            padding: 40px;
            margin-top: -80px;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
        }
        .login-card {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .login-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #0d6efd;
        }
        .login-card h3 {
            font-weight: 600;
            margin-bottom: 15px;
        }
        .login-card p {
            color: #6c757d;
            margin-bottom: 25px;
        }
        .btn-login {
            background-color: #1e2a38;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .btn-login:hover {
            background-color: #2f3f52;
        }
        .features-section {
            padding: 80px 0;
        }
        .feature-card {
            text-align: center;
            padding: 30px 20px;
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 20px;
        }
        .feature-card h4 {
            font-weight: 600;
            margin-bottom: 15px;
        }
        .feature-card p {
            color: #6c757d;
        }
        .footer {
            background-color: #1e2a38;
            color: #cbd5e1;
            padding: 40px 0;
            text-align: center;
        }
        .footer a {
            color: #fff;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Medi<span>Book</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Healthcare Made Simple</h1>
            <p>Find and book appointments with the best doctors in your area. Manage your health records and get reminders for upcoming consultations.</p>
        </div>
    </section>

    <!-- Login Options -->
    <section class="container">
        <div class="login-options">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="login-card">
                        <div class="login-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h3>Patient Login</h3>
                        <p>Access your account to book appointments and manage your health records</p>
                        <a href="patientLogin.php" class="btn btn-login">Login as Patient</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="login-card">
                        <div class="login-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h3>Admin Login</h3>
                        <p>Secure access for administrators to manage the system and providers</p>
                        <a href="login.php" class="btn btn-login">Login as Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose MediBook?</h2>
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h4>Easy Appointments</h4>
                        <p>Book appointments with doctors instantly without any hassle</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <h4>Digital Health Records</h4>
                        <p>Access your medical history and reports anytime, anywhere</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h4>Appointment Reminders</h4>
                        <p>Get timely notifications about your upcoming consultations</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4>Contact Us</h4>
                    <p>Email: support@medibook.com</p>
                    <p>Phone: +1 (555) 123-4567</p>
                    <p>&copy; 2025 MediBook. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>