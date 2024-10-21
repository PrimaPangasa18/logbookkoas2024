<?php
header("Content-Type: application/json");

// Database connection
include 'connect.php';  // Include your database connection file
require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
require 'vendor/autoload.php';
// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if username is provided
if (isset($data['username'])) {
    $username = $data['username'];

    $login = mysqli_query($conn, "SELECT * FROM `admin` WHERE `username`='$username'");
    $ketemu = mysqli_num_rows($login);

    // If username found
    if ($ketemu == 1) {
        $data_log = mysqli_fetch_array($login);

        if ($data_log['level'] == "5") {
            $randomNumber = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            mysqli_query($conn, "UPDATE `admin` SET `code`='$randomNumber' WHERE `username`='$username'");

            // Fetch user details
            $biodata_mhsw = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$username'"));
            $nim = $biodata_mhsw['nim'];
            $email = $biodata_mhsw['email'];
            $nama = $biodata_mhsw['nama'];

            // Send email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->Host = "ssl://smtp.gmail.com";
                $mail->Username = 'akhasadyst@gmail.com';
                $mail->Password = 'ikrsmtxtodnnqalx';
                $mail->Port = 465;

                // Email settings
                $mail->setFrom('akhasadyst@gmail.com', 'E Logbook Koas');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Forgot Password Code';
                $mail->Body = 'Hi <b>' . $nama . '</b>, This is the code for forgot password <b>' . $randomNumber . '</b>';

                $mail->send();

                // Return JSON success response
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Verification code sent to email',
                    'username' => $username,
                    'email' => $email
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email could not be sent. Error: ' . $mail->ErrorInfo
                ]);
            }
        } else {
            $randomNumber = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            mysqli_query($con, "UPDATE `admin` SET `code`='$randomNumber' WHERE `username`='$_POST[username]'");
            $username = $_POST['username'];
            $dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$_POST[username]'"));
            $nim = $dosen['nip'];
            $email = $data_log['email'];
            $nama = $dosen['nama'];

            // Send email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->Host = "ssl://smtp.gmail.com";
                $mail->Username = 'akhasadyst@gmail.com';
                $mail->Password = 'ikrsmtxtodnnqalx';
                $mail->Port = 465;

                // Email settings
                $mail->setFrom('akhasadyst@gmail.com', 'E Logbook Koas');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Forgot Password Code';
                $mail->Body = 'Hi <b>' . $nama . '</b>, This is the code for forgot password <b>' . $randomNumber . '</b>';

                $mail->send();

                // Return JSON success response
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Verification code sent to email',
                    'username' => $username,
                    'email' => $email
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email could not be sent. Error: ' . $mail->ErrorInfo
                ]);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Username not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Username is required']);
}
