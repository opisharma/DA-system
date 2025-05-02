<?php
include("database/config.php");

// Unified message renderer
function renderMessagePage($type, $message, $buttonText, $buttonLink) {
    // $type: 'success' or 'danger'
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Status</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: #f5f7fa;
                height: 100vh;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .card {
                background: #fff;
                border: none;
                border-radius: 10px;
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
                text-align: center;
                padding: 2rem;
                max-width: 400px;
                width: 100%;
            }
            .btn {
                border-radius: 5px;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
            }
            .btn-success {
                background: #3d7cf4;
                border: none;
                color: #fff;
                transition: background 0.3s ease;
            }
            .btn-success:hover {
                background: #2a68d4;
            }
            .btn-danger {
                background: #f44336;
                border: none;
                color: #fff;
                transition: background 0.3s ease;
            }
            .btn-danger:hover {
                background: #d32f2f;
            }
            .message {
                color: #333;
                margin-bottom: 1.5rem;
                font-size: 1.25rem;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <div class="message alert alert-<?= $type ?>" role="alert" style="background: transparent; border: none; padding: 0;">
                <?= $message ?>
            </div>
            <a href="<?= $buttonLink ?>" class="btn btn-<?= $type ?>"><?= $buttonText ?></a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $age      = (int) $_POST['age'];
    $password = $_POST['password'];

    // mail validation
    $allowed = ['gmail.com','yahoo.com','outlook.com','icloud.com','hotmail.com'];
    $domain = strtolower(substr(strrchr($email, "@"), 1));
    if (!in_array($domain, $allowed)) {
        renderMessagePage('danger', 'Please register with one of these domains: ' . implode(', ', $allowed), 'Go Back', 'javascript:self.history.back()');
    }

    // phone validation
    if (!preg_match('/^01[3-9][0-9]{8}$/', $phone)) {
        renderMessagePage('danger', 'Please enter a valid Bangladeshi phone number (e.g., 013XXXXXXXX).', 'Go Back', 'javascript:self.history.back()');
    }

    // existing email check
    $email_safe = mysqli_real_escape_string($con, $email);
    $verify = mysqli_query($con, "SELECT Email FROM users WHERE Email='$email_safe'");
    if (mysqli_num_rows($verify) > 0) {
        renderMessagePage('danger', 'This email is already used, try another one!', 'Go Back', 'javascript:self.history.back()');
    }

    // all good, insert user
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $u  = mysqli_real_escape_string($con, $username);
    $p  = mysqli_real_escape_string($con, $phone);
    $h  = mysqli_real_escape_string($con, $hash);
    mysqli_query($con, "INSERT INTO users (Username, Email, Phone, Age, Password) VALUES ('$u','$email_safe','$p',$age,'$h')")
        or renderMessagePage('danger', 'Error Occurred: ' . mysqli_error($con), 'Go Back', 'javascript:self.history.back()');

    renderMessagePage('success', 'Registration successful!', 'Login Now', 'index.php');
}

// If not POST, show form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MediBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        html, body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
            margin: 0;
            height: 100vh;
            overflow: hidden;
        }
        .container-fluid {
            height: 100vh;
            max-width: 1200px;
            padding: 0;
            margin: 0 auto;
        }
        .row {
            height: 100%;
            margin: 0;
        }
        .left-panel {
            background: #fff;
            padding: 1.5rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100vh;
            overflow-y: auto;
        }
        .right-panel {
            background: #1e293b;
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            height: 100vh;
            overflow-y: auto;
        }
        .form-control {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 0.5rem 0.75rem; /* Reduced padding */
            margin-bottom: 0.5rem; /* Reduced margin */
            height: auto; /* Auto height based on content */
            font-size: 0.9rem; /* Smaller font */
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #3d7cf4;
        }
        .form-check-input:checked {
            background-color: #3d7cf4;
            border-color: #3d7cf4;
        }
        h1 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.5rem; /* Smaller heading */
        }
        .subtitle {
            color: #64748b;
            margin-bottom: 1rem;
            font-size: 0.85rem; /* Smaller subtitle */
        }
        .form-label {
            margin-bottom: 0.25rem; /* Reduced margin */
            font-size: 0.85rem; /* Smaller label */
        }
        .mb-3 {
            margin-bottom: 0.75rem !important; /* Reduced margin between form groups */
        }
        .btn-primary {
            background: #1e293b;
            border: none;
            border-radius: 5px;
            padding: 0.5rem;
            font-weight: 500;
            transition: background 0.3s;
            width: 100%;
            margin-top: 0.75rem;
            font-size: 0.9rem; /* Smaller button text */
        }
        .btn-primary:hover {
            background: #334155;
        }
        .form-check-label {
            color: #64748b;
            font-size: 0.8rem; /* Smaller label */
        }
        .sign-in-link {
            color: #64748b;
            text-align: center;
            margin-top: 0.75rem;
            font-size: 0.8rem; /* Smaller text */
        }
        .sign-in-link a {
            color: #3d7cf4;
            text-decoration: none;
        }
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .chart {
            margin-top: 1rem;
        }
        .stats-card h5 {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1rem; /* Smaller heading */
        }
        .appointment-stat {
            margin-top: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .bullet-chart {
            display: flex;
            margin-top: 0.75rem;
            justify-content: space-between;
            height: 70px; /* Reduced chart height */
        }
        .bullet-chart .bar {
            width: 8%;
            height: 70px; /* Reduced bar height */
            background: #e2e8f0;
            position: relative;
        }
        .bullet-chart .bar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #3d7cf4;
        }
        .bullet-chart .bar:nth-child(1)::after { height: 70%; }
        .bullet-chart .bar:nth-child(2)::after { height: 50%; }
        .bullet-chart .bar:nth-child(3)::after { height: 65%; }
        .bullet-chart .bar:nth-child(4)::after { height: 40%; }
        .bullet-chart .bar:nth-child(5)::after { height: 30%; }
        .bullet-chart .bar:nth-child(6)::after { height: 45%; }
        .bullet-chart .bar:nth-child(7)::after { height: 35%; }
        .bullet-chart .bar:nth-child(8)::after { height: 60%; }
        .bullet-chart .bar:nth-child(9)::after { height: 50%; }
        .bullet-chart .bar:nth-child(10)::after { height: 75%; }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 0.75rem;
        }
        .pagination .dot {
            width: 6px;
            height: 6px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            margin: 0 3px;
        }
        .pagination .dot.active {
            background: rgba(255,255,255,1);
        }
        .input-group-text {
            background: transparent;
            border: 1px solid #e2e8f0;
            border-right: none;
            padding: 0.5rem; /* Reduced padding */
        }
        .input-group .form-control {
            border-left: none;
            margin-bottom: 0;
        }
        .input-password {
            padding-right: 30px;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            cursor: pointer;
            color: #64748b;
            font-size: 0.85rem; /* Smaller icon */
        }
        /* Position the toggle icon within the password input */
        .password-field {
            position: relative;
        }
        p {
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }
        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        /* Adjust spacing for right panel content */
        .right-panel h1 {
            margin-bottom: 0.5rem;
        }
        .right-panel p {
            margin-bottom: 0.75rem;
        }
        /* Ensure proper vertical spacing */
        .content-wrapper {
            max-height: 100%;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 left-panel">
                <div class="content-wrapper">
                    <h1>Create Your Account</h1>
                    <p class="subtitle">Welcome! Please enter your details</p>
                    
                    <form id="regForm" action="" method="post" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Name</label>
                            <input type="text" name="username" class="form-control" placeholder="Enter your name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                            <div class="invalid-feedback">Please use one of: gmail.com, yahoo.com, outlook.com, icloud.com, hotmail.com.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter your phone number" required pattern="01[3-9][0-9]{8}">
                            <div class="invalid-feedback">Enter a valid Bangladeshi number: 01(3-9)XXXXXXXX.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" placeholder="Enter your age" required min="1" max="120">
                        </div>
                        
                        <div class="mb-3 password-field">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control input-password" placeholder="Create a password" required minlength="6">
                            <i class="fas fa-eye-slash toggle-password" id="togglePassword"></i>
                        </div>
                        
                        <div class="mb-3 password-field">
                            <label for="retypePassword" class="form-label">Retype Password</label>
                            <input type="password" name="retypePassword" id="retypePassword" class="form-control input-password" placeholder="Confirm your password" required minlength="6">
                            <i class="fas fa-eye-slash toggle-password" id="toggleRetypePassword"></i>
                        </div>
                    
                      <p id='error'></p>
                        
                        
                        <button type="submit" name="submit" class="btn btn-primary">Sign in</button>
                        
                        <div class="sign-in-link">
                            Already have an account? <a href="index.php">Sign In</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-md-6 right-panel">
                <div class="content-wrapper">
                    <h1>Welcome To MediBook! Join Now For Easy Doctor Appointments</h1>
                    <p>Find and book appointments with the best doctors in your area. Manage your health records and get reminders for upcoming consultations.</p>
                    
                    <!-- <div class="stats-card">
                        <h5>Appointment Analytics</h5>
                        <div class="d-flex justify-content-between">
                            <span style="font-size: 0.8rem;">Doctor Specialties</span>
                            <div>
                                <span class="badge bg-primary rounded-pill">Daily</span>
                                <span class="badge bg-light text-dark rounded-pill">Monthly</span>
                                <span class="badge bg-light text-dark rounded-pill">Yearly</span>
                            </div>
                        </div>
                        
                        <div class="chart">
                            <div class="bullet-chart">
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pagination">
                        <div class="dot active"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const form = document.getElementById('regForm');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const password = document.getElementById('password');
            const retypePassword = document.getElementById('retypePassword');
            const togglePassword = document.getElementById('togglePassword');
            const toggleRetypePassword = document.getElementById('toggleRetypePassword');
            const allowed = ['gmail.com','yahoo.com','outlook.com','icloud.com','hotmail.com'];
            const error = document.getElementById('error');
         
            
            function getDomain(address) {
                return address.includes('@') ? address.trim().toLowerCase().split('@').pop() : '';
            }
            
            email.addEventListener('input', () => {
                if (allowed.includes(getDomain(email.value))) email.classList.remove('is-invalid');
            });
            
            phone.addEventListener('input', () => {
                if (/^01[3-9][0-9]{8}$/.test(phone.value)) phone.classList.remove('is-invalid');
            });
            
            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
            
            toggleRetypePassword.addEventListener('click', function() {
                const type = retypePassword.getAttribute('type') === 'password' ? 'text' : 'password';
                retypePassword.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
            
            form.addEventListener('submit', e => {
                let valid = true;
                
                if (!allowed.includes(getDomain(email.value))) { 
                    email.classList.add('is-invalid'); 
                    valid = false; 
                }
                
                if (!/^01[3-9][0-9]{8}$/.test(phone.value)) { 
                    phone.classList.add('is-invalid'); 
                    valid = false; 
                }
                
                if (password.value !== retypePassword.value) {
                    retypePassword.classList.add('is-invalid');
                
                    error.innerHTML = "Passwords do not match";
                    error.style.color = "red";
                    valid = false;
                }
                
                if (!valid) { 
                    e.preventDefault(); 
                    e.stopPropagation(); 
                }
            });
        })();
    </script>
</body>
</html>