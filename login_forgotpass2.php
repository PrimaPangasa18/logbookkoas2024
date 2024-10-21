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
    <div class="login-card" style="background-color: rgba(255, 255, 255, 0.38);">

      <img src="images/undipsolid.png" alt="Universitas Diponegoro Logo">
      <h4>
        E-LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER<br>
        FAKULTAS KEDOKTERAN<br>
        UNIVERSITAS DIPONEGORO<br>
        TAHUN <?php include "config.php";
              echo htmlspecialchars($tahun, ENT_QUOTES, 'UTF-8'); ?>
      </h4>
      <?php
      $email = $_GET["email"]
      ?>
      <h1>Forgot Password</h1>
      <h4 style="color: #000; text-shadow: none">Kami telah mengirimkan email ke <span style="color: blue;"><?php echo $email ?></span><br> cek Kotak Masuk Email anda
      </h4>
      <h4 id="timer">Time remaining: 02:00</h4>

      <div class="forgot-password-link">
        <br>
        <form action="login_forgotpass">
          <input type="submit" value="Try Again" />
        </form>
      </div>
      <br>
      <form action="login_setnewpassword" method="POST" onsubmit="return validatePassword()">


        <input type="hidden" name="username" value="<?php echo htmlspecialchars($_GET['username']); ?>">
        <input type="text" name="code" placeholder="Enter the verification code" required>
        <input type="password" name="new_password" id="new_password" placeholder="Enter your new password" required>
        <div class="password-strength-meter">
          <div id="strength-bar"></div>
        </div>
        <div id="password-feedback" class="password-feedback"></div>
        <div id="error-message" class="error-message"></div>
        <br>
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

      .password-strength-meter {
        width: 100%;
        height: 5px;
        background-color: #ddd;
        margin-top: 5px;
        border-radius: 3px;
      }

      .password-strength-meter div {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease-in-out, background-color 0.5s ease-in-out;
      }

      .weak {
        background-color: #ff4d4d;
      }

      .medium {
        background-color: #ffa64d;
      }

      .strong {
        background-color: #4dff4d;
      }

      .password-feedback {
        margin-top: 5px;
        font-size: 0.9em;
        color: #000;
        font-weight: bold;
      }

      .error-message {
        color: red;
        font-size: 0.9em;
        margin-top: 5px;
        margin-top: 10px;
        font-weight: bold;
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
            $('#timer').css({
              'text-shadow': 'none',
              'color': 'black'
            }).text('Your Code has been Expired, Please reset again');

            $('.forgot-password-link').css('display', 'block');

            // Send AJAX request to reset 'stase' in the database
            var username = "<?php echo htmlspecialchars($_GET['username']); ?>";
            $.ajax({
              url: 'resetcode.php',
              type: 'POST',
              data: {
                username: username
              },
              success: function(response) {
                alert('Code has expired, please request a new one.');
              }
            });
          }
        },
        1000);

      $(document).ready(function() {
        $('#form-checkbox').click(function() {
          var passwordField = $('#new_password');
          if ($(this).is(':checked')) {
            passwordField.attr('type', 'text');
          } else {
            passwordField.attr('type', 'password');
          }
        });
        $('#new_password').on('input', function() {
          var password = $(this).val();
          var strength = checkPasswordStrength(password);
          updatePasswordStrengthMeter(strength);
        });

        $('#new_password').on('input', function() {
          var password = $(this).val();
          var strength = checkPasswordStrength(password);
          updatePasswordStrengthMeter(strength);
        });

        function checkPasswordStrength(password) {
          var strength = 0;
          if (password.length >= 8) strength += 1;
          if (password.match(/[a-z]+/)) strength += 1;
          if (password.match(/[A-Z]+/)) strength += 1;
          if (password.match(/[0-9]+/)) strength += 1;
          return strength;
        }

        //Akan Otomatis Password Kuat bila memiliki Huruf Besar, Huruf Kecil, dan Angka (dalam salah satu tulisan new password) 
        function updatePasswordStrengthMeter(strength) {
          var $strengthBar = $('#strength-bar');
          var $feedback = $('#password-feedback');
          var $submitButton = $('#submit-button');
          var width = (strength / 4) * 100;
          $strengthBar.css('width', width + '%');

          if (strength <= 2) {
            $strengthBar.removeClass().addClass('weak');
            $feedback.text("Password lemah. Gunakan kombinasi huruf besar, huruf kecil, dan angka.");
            $submitButton.prop('disabled', true);
          } else if (strength == 3) {
            $strengthBar.removeClass().addClass('medium');
            $feedback.text("Password sedang. Tambahkan lebih banyak variasi untuk membuat password lebih kuat.");
            $submitButton.prop('disabled', false);
          } else {
            $strengthBar.removeClass().addClass('strong');
            $feedback.text("Password kuat!");
            $submitButton.prop('disabled', false);
          }
        }
      });

      function validatePassword() {
        var password = document.getElementById("new_password").value;
        var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        var errorMessage = document.getElementById("error-message");

        if (!passwordPattern.test(password)) {
          errorMessage.textContent = "Password harus minimal 8 karakter dan mengandung huruf kecil, huruf besar, dan angka.";
          return false;
        }
        errorMessage.textContent = "";
        return true;
      }
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