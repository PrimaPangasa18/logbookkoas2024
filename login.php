<?php
error_reporting("E_ALL ^ E_NOTICE");
if ($_COOKIE["user_login"] == "" or empty($_COOKIE["user_login"]) or $_GET["st"] == "new") {
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On-line Logbook Koas Pendidikan Dokter FK-UNDIP</title>
    <link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style/login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>

  <body>
    <div class="login-card">
      <img src="images/undipsolid.png" alt="Universitas Diponegoro Logo">
      <h4>
        E-LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER<br>
        FAKULTAS KEDOKTERAN<br>
        UNIVERSITAS DIPONEGORO<br>
        TAHUN <?php include "config.php";
              echo htmlspecialchars($tahun, ENT_QUOTES, 'UTF-8'); ?>
      </h4>
      <h1>LOGIN</h1>
      <form action="adminproseslogin.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" id="form-password" placeholder="Password" required>
        <div class="checkbox-container">
          <div>
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Keep sign in</label>
          </div>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <div>
            <input type="checkbox" id="form-checkbox">
            <label for="form-checkbox">Show password</label>
          </div>
        </div>
        <input type="submit" value="Submit" name="commit">
        <div class="forgot-password-link">
          <br>
          <a href="login_forgotpass">Forgot Password?</a>
        </div>
      </form>
    </div>
    <script>
      $(document).ready(function() {
        $('#form-checkbox').click(function() {
          var passwordField = $('#form-password');
          if ($(this).is(':checked')) {
            passwordField.attr('type', 'text');
          } else {
            passwordField.attr('type', 'password');
          }
        });
      });
    </script>
  </body>

  </html>
<?php
}
?>
<script>
  document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
  });
</script>
<script>
  document.addEventListener('keydown', function(e) {
    if (e.key === 'F12' ||
      (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'C' || e.key === 'J')) ||
      (e.ctrlKey && e.key === 'U')) {
      e.preventDefault();
    }
  });
</script>