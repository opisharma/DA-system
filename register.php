<?php
include("database/config.php");

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $age      = (int) $_POST['age'];
    $password = $_POST['password'];

    //mail validation
    $allowed = [
        'gmail.com','yahoo.com','outlook.com','icloud.com','hotmail.com'
    ];
    $domain = strtolower(substr(strrchr($email, "@"), 1));
    if (!in_array($domain, $allowed)) {
        echo "<div class='alert alert-danger text-center'>
                Please register with one of these domains: "
             . implode(', ', $allowed) .
             "</div>";
        echo "<div class='text-center'>
                <a href='javascript:self.history.back()' class='btn btn-primary'>
                  Go Back
                </a>
              </div>";
        exit;
    }

    //phone number validation
    if (!preg_match('/^01[3-9][0-9]{8}$/', $phone)) {
        echo "<div class='alert alert-danger text-center'>
                Please enter a valid Bangladeshi phone number (e.g., 013XXXXXXXX).
              </div>";
        echo "<div class='text-center'>
                <a href='javascript:self.history.back()' class='btn btn-primary'>
                  Go Back
                </a>
              </div>";
        exit;
    }

    // ——— CHECK FOR EXISTING EMAIL ———
    $email_safe = mysqli_real_escape_string($con, $email);
    $verify = mysqli_query($con,
        "SELECT Email FROM users WHERE Email='$email_safe'"
    );

    if (mysqli_num_rows($verify) > 0) {
        echo "<div class='alert alert-danger text-center'>
                This email is already used, try another one!
              </div>";
        echo "<div class='text-center'>
                <a href='javascript:self.history.back()' class='btn btn-primary'>
                  Go Back
                </a>
              </div>";
    }
    else {
        // HASH PASSWORD 
        $hash = password_hash($password, PASSWORD_BCRYPT);

        //  INSERT NEW USER
        $u  = mysqli_real_escape_string($con, $username);
        $p  = mysqli_real_escape_string($con, $phone);
        $h  = mysqli_real_escape_string($con, $hash);
        mysqli_query($con,
            "INSERT INTO users
             (Username, Email, Phone, Age, Password)
             VALUES
             ('$u','$email_safe','$p',$age,'$h')"
        ) or die("Error Occurred: " . mysqli_error($con));

        echo "<div class='alert alert-success text-center'>
                Registration successful!
              </div>";
        echo "<div class='text-center'>
                <a href='index.php' class='btn btn-success'>
                  Login Now
                </a>
              </div>";
    }
}
else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
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
        .form-control:focus {
            background: rgba(255, 255, 255, 0.35);
            box-shadow: none;
            color: #fff;
        }
        .form-label {
            color: #f5f5f5;
            font-weight: 500;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            border-radius: 50px;
            padding: 0.75rem;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
        }
        .input-group-text {
            background: transparent;
            border: none;
            color: #fff;
        }
        .invalid-feedback {
            color: #ffdede;
        }
        a {
            color: #fff;
        }
        a:hover {
            color: #ddd;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card p-4">
                    <h3 class="text-center text-white mb-4">Create Account</h3>
                    <form id="regForm" action="" method="post" novalidate>

                        <!-- Username -->
                        <div class="mb-3 input-group">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username" autocomplete="off" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3 input-group">
                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" autocomplete="off" required>
                            <div class="invalid-feedback">
                                Please use one of: gmail.com, yahoo.com, outlook.com, icloud.com, hotmail.com.
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3 input-group">
                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone Number" autocomplete="off" required pattern="01[3-9][0-9]{8}">
                            <div class="invalid-feedback">
                                Enter a valid Bangladeshi number: 01(3-8)XXXXXXXX.
                            </div>
                        </div>

                        <!-- Age -->
                        <div class="mb-3 input-group">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            <input type="number" name="age" id="age" class="form-control" placeholder="Age" autocomplete="off" required min="1" max="120">
                        </div>

                        <!-- Password -->
                        <div class="mb-3 input-group">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off" required minlength="6">
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="submit" id="submitBtn" class="btn btn-primary">Register</button>
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-white">Already have an account? <a href="index.php">Sign In</a></small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const form = document.getElementById('regForm');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const allowed = ['gmail.com', 'yahoo.com', 'outlook.com', 'icloud.com', 'hotmail.com'];

            function getDomain(address) {
                return address.includes('@') ? address.trim().toLowerCase().split('@').pop() : '';
            }

            email.addEventListener('input', () => {
                const domain = getDomain(email.value);
                if (allowed.includes(domain)) email.classList.remove('is-invalid');
            });

            phone.addEventListener('input', () => {
                const validPhone = /^01[3-9][0-9]{8}$/;
                if (validPhone.test(phone.value)) phone.classList.remove('is-invalid');
            });

            form.addEventListener('submit', e => {
                const domain = getDomain(email.value);
                const validPhone = /^01[3-9][0-9]{8}$/;
                if (!allowed.includes(domain)) {
                    e.preventDefault(); e.stopPropagation(); email.classList.add('is-invalid');
                }
                if (!validPhone.test(phone.value)) {
                    e.preventDefault(); e.stopPropagation(); phone.classList.add('is-invalid');
                }
            });
        })();
    </script>
</body>
</html>
<?php } ?>
