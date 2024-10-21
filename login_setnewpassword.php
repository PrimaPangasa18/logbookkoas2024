<?php
include "config.php";
error_reporting(E_ALL ^ E_NOTICE);
if (isset($_REQUEST['verify_code'])) {
    $username = $_POST['username'];
    $code = $_POST['code'];
    $new_password = md5($_POST['new_password']);  // Hash the new password

    // Verify the code
    $result = mysqli_query($con, "SELECT * FROM `admin` WHERE `username`='$username' AND `code`='$code'");
    if (mysqli_num_rows($result) == 1) {
        // Code matches, update the password
        mysqli_query($con, "UPDATE `admin` SET `password`='$new_password', `code` = NULL WHERE `username`='$username'");
        echo "Password has been reset successfully.";
        echo "$new_password";
        
        // Redirect to login page
        echo "
        <script>
            window.location.href=\"login\";
        </script>
        ";
    } else {
        echo "Invalid verification code.";
    }
}
?>
