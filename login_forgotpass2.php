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
      <h4 id="timer">Time remaining: 02:00</h4>

      <div class="forgot-password-link">
        <br>
        <form action="login_forgotpass">
            <input type="submit" value="Try Again" />
        </form>
      </div>
      <br>
      <form action="login_setnewpassword" method="POST">
        

        <input type="hidden" name="username" value="<?php echo htmlspecialchars($_GET['username']); ?>">
        <input type="text" name="code" placeholder="Enter the verification code" required>
        <input type="password" name="new_password" id="new_password" placeholder="Enter your new password" required>
        <div class="checkbox-container">
          <div>
            <input type="checkbox" id="form-checkbox">
            <label for="form-checkbox">Show password</label>
          </div>
        </div>
        <input type="submit" value="Submit" name="verify_code">
      </form>
    </div>

    <style>
      /* Initially hide the Try Again button */
      .forgot-password-link {
        display: none;
      }

      .login-card .forgot-password-link button {
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        width: 100%;
        padding: 0.75em;
        font-size: 1em;
        cursor: pointer;
        transition: background-color 0.3s;
      }

      .login-card .forgot-password-link button:hover {
        background-color: #218838;
      }
    </style>
    
    

  <script>
    // Timer countdown function
    var timeLeft = 120; // 2 minutes in seconds

    var timerInterval = setInterval(function() {
      var minutes = Math.floor(timeLeft / 60);
      var seconds = timeLeft % 60;
      if (seconds < 10) seconds = "0" + seconds;
      $('#timer').text('Time remaining: ' + minutes + ':' + seconds);

      timeLeft--;

      if (timeLeft < 0) {
        clearInterval(timerInterval);
        $('#timer').text('Your Code has been Expired');

        $('.forgot-password-link').css('display', 'block');

        // Send AJAX request to reset 'stase' in the database
        var username = "<?php echo htmlspecialchars($_GET['username']); ?>";
        $.ajax({
          url: 'resetcode.php',
          type: 'POST',
          data: { username: username },
          success: function(response) {
            alert('Code has expired, please request a new one.');
          }
        });
      }
    }, 1000);

      $(document).ready(function() {
        $('#form-checkbox').click(function() {
          var passwordField = $('#new_password');
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