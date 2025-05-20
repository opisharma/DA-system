<?php
session_start();
include("database/config.php");

// Initialize error message
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mysqli_real_escape_string($con, trim($_POST['email']));
    $password = $_POST['password'];

    $result = mysqli_query(
        $con,
        "SELECT id, Username, Age, Email, Password
         FROM admin
         WHERE Email = '$email'"
    ) or die("DB error: " . mysqli_error($con));

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify submitted password against the stored hash
        if (password_verify($password, $row['Password'])) {
            // Password is correct, set session and redirect
            $_SESSION['valid']    = $row['Email'];
            $_SESSION['username'] = $row['Username'];
            $_SESSION['age']      = $row['Age'];
            $_SESSION['id']       = $row['id'];
            header("Location: appointment.php");
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Invalid email or password.';
    }
}
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
        * { font-family: 'Poppins', sans-serif; }
        body { margin: 0; padding: 0; background-color: #f5f7fa; }
        .main-container { display: flex; min-height: 100vh; }
        .form-container { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 60px 40px; background-color: #fff; }
        .form-container h2 { font-weight: 600; }
        .form-container p { color: #6c757d; }
        .form-control { border-radius: 8px; padding: 10px 15px; }
        .form-container button { background-color: #1e2a38; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; transition: background 0.3s ease; width: 100%; }
        .form-container button:hover { background-color: #2f3f52; }
        .info-panel { flex: 1; background-color: #1e2a38; color: white; display: flex; flex-direction: column; justify-content: center; padding: 60px 40px; }
        .info-panel h2 { font-weight: 600; font-size: 1.75rem; }
        .info-panel p { color: #cbd5e1; margin-top: 1rem; }
        .form-footer { text-align: center; margin-top: 1rem; }
        .form-footer a { color: #0d6efd; text-decoration: none; }
        .form-footer a:hover { text-decoration: underline; }
        .alert-custom { margin-bottom: 1rem; }
    </style>
</head>
<body>
  <div class="main-container">
    <div class="form-container">
      <h2>Welcome Back</h2>
      <p>Please login to your account</p>

      <?php if ($error): ?>
        <div class="alert alert-danger alert-custom" role="alert"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="mb-3 mt-4">
          <label>Email</label>
          <input type="email" name="email" class="form-control" placeholder="Enter your email"
                 value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
        </div>
        <div class="mb-4">
          <label>Password</label>
          <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" name="submit">Sign In</button>
      </form>
      <div class="form-footer">
        Don't have an account? <a href="register.php">Sign Up</a>
      </div>
    </div>

    <div class="info-panel">
      <h2>Welcome To MediBook! Join Now For Easy Doctor Appointments</h2>
      <p>Find and book appointments with the best doctors in your area. Manage your health records and get reminders for upcoming consultations.</p>
    </div>
  </div>
</body>
</html>
