<?php
// register.php
include("database/config.php");

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $age      = (int) $_POST['age'];
    $password = $_POST['password'];

    // ——— SERVER-SIDE EMAIL DOMAIN VALIDATION ———
    $allowed = [
        'gmail.com','yahoo.com','outlook.com','icloud.com',
        'aol.com','protonmail.com','zoho.com','hotmail.com'
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

    // ——— SERVER-SIDE PHONE VALIDATION ———
    // 1) starts with 01
    // 2) third digit 3-9
    // 3) followed by 8 digits 0-9
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
        // ——— HASH PASSWORD ———
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // ——— INSERT NEW USER ———
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

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS (optional) -->
  <link rel="stylesheet" href="style/regStyle.css">
</head>
<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4 rounded" style="width:100%; max-width:400px;">
      <h3 class="text-center mb-4">Sign Up</h3>

      <form id="regForm" action="" method="post" novalidate>
        <!-- Username -->
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" id="username"
                 class="form-control" autocomplete="off" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email"
                 class="form-control" autocomplete="off" required>
          <div class="invalid-feedback">
            Please use one of these domains:
            gmail.com, yahoo.com, outlook.com, icloud.com,
            aol.com, protonmail.com, zoho.com, hotmail.com.
          </div>
        </div>

        <!-- Phone -->
        <div class="mb-3">
          <label for="phone" class="form-label">Phone Number</label>
          <input type="tel" name="phone" id="phone"
                 class="form-control" autocomplete="off" required
                 pattern="01[3-9][0-9]{8}">
          <div class="invalid-feedback">
            Enter a valid Bangladeshi number: starts with 01, third digit 3–9, then 8 digits.
          </div>
        </div>

        <!-- Age -->
        <div class="mb-3">
          <label for="age" class="form-label">Age</label>
          <input type="number" name="age" id="age"
                 class="form-control" autocomplete="off"
                 required min="1" max="120">
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" id="password"
                 class="form-control" autocomplete="off"
                 required minlength="6">
        </div>

        <div class="d-grid">
          <button type="submit" name="submit" id="submitBtn"
                  class="btn btn-primary">
            Register
          </button>
        </div>

        <div class="text-center mt-3">
          <small>Already a member? <a href="index.php">Sign In</a></small>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function() {
      const form = document.getElementById('regForm');
      const email = document.getElementById('email');
      const phone = document.getElementById('phone');
      const submitBtn = document.getElementById('submitBtn');
      const allowed = [
        'gmail.com','yahoo.com','outlook.com','icloud.com',
        'aol.com','protonmail.com','zoho.com','hotmail.com'
      ];

      function getDomain(address) {
        return address.includes('@')
          ? address.trim().toLowerCase().split('@').pop()
          : '';
      }

      // live email/phone validation
      email.addEventListener('input', () => {
        const domain = getDomain(email.value);
        if (allowed.includes(domain)) {
          email.classList.remove('is-invalid');
        }
      });

      phone.addEventListener('input', () => {
        const validPhone = /^01[3-9][0-9]{8}$/;
        if (validPhone.test(phone.value)) {
          phone.classList.remove('is-invalid');
        }
      });

      form.addEventListener('submit', e => {
        const domain = getDomain(email.value);
        const validPhone = /^01[3-9][0-9]{8}$/;
        if (!allowed.includes(domain)) {
          e.preventDefault(); e.stopPropagation();
          email.classList.add('is-invalid');
        }
        if (!validPhone.test(phone.value)) {
          e.preventDefault(); e.stopPropagation();
          phone.classList.add('is-invalid');
        }
      });
    })();
  </script>
</body>
</html>
<?php } ?>
