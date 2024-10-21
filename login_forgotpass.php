<?php
error_reporting(E_ALL ^ E_NOTICE);
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
      <h1>Forgot Password</h1>
      <form action="login_prosesforgotpasas" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="submit" value="Submit" name="commit">
      </form>
      <br>
      <div class="forgot-password-link">
        <form action="login">
          <input type="submit" value="Cancel" />
        </form>
      </div>
      <br>
    </div>




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