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
                background: linear-gradient(135deg, #71b7e6, #9b59b6);
                height: 100vh;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .card {
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(10px);
                border: none;
                border-radius: 15px;
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
                text-align: center;
                padding: 2rem;
                max-width: 400px;
                width: 100%;
            }
            .btn {
                border-radius: 50px;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
            }
            .btn-success {
                background: linear-gradient(135deg, #11998e, #38ef7d);
                border: none;
                color: #fff;
                transition: background 0.3s ease;
            }
            .btn-success:hover {
                background: linear-gradient(135deg, #38ef7d, #11998e);
            }
            .btn-danger {
                background: linear-gradient(135deg, #cb2d3e, #ef473a);
                border: none;
                color: #fff;
                transition: background 0.3s ease;
            }
            .btn-danger:hover {
                background: linear-gradient(135deg, #ef473a, #cb2d3e);
            }
            .message {
                color: #fff;
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
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-papO..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.25);
            border: none;
            border-radius: 10px;
            padding: 0.8rem 1rem;
            color: #fff;
        }
        .form-control:focus { background: rgba(255,255,255,0.35); box-shadow: none; color: #fff; }
        .form-label { color: #f5f5f5; font-weight: 500; }
        .btn-primary { background: linear-gradient(135deg, #6a11cb, #2575fc); border: none; border-radius: 50px; padding: 0.75rem; font-weight: 600; transition: background 0.3s; }
        .btn-primary:hover { background: linear-gradient(135deg, #2575fc, #6a11cb); }
        .input-group-text { background: transparent; border: none; color: #fff; }
        .invalid-feedback { color: #ffdede; }
        a { color: #fff; } a:hover { color: #ddd; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4 rounded" style="width:100%; max-width:400px;">
            <h3 class="text-center text-white mb-4">Create Account</h3>
            <form id="regForm" action="" method="post" novalidate>
                <!-- inputs unchanged -->
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                    <div class="invalid-feedback">Please use one of: gmail.com, yahoo.com, outlook.com, icloud.com, hotmail.com.</div>
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                    <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone Number" required pattern="01[3-9][0-9]{8}">
                    <div class="invalid-feedback">Enter a valid Bangladeshi number: 01XXXXXXXXX.</div>
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    <input type="number" name="age" class="form-control" placeholder="Age" required min="1" max="120">
                </div>
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password" required minlength="6">
                </div>
                <div class="d-grid"><button type="submit" name="submit" class="btn btn-primary">Register</button></div>
                <div class="text-center mt-3"><small class="text-white">Already have an account? <a href="index.php">Sign In</a></small></div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const form = document.getElementById('regForm');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const allowed = ['gmail.com','yahoo.com','outlook.com','icloud.com','hotmail.com'];
            function getDomain(address) {
                return address.includes('@') ? address.trim().toLowerCase().split('@').pop() : '';
            }
            email.addEventListener('input', () => {
                if (allowed.includes(getDomain(email.value))) email.classList.remove('is-invalid');
            });
            phone.addEventListener('input', () => {
                if (/^01[3-9][0-9]{8}$/.test(phone.value)) phone.classList.remove('is-invalid');
            });
            form.addEventListener('submit', e => {
                let valid = true;
                if (!allowed.includes(getDomain(email.value))) { email.classList.add('is-invalid'); valid = false; }
                if (!/^01[3-9][0-9]{8}$/.test(phone.value)) { phone.classList.add('is-invalid'); valid = false; }
                if (!valid) { e.preventDefault(); e.stopPropagation(); }
            });
        })();
    </script>
</body>
</html>
