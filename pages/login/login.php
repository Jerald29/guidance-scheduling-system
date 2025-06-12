<?php
require '../../includes/conn.php';
ob_start();
session_start();

if (isset($_SESSION['role'])) {
    header("location: pages/dashboard/index.php");
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log In | GCS Bacoor Campus</title>
  <link rel="icon" href="../../docs/assets/img/gcs-bac.png">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
  
  <style>
    .login-box {
      width: 350px; 
    }

    .card {
      padding: 0px; 
    }

    .password-toggle {
      cursor: pointer;
      position: relative;
      transition: opacity 0.3s ease, transform 0.1s ease; 
    }

    .password-toggle:hover {
      opacity: 0.7; 
    }

    .input-group .form-control {
      transition: border-color 0.3s ease; 
    }

    /* Container to hold the icon animation effect */
    .icon-wrapper {
      display: inline-block;
      position: relative;
    }

    .fa-eye {
      transition: transform 0.1s ease; 
    }

    /* Center the academic image */
    .centered-image {
      display: block;
      margin: 20px auto 0 auto;
      max-width: 60%;
      margin-left: 66.5px;
      height: auto;
    }

    /* Overall styling for the input group */
    .input-group {
      width: 100%; 
    }

    .input-field {
      height: 37px; 
      border-radius: 6px; 
      border: 1px solid #ced4da; 
      transition: all 0.3s; 
      padding: 0.375rem 1rem; 
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
    }

    /* Hover effect for the input fields */
    .input-field:hover {
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); 
      border-color: #20c997; 
    }

    /* Ensure consistent size for icon boxes */
    .input-group-text {
      display: flex; 
      justify-content: center; 
      align-items: center; 
      width: 37px; 
      height: 37px; 
      border-radius: 6px; 
      background-color: #f8f9fa; 
      border: 1px solid #ced4da; 
      cursor: pointer; 
      transition: background-color 0.3s; 
    }

    /* Change background color on hover for icon box */
    .icon-wrapper:hover {
      background-color: #e2e6ea; 
    }

    /* Ensure password toggle icon box has the same size */
    .password-toggle {
      width: 37px; 
      height: 37px; 
    }

    /* Optional: styling for the icons */
    .icon-wrapper span {
      color: #20c997; 
      font-size: 0.95rem; 
    }

    /* Optional: for better focus visibility */
    .input-field:focus {
      outline: none; 
      border-color: #20c997; 
    }

    .custom-button {
      background-color: #20c997; 
      color: white; 
      transition: background-color 0.3s, box-shadow 0.3s; 
    }

    .custom-button:hover {
      background-color: #17a2b8; 
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .custom-button:focus {
      outline: none; 
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); 
    }

    /* Style for Caps Lock warning */
    .caps-lock-warning {
      color: red;
      font-size: 0.875rem;
      display: none;
    }

  </style>
  
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-teal">
    <div class="card-header bg-teal text-center" style="font-family: 'Cambria', serif;"> 
        <a href="https://stfrancisbacoor.com" target="_blank"> 
            <img class="mb-2" height="75" width="75" src="../../docs/assets/img/gcs-bac.png" alt="logo-signin"> 
        </a>
        <h5 style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);"><b>Saint Francis of Assisi College Bacoor Campus</b></h5>
        <h6 style="text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);"><b>Guidance Counseling System</b></h6>
    </div>
    <div class="card-body">
      <p class="login-box-msg" style="font-family: 'Cambria', serif;">Sign in to your account.</p>
      <form action="userData/ctrl.login.php" method="POST">
        <div class="input-group mb-3">
          <input type="text" class="form-control input-field" name="username" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text icon-wrapper">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control input-field" id="password" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text icon-wrapper password-toggle" onclick="togglePassword()">
              <span class="fas fa-eye" id="toggleIcon"></span>
            </div>
          </div>
        </div>
        <p id="capsLockWarning" class="caps-lock-warning">Caps Lock is ON</p>
        <div class="row">
          <div class="col-12 text-center">
            <button type="submit" name="submit" class="btn btn-outline-teal custom-button" style="background-color: #20c997; color: white;">Sign In</button>
          </div>
        </div>
        <div class="row">
          <div class="col-12 text-center">
            <img src="../../docs/assets/img/acads.png" alt="Academics" class="centered-image">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- Toastr -->
<script src="../../plugins/toastr/toastr.min.js"></script>

<!-- JavaScript for toggling password visibility -->
<script>
  function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    // Toggle between password and text
    if (passwordField.type === 'password') {
      passwordField.type = 'text';
      toggleIcon.classList.remove('fa-eye');
      toggleIcon.classList.add('fa-eye-slash');
    } else {
      passwordField.type = 'password';
      toggleIcon.classList.remove('fa-eye-slash');
      toggleIcon.classList.add('fa-eye');
    }
  }
    // New: Caps Lock warning functionality
    const passwordField = document.getElementById('password');
    const capsLockWarning = document.getElementById('capsLockWarning');

    // Detect Caps Lock key press
    passwordField.addEventListener('keyup', function(event) {
    if (event.getModifierState('CapsLock')) {
      capsLockWarning.style.display = 'block';
    } else {
      capsLockWarning.style.display = 'none';
    }
  });

  function eyeFollow(event) {
    const toggleIcon = document.getElementById('toggleIcon');
    const iconRect = toggleIcon.getBoundingClientRect();

    // Calculate the center of the eye icon
    const centerX = iconRect.left + (iconRect.width / 2);
    const centerY = iconRect.top + (iconRect.height / 2);

    // Calculate the angle between the cursor and the center of the icon
    const angle = Math.atan2(event.clientY - centerY, event.clientX - centerX);

    // Move the eye icon in the direction of the cursor
    const xOffset = Math.cos(angle) * 5; // Control movement by reducing magnitude
    const yOffset = Math.sin(angle) * 5;

    // Apply the movement to the icon using a transform
    toggleIcon.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
  }
</script>

<?php
if (isset($_SESSION['password_incorrect'])) {
  echo "<script>
    $(function() {
      toastr.error('Password is incorrect.','Error')
    });
    </script>";
} elseif (isset($_SESSION['username_incorrect'])) {
  echo "<script>
    $(function() {
      toastr.error('Username is incorrect.','Error')
    });
    </script>";
}
session_destroy();
?>

</body>
</html>

        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                            Miguel Capule                                * *-->
        <!-- * *               Guidance Counseling System Login Module                   * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
