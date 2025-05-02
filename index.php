<!-- <?php
session_start();
include("php/config.php");

// Unified message renderer (same style as registration page)
function renderMessagePage($type, $message, $buttonText, $buttonLink) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Status</title>
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
            .btn-success:hover { background: linear-gradient(135deg, #38ef7d, #11998e); }
            .btn-danger {
                background: linear-gradient(135deg, #cb2d3e, #ef473a);
                border: none;
                color: #fff;
                transition: background 0.3s ease;
            }
            .btn-danger:hover { background: linear-gradient(135deg, #ef473a, #cb2d3e); }
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
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // verify credentials
    $result = mysqli_query($con, "SELECT * FROM users WHERE Email='$email'") or die("Select Error: " . mysqli_error($con));
    $user = mysqli_fetch_assoc($result);
    if ($user && password_verify($password, $user['Password'])) {
        // valid login
        $_SESSION['valid'] = $user['Email'];
        $_SESSION['username'] = $user['Username'];
        $_SESSION['age'] = $user['Age'];
        $_SESSION['id'] = $user['Id'];
        header("Location: home.php");
        exit;
    } else {
        renderMessagePage('danger', 'Wrong Email or Password', 'Go Back', 'index.php');
    }
}

// If no POST or after redirect, show form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            padding: 2rem;
            max-width: 400px;
            width: 100%;
        }
        .card header {
            font-size: 1.75rem;
            color: #fff;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-control {
            background: rgba(255,255,255,0.25);
            border: none;
            border-radius: 10px;
            padding: 0.8rem 1rem;
            color: #fff;
        }
        .form-control:focus { background: rgba(255,255,255,0.35); box-shadow: none; color: #fff; }
        .input-group-text { background: transparent; border: none; color: #fff; }
        .btn-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            border-radius: 50px;
            padding: 0.75rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
        }
        .btn-primary:hover { background: linear-gradient(135deg, #2575fc, #6a11cb); }
        .links { text-align: center; margin-top: 1rem; }
        .links a { color: #fff; text-decoration: underline; }
        .links a:hover { color: #ddd; }
    </style>
</head>
<body>
    <div class="card">
        <header>Login</header>
        <form action="" method="post" novalidate>
            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email" required autocomplete="off">
            </div>
            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="off">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="links"><small class="text-white">Don't have an account? <a href="register.php">Sign Up Now</a></small></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> -->

<!-- new design starts-->

<!-- <?php
// session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MediBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }
        .main-container {
            display: flex;
            min-height: 100vh;
        }
        .form-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 40px;
            background-color: #fff;
        }
        .form-container h2 {
            font-weight: 600;
        }
        .form-container p {
            color: #6c757d;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
        }
        .form-container button {
            background-color: #1e2a38;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .form-container button:hover {
            background-color: #2f3f52;
        }
        .info-panel {
            flex: 1;
            background-color: #1e2a38;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 40px;
        }
        .info-panel h2 {
            font-weight: 600;
            font-size: 1.75rem;
        }
        .info-panel p {
            color: #cbd5e1;
            margin-top: 1rem;
        }
        .form-footer {
            text-align: center;
            margin-top: 1rem;
        }
        .form-footer a {
            color: #0d6efd;
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Left form section -->
        <!-- <div class="form-container">
            <h2>Welcome Back</h2>
            <p>Please login to your account</p>
            <form action="login_process.php" method="POST">
                <div class="mb-3 mt-4">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="mb-4">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="w-100">Sign In</button>
            </form>
            <div class="form-footer">
                Don't have an account? <a href="register.php">Sign Up</a>
            </div>
        </div>

        <!-- Right info panel -->
        <!-- <div class="info-panel">
            <h2>Welcome To MediBook! Join Now For Easy Doctor Appointments</h2>
            <p>
                Find and book appointments with the best doctors in your area. 
                Manage your health records and get reminders for upcoming consultations.
            </p>
        </div>
    </div>
</body>
</html> --> --> -->


<!-- new design ends -->
<?php
// Placeholder Login Page with upcoming functionality notice
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login Coming Soon</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
    rel="stylesheet"
  />
  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }
    body {
      margin: 0;
      padding: 0;
      background-color: #f5f7fa;
    }
    .main {
      display: flex;
      min-height: 100vh;
    }
    .left-panel {
      flex: 1;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      max-width: 380px;
      width: 100%;
      text-align: center;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .card h1 {
      font-size: 1.75rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }
    .card .message {
      font-size: 1.1rem;
      color: #555;
      margin-bottom: 2rem;
    }
    .btn-notice {
    display: block;              /* full width */
    width: 100%;
    padding: 0.75rem;            /* vertical padding */
    background-color: #1e2a38;   /* dark navy */
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    border-radius: 6px;          /* subtle rounding */
    text-align: center;
    text-decoration: none;
    transition: background-color 0.2s ease;
    }

    .btn-notice:hover {
    background-color: #2f3f52;   /* a bit lighter on hover */
    }


    .right-panel {
      flex: 1;
      background-color: #1e2a38;
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 60px 40px;
    }
    .right-panel h2 {
      font-size: 1.75rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }
    .right-panel p {
      color: #cbd5e1;
      line-height: 1.5;
    }

    @media (max-width: 768px) {
      .main {
        flex-direction: column;
      }
      .right-panel {
        padding: 40px;
        text-align: center;
      }
    }
  </style>
</head>
<body>
  <div class="main">
    <!-- Left: placeholder card -->
    <div class="left-panel">
      <div class="card">
        <h1>Login</h1>
        <div class="message">
          Rest of functionality will be implemented soon 🚧
        </div>
        <a href="register.php" class="btn-notice">Go to Sign Up</a>
      </div>
    </div>

    <!-- Right: info panel (same as registration) -->
    <div class="right-panel">
      <h2>Welcome to MediBook!<br />Join Now for Easy Doctor Appointments</h2>
      <p>
        Find and book appointments with the best doctors in your area.
        Manage your health records and get reminders for upcoming
        consultations.
      </p>
    </div>
  </div>
</body>
</html>
