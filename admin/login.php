<?php
session_start();

require_once('../mysqli_connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Fetch username and password from the form
  $username = isset($_POST['username']) ? $_POST['username'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  // Hash the password using MD5
  $hashedPassword = md5($password);

  // Query to check if the provided credentials are valid
  $query = "SELECT * FROM user_t WHERE username = '$username' AND password = '$hashedPassword'";
  $result = $conn->query($query);

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $isActive = $row['active'];

    if ($isActive == '1') {
      // Authentication successful
      $_SESSION['user_id'] = $row['id']; // Store the user ID in session
      $_SESSION['username'] = $username;
      header("Location: ./"); // Redirect to the dashboard page
      exit();
    } else {
      // User is deactivated
      $error = "Your account has been deactivated. Please contact the administrator.";
      echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var toast = document.getElementById("toastError");
                    var toastInstance = new bootstrap.Toast(toast);
                    toastInstance.show();
                });
            </script>';
    }
  } else {
    // Authentication failed
    $error = "Incorrect username or password.";
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var toast = document.getElementById("toastError");
                var toastInstance = new bootstrap.Toast(toast);
                toastInstance.show();
            });
        </script>';
  }
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Sign in - BSoft</title>
  <!-- CSS files -->
  <link href="./dist/css/tabler.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/tabler-flags.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/tabler-payments.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet" />
  <link href="./dist/css/demo.min.css?1684106062" rel="stylesheet" />
  <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
      font-feature-settings: "cv03", "cv04", "cv11";
    }
  </style>
</head>

<body class=" d-flex flex-column">

  <!-- Add this code for the toast message -->
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050">
    <div id="toastError" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
      <div class="toast-header bg-danger text-white">
        <i class="bx bx-error-circle me-2"></i>
        <strong class="me-auto">Error</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <?php echo $error; ?>
      </div>
    </div>
  </div>

  <script src="./dist/js/demo-theme.min.js?1684106062"></script>
  <div class="page page-center">
    <div class="container container-tight py-4">
      <div class="text-center mb-4">
        <a href="." class="navbar-brand navbar-brand-autodark"><img src="../assets/img/logo_bsoft_red.png" height="90" alt=""></a>
      </div>
      <div class="card card-md">
        <div class="card-body">
          <h2 class="h2 text-center mb-4">Login to your account</h2>
          <form id="formAuthentication" method="POST" autocomplete="off" novalidate>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Your Username"
                autocomplete="off" required>
            </div>
            <div class="mb-2">
              <label class="form-label">
                Password
                <!-- <span class="form-label-description">
                    <a href="./forgot-password.html">I forgot password</a>
                  </span> -->
              </label>
              <div class="input-group input-group-flat">
                <input type="password" id="password" class="form-control" name="password" placeholder="Your password"
                  autocomplete="off">
                <span class="input-group-text">
                  <a href="#" class="link-secondary" title="Show password"
                    data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                      stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                      <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                    </svg>
                  </a>
                </span>
              </div>
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Libs JS -->
  <!-- Tabler Core -->
  <script src="./dist/js/tabler.min.js?1684106062" defer></script>
  <script src="./dist/js/demo.min.js?1684106062" defer></script>
</body>

</html>