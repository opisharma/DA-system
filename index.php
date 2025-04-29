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


<?php
// Placeholder Login Page with upcoming functionality notice
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Coming Soon</title>
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
            padding: 2rem;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .card header {
            font-size: 1.75rem;
            color: #fff;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .message {
            color: #fff;
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }
        .btn-notice {
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            color: #fff;
            transition: background 0.3s ease;
            text-decoration: none;
        }
        .btn-notice:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="card">
        <header>Login</header>
        <div class="message">Rest of functionality will be implemented soon 🚧</div>
        <a href="register.php" class="btn-notice">Go to Sign Up</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
