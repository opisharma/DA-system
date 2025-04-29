<?php
include("database/config.php");

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $age      = (int)$_POST['age'];
    $password = $_POST['password'];

    // Email domain validation
    $allowed = ['gmail.com', 'yahoo.com', 'outlook.com', 'icloud.com', 'hotmail.com'];
    $domain = strtolower(substr(strrchr($email, "@"), 1));
    if (!in_array($domain, $allowed)) {
        echo "<div class='alert alert-danger text-center alert-dismissible fade show mt-3' role='alert'>
                Please register with one of these domains: " . implode(', ', $allowed) . "
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
        echo "<div class='text-center mt-2'>
                <a href='javascript:self.history.back()' class='btn btn-outline-primary'>Go Back</a>
              </div>";
        exit;
    }

    // Phone number validation
    if (!preg_match('/^01[3-9][0-9]{8}$/', $phone)) {
        echo "<div class='alert alert-danger text-center alert-dismissible fade show mt-3' role='alert'>
                Please enter a valid Bangladeshi phone number (e.g., 013XXXXXXXX).
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
        echo "<div class='text-center mt-2'>
                <a href='javascript:self.history.back()' class='btn btn-outline-primary'>Go Back</a>
              </div>";
        exit;
    }

    // Check existing email
    $email_safe = mysqli_real_escape_string($con, $email);
    $verify = mysqli_query($con, "SELECT Email FROM users WHERE Email='$email_safe'");

    if (mysqli_num_rows($verify) > 0) {
        echo "<div class='alert alert-danger text-center alert-dismissible fade show mt-3' role='alert'>
                This email is already used, try another one!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
        echo "<div class='text-center mt-2'>
                <a href='javascript:self.history.back()' class='btn btn-outline-primary'>Go Back</a>
              </div>";
    } else {
        // Hash password and insert user
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $u = mysqli_real_escape_string($con, $username);
        $p = mysqli_real_escape_string($con, $phone);
        $h = mysqli_real_escape_string($con, $hash);

        mysqli_query($con, "INSERT INTO users (Username, Email, Phone, Age, Password)
            VALUES ('$u','$email_safe','$p',$age,'$h')") 
            or die("Error Occurred: " . mysqli_error($con));

        echo "<div class='alert alert-success text-center alert-dismissible fade show mt-3' role='alert'>
                Registration successful!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
        echo "<div class='text-center mt-2'>
                <a href='index.php' class='btn btn-success'>Login Now</a>
              </div>";
    }
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Clean & Cool Design</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            font-family: 'Poppins', sans-serif;
        }

        .card {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: #2575fc;
        }

        .btn-primary {
            background: linear-gradient(to right, #2575fc, #6a11cb);
            border: none;
            transition: transform 0.2s ease;
        }

        .btn-primary:hover {
            transform: scale(1.03);
        }

        .input-group-text {
            background-color: transparent;
            border: none;
        }

        .toggle-password {
            cursor: pointer;
            color: #6c757d;
        }
    </style>
</head>

<body class="min-vh-100 d-flex align-items-center justify-content-center p-3">
    <div class="card w-100 shadow-lg" style="max-width: 420px;">
        <div class="card-body p-4">
            <h3 class="text-center fw-bold mb-4">Create Account</h3>

            <form id="regForm" action="" method="post" novalidate>
                <!-- Username -->
                <div class="form-floating mb-3">
                    <input type="text" name="username" id="username" class="form-control" required>
                    <label for="username"><i class="bi bi-person"></i> Username</label>
                </div>

                <!-- Email -->
                <div class="form-floating mb-3">
                    <input type="email" name="email" id="email" class="form-control" autocomplete="off" required>
                    <label for="email"><i class="bi bi-envelope"></i> Email</label>
                    <div class="invalid-feedback">
                        Invalid email domain. Use Gmail, Yahoo, Outlook, iCloud, or Hotmail.
                    </div>
                </div>

                <!-- Phone -->
                <div class="form-floating mb-3">
                    <input type="tel" name="phone" id="phone" class="form-control" required pattern="01[3-9][0-9]{8}">
                    <label for="phone"><i class="bi bi-telephone"></i> Phone Number</label>
                    <div class="invalid-feedback">
                        Enter a valid Bangladeshi number (e.g., 013XXXXXXXX).
                    </div>
                </div>

                <!-- Age -->
                <div class="form-floating mb-3">
                    <input type="number" name="age" id="age" class="form-control" required min="1" max="120">
                    <label for="age"><i class="bi bi-calendar3"></i> Age</label>
                </div>

                <!-- Password -->
                <div class="form-floating mb-3 position-relative">
                    <input type="password" name="password" id="password" class="form-control" required minlength="6">
                    <label for="password"><i class="bi bi-lock"></i> Password</label>
                    <span class="position-absolute end-0 top-50 translate-middle-y me-3 toggle-password"
                          onclick="togglePassword()">
                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                    </span>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" name="submit" id="submitBtn" class="btn btn-primary py-2">Register</button>
                </div>

                <div class="text-center mt-3">
                    <small>Already have an account? <a href="index.php" class="text-decoration-none">Login</a></small>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (pass.type === "password") {
                pass.type = "text";
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                pass.type = "password";
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }

        (function () {
            const form = document.getElementById('regForm');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const allowedDomains = ['gmail.com', 'yahoo.com', 'outlook.com', 'icloud.com', 'hotmail.com'];

            function getDomain(email) {
                return email.split('@')[1]?.toLowerCase();
            }

            form.addEventListener('submit', e => {
                let valid = true;

                const domain = getDomain(email.value);
                if (!allowedDomains.includes(domain)) {
                    e.preventDefault();
                    email.classList.add('is-invalid');
                    valid = false;
                }

                const phoneRegex = /^01[3-9][0-9]{8}$/;
                if (!phoneRegex.test(phone.value)) {
                    e.preventDefault();
                    phone.classList.add('is-invalid');
                    valid = false;
                }

                if (valid) {
                    email.classList.remove('is-invalid');
                    phone.classList.remove('is-invalid');
                }
            });
        })();
    </script>
</body>
</html>
<?php } ?>